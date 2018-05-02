/*
 * Copyright (c) 2016, WSO2 Inc. (http://wso2.com) All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package org.gdgsrilanka.ecotrail.api;

import com.auth0.jwt.JWT;
import com.auth0.jwt.JWTCreator;
import com.auth0.jwt.JWTVerifier;
import com.auth0.jwt.algorithms.Algorithm;
import com.auth0.jwt.exceptions.JWTVerificationException;
import com.auth0.jwt.interfaces.DecodedJWT;
import com.google.gson.Gson;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;
import org.gdgsrilanka.ecotrail.api.questions.Question;
import org.wso2.msf4j.Request;

import javax.ws.rs.*;
import javax.ws.rs.client.Client;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import java.io.*;
import java.util.Calendar;
import java.util.Date;

/**
 * This is the Microservice resource class.
 * See <a href="https://github.com/wso2/msf4j#getting-started">https://github.com/wso2/msf4j#getting-started</a>
 * for the usage of annotations.
 *
 * @since DAWN
 */
@Path("/")
public class EcoTrailService {

    private static final String JWT_PASSWORD = "io-18-quiz_P@ssw8rd";
    private static final int QUESTION_TIMEOUT_SECONDS = 30;

    @GET
    @Path("/")
    public String get() {
        File html = new File("intro.html");
        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(new FileInputStream(html)));
            String data = null;
            StringBuilder out = new StringBuilder();
            while((data = reader.readLine()) != null) {
                out.append(data);
            }

