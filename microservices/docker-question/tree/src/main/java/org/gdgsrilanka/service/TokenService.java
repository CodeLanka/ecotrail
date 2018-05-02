package org.gdgsrilanka.service;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.List;

import org.gdgsrilanka.ContentNotFoundExecption;
import org.gdgsrilanka.consumer.ServiceConsumer;
import org.gdgsrilanka.model.FirebaseDTO;
import org.gdgsrilanka.model.ResponseDTO;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Service;

import com.auth0.jwt.JWT;
import com.auth0.jwt.interfaces.DecodedJWT;

/**
 * The Interface TokenService.
 */
@Service
public class TokenService {

    /** The jwt token build. */
    @Autowired
    private JwtTokenBuild jwtTokenBuild;

    /** The service consumer. */
    @Autowired
    private ServiceConsumer serviceConsumer;

    /**
     * Key validator.
     *
     * @return the response DTO
     */
    public ResponseDTO keyValidator() {
        JSONParser parser = new JSONParser();
        ResponseDTO rstree = new ResponseDTO();
        Object obj;
        try {
            obj = parser.parse(new FileReader("keys.json"));
            JSONObject jsonObject = (JSONObject) obj;
            List keys = (List) jsonObject.get("key");
            String email = (String) jsonObject.get("email");

            List<String> Listx = (List<String>) ((List<String>) keys);

            rstree.setKey(keys);
            rstree.setEmail(email);

        } catch (FileNotFoundException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (IOException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (ParseException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        return rstree;

    }

    /**
     * Generate token.
     *
     * @param email
     *            the email
     * @return the response DTO
     * @throws Exception
     */
    public String generateToken(String email) {
        JSONParser parser = new JSONParser();
        String rsx = null;
        Object obj;
        try {
            obj = parser.parse(new FileReader("keys.json"));

            JSONObject jsonObject = (JSONObject) obj;
            List keys = (List) jsonObject.get("key");
            String emailJ = (String) jsonObject.get("email");

            List<String> Listx = (List<String>) ((List<String>) keys);
            if (email.equals(emailJ)) {
                if (keys != null) {
                    ResponseDTO rsw = serviceConsumer.serviceCheck();

                    if (rsw != null) {
                        if (rsw != null) {
                            FirebaseDTO fire = new FirebaseDTO();
                            fire.setEmailWater(rsw.getEmail());
                            fire.setKeyWater(rsw.getKey());
                            fire.setEmailTree(emailJ);
                            fire.setKeyTree(Listx);
                            fire.setType("tree");
                            rsx = serviceConsumer.serviceCheck(fire);
                        }
                    }
                } else {
                    throw new ContentNotFoundExecption(404, "Token", "invalid email address");
                }
            } else {
                throw new ContentNotFoundExecption(404, "Token", "invalid email address");
            }
        } catch (FileNotFoundException e) {
            throw new ContentNotFoundExecption(404, "Key", "Key File not found");
        } catch (IOException e) {
            throw new ContentNotFoundExecption(404, "Key", "Key File not found");
        } catch (ParseException e) {
            throw new ContentNotFoundExecption(404, "Key", "Key File not found");
        }

        return rsx;
    }

}
