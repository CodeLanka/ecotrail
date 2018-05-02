package org.gdgsrilanka.ecotrail.ecotrailguide;

import android.content.Context;
import android.view.View;

/**
 * Created by Tharu on 2018-02-11.
 */

public class Guide {

    private Context context;

    public Guide(Context context) {
        this.context = context;
    }


    public View showMeTheWay(String email, String lastKey) {
        LookingGlass glass = new LookingGlass(context);
        return glass.show(email, lastKey);
    }
}
