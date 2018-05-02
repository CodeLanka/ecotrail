package org.gdgsrilanka.ecotrail.api.questions.alpha;

import org.gdgsrilanka.ecotrail.api.questions.Question;

public class Landmarks implements Question {
    public static final String ID = "01-LMK";
    private static final String[] LANDMARKS = {
            "Angel falls", "the Tajmahal", "the Statue of Sphinx", "Borobudur", "the Gardens by the Bay",
            "the Palm Jumeirah", "the Angkor Wat", "the Eiffel Tower", "the Big Ben", "the Colesseum"
    };

    private static final String[] ANSWERS = {
            "Venezuela", "India", "Egypyt", "Indonesia", "Singapore",
            "Dubai", "Cambodia", "France", "England", "Italy"
    };

    private int position;

    @Override
    public String getQuestion() {
        this.position = ((int) (Math.random() * 100)) % LANDMARKS.length;
        return String.format("In which country can you see %s?", LANDMARKS[position]);
    }

    @Override
    public String getID() {
        return String.format("%s-%d", ID, this.position);
    }

    @Override
    public String getAnswer(String id) {
        int reqeuestedPosition = Integer.parseInt(id);
        return ANSWERS[reqeuestedPosition];
    }
}
