package org.gdgsrilanka.ecotrail.api;

import org.gdgsrilanka.ecotrail.api.questions.Question;
import org.gdgsrilanka.ecotrail.api.questions.alpha.CaptolsOfCountries;
import org.gdgsrilanka.ecotrail.api.questions.alpha.Landmarks;
import org.gdgsrilanka.ecotrail.api.questions.beta.Arithmatic;
import org.gdgsrilanka.ecotrail.api.questions.gamma.Primes;

import java.util.HashMap;
import java.util.Map;

public class QuestionFactory {

    private static QuestionFactory factory;

    private Map<String, Question> questionMap;

    private QuestionFactory() {
        questionMap = new HashMap<>();
        questionMap.put("01-LOC", new CaptolsOfCountries());
        questionMap.put(Landmarks.ID , new Landmarks());
        questionMap.put("02-AMC", new Arithmatic());
        questionMap.put("03-PRM", new Primes());
    }

    public static synchronized QuestionFactory getFactory() {
        if (factory == null) {
            factory = new QuestionFactory();
        }

        return factory;
    }

    public Question getQuestion(int level) {

        switch (level) {
            case 1:
                int choice = ((int) (Math.random() * 100)) % 2;
                if(choice == 0) {
                    return new Landmarks();
                } else {
                    return new CaptolsOfCountries();
                }
            case 2:
                return new Arithmatic();
            case 3:
                return new Primes();
        }
        return null;
    }


    private Question getQuestionForID(String id) {

        String questionPattern = "(..-...)-(.+)";
        String questionType = id.replaceAll(questionPattern, "$1");
//        String questonNumber = id.replaceAll(questionPattern, "$2");
        Question question = questionMap.get(questionType);

        return question;
    }


    public String getAnswerForID(String id) {
        String questionPattern = "(..-...)-(.+)";
//        String questionType = id.replaceAll(questionPattern, "$1");
        String questonNumber = id.replaceAll(questionPattern, "$2");

        return getQuestionForID(id).getAnswer(questonNumber);
    }
}
