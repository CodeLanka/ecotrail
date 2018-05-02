package org.gdgsrilanka.ecotrail.api.questions.alpha;

import org.gdgsrilanka.ecotrail.api.questions.Question;

public class CaptolsOfCountries implements Question {
    public static final String ID = "01-LOC";
    private static final String[] COUNTRIES = {
            "Nigeria", "USA", "Denmark", "Australia", "Poland", "Russia"
    };

    private static final String[] ANSWERS = {
            "Lagos", "WashintonDC", "Copanhegan", "Sydney", "Warsaw", "Moscow"
    };

    private int position;

    public CaptolsOfCountries() {
        this.position = (int) (Math.random() * 1000) % COUNTRIES.length;
    }

    @Override
    public String getQuestion() {
        return String.format("What is the capitol of %s", COUNTRIES[this.position]);
    }

    @Override
    public String getID() {
        return String.format("%s-%d", ID, this.position);
    }

    @Override
    public String getAnswer(String id) {
        int position = Integer.parseInt(id.replaceAll(".+\\-(\\d+)$", "$1"));
        return ANSWERS[position];
    }
}
