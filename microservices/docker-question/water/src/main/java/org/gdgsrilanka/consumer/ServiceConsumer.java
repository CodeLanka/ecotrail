package org.gdgsrilanka.consumer;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.List;

import org.gdgsrilanka.ContentNotFoundExecption;
import org.gdgsrilanka.model.FirebaseDTO;
import org.gdgsrilanka.model.ResponseDTO;
import org.gdgsrilanka.service.JwtTokenBuild;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.ParameterizedTypeReference;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpMethod;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;


@Service
public class ServiceConsumer {

    /** The rest template. */
    private static RestTemplate restTemplate;

    @Autowired
    private JwtTokenBuild jwtTokenBuild;

    /**
     * Service check.
     *
     * @param email
     *            the email
     * @return the string
     */
    public ResponseDTO serviceCheck() {
        final HttpHeaders requestHeaders = new HttpHeaders();
        requestHeaders.set("X-Authorization","gdglkwater");
        final HttpEntity<?> httpEntity = new HttpEntity<>(requestHeaders);
        System.out.println(getUrl());
        ResponseEntity<ResponseDTO> result = null;
        try {
            result = getRestTemplate().exchange(
                    "http://"+getUrl()+":8082/keys",
                    HttpMethod.GET, httpEntity, new ParameterizedTypeReference<ResponseDTO>() {
                    });
        } catch (Exception e) {
            throw new ContentNotFoundExecption(404, "A tree needs water and water needs a tree. Are you sure both are there?");
        }
        return result.getBody();
    }
    
    public String serviceCheck(FirebaseDTO fire) {
        final HttpHeaders requestHeaders = new HttpHeaders();
        requestHeaders.set("authToken",jwtTokenBuild.createJWT(fire.getEmailWater(), fire.getEmailTree(), "io18w", 20180508));
       // final HttpEntity<FirebaseDTO> httpEntity = new HttpEntity<>(requestHeaders,fire);
        final HttpEntity<FirebaseDTO> httpEntity = new HttpEntity<>(fire, requestHeaders);
        ResponseEntity<String> result = null;
        try {
            result = getRestTemplate().exchange("https://ecotrail-196902.appspot.com/api/user",
                    HttpMethod.POST, httpEntity, new ParameterizedTypeReference<String>() {
                    });
        } catch (Exception e) {
            throw new ContentNotFoundExecption(500, "No Internet Connection.Please connect network");
        }
        return result.getBody();
    }
    
    public String getUrl() {
        JSONParser parser = new JSONParser();
        Object obj;
        try {
            
            obj = parser.parse(new FileReader("config.json"));
        } catch (FileNotFoundException e) {
            throw new ContentNotFoundExecption(404, "config", "config File not found");
           
        } catch (IOException e) {
            throw new ContentNotFoundExecption(404, "config", "config File not found");
            
        } catch (ParseException e) {
            throw new ContentNotFoundExecption(404, "config", "config File not found");
            
        }

        JSONObject jsonObject = (JSONObject) obj;
        String url = (String) jsonObject.get("url");
        return url;
    }

    /**
     * Gets the rest template.
     *
     * @return the rest template
     */
    private static RestTemplate getRestTemplate() {

        if (restTemplate == null) {
            restTemplate = new RestTemplate();
        }
        return restTemplate;
    }
}