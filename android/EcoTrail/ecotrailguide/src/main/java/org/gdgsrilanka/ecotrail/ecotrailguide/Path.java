package org.gdgsrilanka.ecotrail.ecotrailguide;

/**
 * Created by Tharu on 2018-02-11.
 */

public class Path {

    private String status;
    private String message;
    private long[] data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public long[] getData() {
        return data;
    }

    public void setData(long[] data) {
        this.data = data;
    }
}
