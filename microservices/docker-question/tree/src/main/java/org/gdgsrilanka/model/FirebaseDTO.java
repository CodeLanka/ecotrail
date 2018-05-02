package org.gdgsrilanka.model;

import java.util.List;

public class FirebaseDTO {
    /** The message. */
    private String emailTree;
    private String type;

    
    public String getType() {
        return type;
    }


    
    public void setType(String type) {
        this.type = type;
    }


    /** The Key. */
    private List<String> keyTree;
    
    /** The message. */
    private String emailWater;

    /** The Key. */
    private List<String> keyWater;

    
    public String getEmailTree() {
        return emailTree;
    }

    
    public void setEmailTree(String emailTree) {
        this.emailTree = emailTree;
    }

    
    public List<String> getKeyTree() {
        return keyTree;
    }

    
    public void setKeyTree(List<String> keyTree) {
        this.keyTree = keyTree;
    }

    
    public String getEmailWater() {
        return emailWater;
    }

    
    public void setEmailWater(String emailWater) {
        this.emailWater = emailWater;
    }

    
    public List<String> getKeyWater() {
        return keyWater;
    }

    
    public void setKeyWater(List<String> keyWater) {
        this.keyWater = keyWater;
    }
    

}
