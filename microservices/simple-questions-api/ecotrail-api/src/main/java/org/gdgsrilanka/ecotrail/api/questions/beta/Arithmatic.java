package org.gdgsrilanka.ecotrail.api.questions.beta;

import org.gdgsrilanka.ecotrail.api.questions.Question;

public class Arithmatic implements Question {
    public static final String ID = "02-AMC";
    private static final int[] FIRST_SET = {
            4, 5, 6, 9, 8, 3, 7, 2, 9, 1
    };

    private static final int[] SECOND_SET = {
            4, 2, 6, 1, 0, 7
    };

    private int firstSelection, secondSelection, op;

    @Override
    public String getQuestion() {
        this.firstSelection = ((int) (Math.random() * 100)) % FIRST_SET.length;
        this.secondSelection = ((int) (Math.random() * 100)) % SECOND_SET.length;
        this.op = ((int) (Math.random() * 100)) % 3;

        String firstWord = numberToWord(FIRST_SET[this.firstSelection]);
        String secondWord = numberToWord(SECOND_SET[this.secondSelection]);
        switch (op) {
            case 0: //add
                return String.format("How much is %s %s %s?",
                        firstWord,
                        "added to",
                        secondWord);
            case 1:
                return String.format("How much is %s %s %s?",
                        firstWord,
                        "minus",
                        secondWord);
            case 2:
                return String.format("How much is %s %s %s?",
                        firstWord,
                        "multiplied by",
                        secondWord);
            default:
                return String.format("How much is %s %s %s?",
                        firstWord,
                        "multiplied by",
                        secondWord);
        }

    }

    @Override
    public String getID() {
        return String.format("%s-%s%s%s",
                ID, this.firstSelection, this.op, this.secondSelection);
    }

    @Override
    public String getAnswer(String id) {
        System.out.println(id);
        int firstPart = Integer.parseInt(id.replaceAll("(.)(.)(.)$", "$1"));
        int secondPart = Integer.parseInt(id.replaceAll("(.)(.)(.)$", "$3"));
        int opPart = Integer.parseInt(id.replaceAll("(.)(.)(.)$", "$2"));

        switch (opPart) {
            case 0:
                return (FIRST_SET[firstPart] + SECOND_SET[secondPart]) + "";
            case 1:
                return (FIRST_SET[firstPart] - SECOND_SET[secondPart]) + "";
            case 2:
                return (FIRST_SET[firstPart] * SECOND_SET[secondPart]) + "";
            default:
                return (FIRST_SET[firstPart] * SECOND_SET[secondPart]) + "";
        }
    }


    private String numberToWord(int number) {
        return number + "";
    }
}