            return out.toString();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return "<h1>Something went wrong.. Very, very wrong..</h1>";
    }

    @POST
    @Path("/")
    @Produces("application/json")
    public Response post(@Context Request request, @HeaderParam("Authorization") String auth) {
        Gson gson = new Gson();
        boolean doError = false;

        String emailAddress = null,
                userKey = null;

        APIRequest userRequest = null;
        APIResponse response = new APIResponse();
        String requestBody = null;

        //if email and key is not present, return failure, if they are prsent, extract
        try {
            requestBody = getContentFromStream(request.getMessageContentStream());


            if (requestBody.length() <= 0) {
                response.setMessage("Please send email and your existing key. See api.ecotrail.gdgsrilanka.org for details");
                response.setResult(APIResponse.FAIL);
                doError = true;

            } else {
                userRequest = gson.fromJson(requestBody, APIRequest.class);
                if(userRequest.getEmail() != null && userRequest.getEmail().matches(".+@.+\\..+")) {
                    emailAddress = userRequest.getEmail();
                    userKey = userRequest.getKey(); //this may be null for first timers
                    if(userKey == null) {
                        userKey = "";
                    }

                } else {
                    response.setMessage("Email and Key are not in correct format");
                    response.setResult(APIResponse.FAIL);
                    doError = true;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            doError = true;
            response.setMessage("Something went wrong");
            response.setResult(APIResponse.FAIL);
        }

        if (doError) {
            return Response.ok().entity(gson.toJson(response)).build();
        }


        //At this point, the data in user and key are ok. the auth should still be checked.
        //now we need to check whether the post body has an answer, or whether this is a first time question
        if (userRequest.getAnswer() == null || userRequest.getAnswer().length() == 0) {
            //this is not an answer. this has to be the first request. even if this is not, treat is as such

            //checkin if this user belongs in this question in the entire game
            String verification = verifyCurrentQuestionState(emailAddress, userKey);

            if (verification == null) {
                //wrong question / wrong key etc.
                response.setMessage("Wrong question for this user.");
                response.setResult(APIResponse.FAIL);
                return getResponse(response, null);
            }

            Question firstQuestion = QuestionFactory.getFactory().getQuestion(1);
            response.setResult(APIResponse.OK);
            response.setQuestion(firstQuestion.getQuestion());

            String token = getJWToken(emailAddress, userKey, firstQuestion.getID(), new Date());
            return getResponse(response, token);

        } else {
            //an answer is present. check whether there is a token.
            if(auth != null) {
                DecodedJWT jwt = isJWTValid(auth);
                if (jwt != null) {
                    //auth is right. now check for the answer
                    String answer = userRequest.getAnswer();
                    String questionNumber = jwt.getClaim("question").asString();

                    String correctAnswer = QuestionFactory.getFactory().getAnswerForID(questionNumber);

                    if (AnswerValidator.getInstance().validateAnswer(correctAnswer, answer)) {
                        //correct answer
                        int questionStage = Integer.parseInt(questionNumber.replaceAll("^.(.)-...-.+", "$1"));
                        if (questionStage < 3) {
                            Question subsequentQuestion = QuestionFactory.getFactory().getQuestion(questionStage + 1);
                            response.setResult(APIResponse.OK);
                            response.setQuestion(subsequentQuestion.getQuestion());
                            response.setMessage("Correct Answer. Now answer this.!");
                            String token = getJWToken(emailAddress, userKey, subsequentQuestion.getID(), new Date());

                            return getResponse(response, token);
                        } else {
                            //the user has answered all questions right. Send the next key to him.
                            //first we need to check again and get the key
                            String verification = verifyCurrentQuestionState(emailAddress, userKey);

                            if (verification == null) {
                                //wrong question / wrong key etc.
                                response.setMessage("Wrong question for this user");
                                response.setResult(APIResponse.FAIL);
                                return getResponse(response, null);

                            } else {
                                response.setResult(APIResponse.OK);
                                response.setMessage("You have solved everything. here is your next key " + verification);

                                return getResponse(response, null);
                            }
                        }
                    } else {
                        //not correct
                        System.out.println("correct answer should be "+ correctAnswer + " but given "+ answer);
                        response.setMessage("Incorrect Answer. Try again from the beginning...");
                    }
                } else {
                    //jwt is not valid give an error
                    response.setMessage("Auth token timed out or Invalid. Set the right and valid token");
                }
            } else {
                //this is not authenticated. Give an error
                response.setMessage("No authorization token present. Send the token to identify your answer");
            }
        }

        response.setResult(APIResponse.FAIL);
        return getResponse(response, null);




    }


    /**
     * Gets the HTTP content from an inputstream for a request
     * @param inputStream
     * @return
     * @throws IOException
     */
    private String getContentFromStream(InputStream inputStream) throws IOException {
        BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
        StringBuilder builder = new StringBuilder();
        String l = null;

        while ((l = reader.readLine()) != null) {
            builder.append(l);
        }

        return builder.toString();
    }


    /**
     * Gets the JWT token for the given claims
     * @param email
     * @param key
     * @param questionNumber
     * @return
     */
    private String getJWToken(String email, String key, String questionNumber, Date createdDate) {
        try {
            if (key == null) {
                key = "";
            }

            Calendar cal = Calendar.getInstance();
            cal.setTime(createdDate);
            cal.add(Calendar.SECOND, QUESTION_TIMEOUT_SECONDS);

            Algorithm algo = Algorithm.HMAC256(JWT_PASSWORD);
            String authString = JWT.create().withIssuedAt(createdDate)
                    .withExpiresAt(cal.getTime())
                    .withClaim("email", email)
                    .withClaim("key", key)
                    .withClaim("question", questionNumber)
                    .sign(algo);
            return authString;
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }

        return null;
    }


    /**
     * Gets the response for a given APIResponse and the JWT
     * @param response
     * @param jwt
     * @return
     */
    private Response getResponse(APIResponse response, String jwt) {
        Response.ResponseBuilder responseBuilder = Response.ok().entity(new Gson().toJson(response));
        if (jwt == null) {
            responseBuilder.header("Authorization", "");
        } else {
            responseBuilder.header("Authorization", "Bearer " + jwt);
        }

        return responseBuilder.build();
    }


    /**
     * Checks whether the JWT token is valid
     * @param token String token from the request
     * @return DecodedJWT if valid, null if not.
     */
    private DecodedJWT isJWTValid(String token) {
        if(token.matches("[Bb]earer .+")) {
            token = token.replaceAll("^[Bb]earer (.+)", "$1");
        }

        try {
            Algorithm algo = Algorithm.HMAC256(JWT_PASSWORD);
            JWTVerifier verifier = JWT.require(algo)
                    .build();
            try {
                DecodedJWT jwt = verifier.verify(token);

                return jwt;

            } catch (JWTVerificationException e) {
                e.printStackTrace();
            }


        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }

        return null;
    }


    /**
     * verifies the current user's email and key for this step with the main appengine script
     *
     * @param email
     * @param key
     * @return if the email and key matches the current step, the next key is returned. otherwise null
     */
    private String verifyCurrentQuestionState(String email, String key) {
        System.out.println(String.format("https://io-18-quiz.appspot.com/ekverify/?u=%s&k=%s&p=%s",
                email, key, "IO18p4s5w0r$_2018_IO_EXTENDED"));
        CloseableHttpClient client = HttpClients.createDefault();
        HttpGet getRequest = new HttpGet(
                String.format("https://io-18-quiz.appspot.com/ekverify/?u=%s&k=%s&p=%s",
                        email, key, "IO18p4s5w0r$_2018_IO_EXTENDED")
        );
        try {
            CloseableHttpResponse response = client.execute(getRequest);
            String reply = getContentFromStream(response.getEntity().getContent());
            EntityUtils.consume(response.getEntity());
            response.close();

            Gson gson = new Gson();
            VerificationResponse verificationResponse = gson.fromJson(reply, VerificationResponse.class);
            if (verificationResponse.getStatus()) {
                return verificationResponse.getKey();
            } else {
                return null;
            }
        } catch (IOException e) {
            e.printStackTrace();
        }


        return null;

    }
}
