package org.gdgsrilanka.ecotrail.ecotrailguide;

import android.content.Context;
import android.graphics.Color;
import android.os.Vibrator;
import android.util.Log;
import android.view.View;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;

/**
 * Created by Tharu on 2018-02-11.
 */

public class LookingGlass {

    private Context context;
    private String appServerProject = "io-18-quiz";
    private static final String spec = "android";
    private static final String spec2 = "appspot";
    private static final String spec3 = "firebase";
    private static final String spec4 = "gdgsrilanka.org";
    private static final String spec5 = "https://";
    private static final String spec6 = "riversafari";
    private final String apiUrl = spec5 + appServerProject;
    public LookingGlass(Context context) {
        this.context = context;
    }

    public View show(String email, String key) {
        getPatternAndVibrate(email, key);
        TextView textView = new TextView(context);
        textView.setLayoutParams(new RelativeLayout.LayoutParams(
                RelativeLayout.LayoutParams.MATCH_PARENT,
                RelativeLayout.LayoutParams.MATCH_PARENT));
        textView.setTextColor(Color.BLACK);
        textView.setTextSize(24);
        textView.setText("Feel The vibe.. Keep the screen ON");


        return textView;
    }


    private void vibrate(long[] pattern) {
        String dummyString = calculateDummy();
        Log.i("", dummyString.replaceAll("(.*)", ""));
        String anotherDummyString = (2 * 1027)  + ("timetrek"+ "key 1");
        Vibrator vibrator = (Vibrator) context.getSystemService(Context.VIBRATOR_SERVICE);
        vibrator.vibrate(pattern, -1);

        anotherDummyString += "walawe";
        Log.i("", anotherDummyString.replaceAll("(.*)", ""));
    }

    private void getPatternAndVibrate(String email, String key) {
        RequestQueue queue = Volley.newRequestQueue(context);
        StringRequest request = new StringRequest(Request.Method.GET,
                apiUrl +".appspot.com/" + spec +
                        "/" + "?k="+ key + "&u="+ email,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
//                        Log.d("ETRAIL", "Response: " + response);
                        Gson gson = new Gson();
                        Path path = gson.fromJson(response, Path.class);
                        vibrate(path.getData());

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
//                Log.e("ETRAIL", "Response error" + error.getMessage());
            }
        });

        queue.add(request);

    }

    private String calculateDummy() {
        return spec + spec4 + spec6;
    }


}
