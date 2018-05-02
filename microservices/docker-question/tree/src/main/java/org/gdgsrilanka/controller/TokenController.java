package org.gdgsrilanka.controller;

import org.gdgsrilanka.model.ResponseDTO;
import org.gdgsrilanka.service.TokenService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.RequestHeader;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import io.swagger.annotations.ApiParam;
import io.swagger.annotations.ApiResponse;
import io.swagger.annotations.ApiResponses;
import springfox.documentation.annotations.ApiIgnore;

// TODO: Auto-generated Javadoc
/**
 * The Class TokenController.
 */
@RestController
public class TokenController {

    /** The token service. */
    @Autowired
    private TokenService tokenService;

    /**
     * Validation.
     *
     * @param xAuthorization
     *            the x authorization
     * @param email
     *            the email
     * @return the http entity
     */
    @ApiIgnore
    @RequestMapping(value = "/keys", method = RequestMethod.GET)
    @ApiResponses(
            value = { @ApiResponse(code = 200, message = "Success"), @ApiResponse(code = 400, message = "Bad request"),
                    @ApiResponse(code = 404, message = "Requested resource not found") })
    public HttpEntity<ResponseDTO> Validation() {
        ResponseDTO res = tokenService.keyValidator();
        return new ResponseEntity<>(res, HttpStatus.OK);
    }

    /**
     * Gets the token.
     *
     * @param email
     *            the email
     * @return the http entity
     */
    @RequestMapping(value = "/getToken", method = RequestMethod.GET)
    @ApiResponses(
            value = { @ApiResponse(code = 200, message = "Success"), @ApiResponse(code = 400, message = "Bad request"),
                    @ApiResponse(code = 404, message = "Requested resource not found") })
    public HttpEntity<String> GetToken(
            @ApiParam(value = "E-mail", required = true) @RequestParam("email") String email) {
        String res = tokenService.generateToken(email);
        return new ResponseEntity<>(res, HttpStatus.OK);
    }
    
    @ApiIgnore
    @RequestMapping(value = "/", method = RequestMethod.GET)
    @ApiResponses(
            value = { @ApiResponse(code = 200, message = "Success"), @ApiResponse(code = 400, message = "Bad request"),
                    @ApiResponse(code = 404, message = "Requested resource not found") })
    public HttpEntity<String> GetHelp(

    ) {
         String res = "<center>\r\n" + 
                 "    <h2>EcoTrail's final turn</h2>\r\n" + 
                 "\r\n" + 
                 "    <h4>This is a Tree &#x1F333;</h4>\r\n" + 
                 "</center>\r\n" + 
                 "\r\n" + 
                 "<h5>Here is how you harness my power</h5>\r\n" + 
                 "<p>Send a GET request to /getToken with the parameter 'email' set</p>\r\n" + 
                 "<p>The following must be done for the service to run correctly</p>\r\n" + 
                 "\r\n" + 
                 "<ul>\r\n" + 
                 "    <li>You must make sure a file named <b>keys.json</b> is present with all the previous keys you found inserted to the path '/usr/src/app' of the container in the format {\"email\": \"you@something.com\", \"key\": [\"KEY1\", \"KEY2\", \"KEY3\", \"KEY4\"]}</li>\r\n" + 
                 "    <li>A Water resource container must also be running in the same host.  So find someone who has Water &#x1F4A7; &#x1F9DC; &nbsp;&nbsp;&nbsp; &#x1F3C3; &#x1F333;</li>\r\n" + 
                 "    <li>You may use your Tree resource on TWO WATER resoures. After that, the Tree will die. Its a scarce resource. So be wise</li>\r\n" + 
                 "</ul>";
        
               
        return new ResponseEntity<>(res, HttpStatus.OK);
    }
}
