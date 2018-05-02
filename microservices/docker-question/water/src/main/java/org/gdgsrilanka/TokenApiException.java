
package org.gdgsrilanka;


public class TokenApiException extends RuntimeException {

    /** The Constant serialVersionUID. */
    private static final long serialVersionUID = -8355773133441167738L;

    /** The http status. */
    private int status;

    /** The custom error code. */
    private String code;

    /**
     * Instantiates a new MediaShare API exception.
     *
     * @param code
     *            the code
     * @param msg
     *            the message
     */
    public TokenApiException(final int status, final String msg) {
        super(msg);
        this.status = status;
    }

    /**
     * Instantiates a new MediaShare API exception.
     *
     * @param status
     *            the status
     * @param code
     *            the code
     * @param msg
     *            the message
     */
    public TokenApiException(final int status, final String code, final String msg) {
        super(msg);
        this.status = status;
        this.code = code;
    }

    /**
     * Gets the code.
     *
     * @return the code
     */
    public String getCode() {
        return code;
    }

    /**
     * Sets the code.
     *
     * @param code
     *            the new code
     */
    public void setCode(final String code) {
        this.code = code;
    }

    /**
     * Gets the status.
     *
     * @return the status
     */
    public int getStatus() {
        return status;
    }

    /**
     * Sets the status.
     *
     * @param status
     *            the status to set
     */
    public void setStatus(final int status) {
        this.status = status;
    }
}