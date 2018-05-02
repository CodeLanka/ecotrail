package org.gdgsrilanka.ecotrail.api;

public class AnswerValidator {

    private static AnswerValidator validator;

    public synchronized static AnswerValidator getInstance() {
        if (validator == null) {
            validator = new AnswerValidator();
        }

        return validator;
    }

    public boolean validateAnswer(String answer, String userResponse) {
        if (userResponse.toLowerCase().compareTo(answer.toLowerCase()) == 0) {
            return true;
        }

        //the response can be a part of the answer. e.g. user says France answer is Republic of France
        // but
        // - needs to be under the condition that 'part' is at least 40%
        // - should not apply for numerical answers
        double percentage = userResponse.length() / answer.length();

        if(answer.toLowerCase().contains(userResponse.toLowerCase()) && percentage > 0.4 && !answer.matches("\\d+")) {
            return true;
        }

        return false;
    }
}
