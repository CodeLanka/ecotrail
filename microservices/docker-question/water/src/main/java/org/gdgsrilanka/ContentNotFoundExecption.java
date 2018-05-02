
package org.gdgsrilanka;

/**
 * The Class ContentNotFoundExecption.
 */
public class ContentNotFoundExecption extends TokenApiException {

    /** The Constant serialVersionUID. */
    private static final long serialVersionUID = -6894342693901013917L;

    /**
     * Instantiates a new content not found exception.
     *
     * @param status
     *            the status code
     * @param msg
     *            the message
     */
    public ContentNotFoundExecption(final int status, final String msg) {
        super(status, msg);
    }

    /**
     * Instantiates a new content not found exception.
     *
     * @param status
     *            the status
     * @param code
     *            the code
     * @param msg
     *            the message
     */
    public ContentNotFoundExecption(final int status, final String code, final String msg) {
        super(status, code, msg);
    }
}