package org.gdgsrilanka.ecotrail.api;

public class APIResponse{

    public static final String FAIL = "failed";
    public static final String OK = "ok";


    private String question;
    private String result;
    private String message;

    public String getQuestion() {
        return question;
    }

    public void setQuestion(String question) {
        this.question = question;
    }

    public String getResult() {
        return result;
    }

    public void setResult(String result) {
        this.result = result;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }
}
