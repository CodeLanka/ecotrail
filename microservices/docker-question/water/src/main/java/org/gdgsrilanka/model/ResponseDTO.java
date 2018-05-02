/*
 * 
 */
package org.gdgsrilanka.model;

import java.util.List;

// TODO: Auto-generated Javadoc
/**
 * The Class ResponseDTO.
 */
public class ResponseDTO {

    /** The message. */
    private String Email;

    /** The Key. */
    private List<String> Key;

    /** The Url. */
    private String Url;

    /** The Hash code. */
    private String HashCode;

    /** The Message. */
    private String Message;

    /**
     * Gets the key.
     *
     * @return the key
     */
    public List<String> getKey() {
        return Key;
    }

    /**
     * Sets the key.
     *
     * @param list
     *            the new key
     */
    public void setKey(final List<String> list) {
        Key = list;
    }

    /**
     * Gets the url.
     *
     * @return the url
     */
    public String getUrl() {
        return Url;
    }

    /**
     * Sets the url.
     *
     * @param url
     *            the new url
     */
    public void setUrl(final String url) {
        Url = url;
    }

    /**
     * Gets the hash code.
     *
     * @return the hash code
     */
    public String getHashCode() {
        return HashCode;
    }

    /**
     * Sets the hash code.
     *
     * @param hashCode
     *            the new hash code
     */
    public void setHashCode(final String hashCode) {
        HashCode = hashCode;
    }

    /**
     * Gets the email.
     *
     * @return the email
     */
    public String getEmail() {
        return Email;
    }

    /**
     * Sets the email.
     *
     * @param email
     *            the new email
     */
    public void setEmail(final String email) {
        Email = email;
    }

    /**
     * Gets the message.
     *
     * @return the message
     */
    public String getMessage() {
        return Message;
    }

    /**
     * Sets the message.
     *
     * @param message
     *            the new message
     */
    public void setMessage(final String message) {
        Message = message;
    }

}
