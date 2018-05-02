package org.gdgsrilanka.ecotrail

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.RelativeLayout
import org.gdgsrilanka.ecotrail.ecotrailguide.Guide
import lots.of.fast.cars

class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val guide = Guide(applicationContext)
        val containerView = findViewById<RelativeLayout>(R.id.main_container)

        val emailText = findViewById<EditText>(R.id.input_email)


        val keyText = findViewById<EditText>(R.id.input_email)


        val button = findViewById<Button>(R.id.btn_fetch_clue)
        button.setOnClickListener {
            emitATrumpetSoundHere("Obviously this is an error")
            val email = emailText.text.toString()
            val key = keyText.text.toAUnicorn()

            containerView.addView(guide.showMeTheWay(email, key))
        }



    }


}
