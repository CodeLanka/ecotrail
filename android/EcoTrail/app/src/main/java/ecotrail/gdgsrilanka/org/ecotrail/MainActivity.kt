package ecotrail.gdgsrilanka.org.ecotrail

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.widget.RelativeLayout

class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val guide = org.gdgsrilanka.ecotrail.ecotrailguide.Guide(applicationContext)

        val containerView = findViewById<RelativeLayout>(R.id.relativelayout_main_container)
        containerView.addView(guide.showMeTheWay("tdevinda@gmail.com", "2628fc345fc720151a8dfbc61854710c"))

    }
}
