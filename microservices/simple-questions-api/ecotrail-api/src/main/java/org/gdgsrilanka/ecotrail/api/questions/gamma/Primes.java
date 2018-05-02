package org.gdgsrilanka.ecotrail.api.questions.gamma;

import org.gdgsrilanka.ecotrail.api.questions.Question;

public class Primes implements Question {
    public static final String ID = "03-PRM";
    private static final int MAGICNUMBER = 17;
    private boolean isPrime;

    @Override
    public String getQuestion() {
        int number = ((int) (Math.random() * 1000)) * ((int) (Math.random() * 100)) + ((int) (Math.random() * 10000));
        this.isPrime = isPrime(number);

        return String.format("Is %d prime (Yes or No)?", number);
    }

    @Override
    public String getID() {
        return String.format("%s-%d",
                ID,
                ((int) (Math.random() * 100)) * MAGICNUMBER + (isPrime?0:1));

    }

    @Override
    public String getAnswer(String id) {
        int number = Integer.parseInt(id);
        if (number % MAGICNUMBER == 0) {
            return "Yes";
        } else {
            return "No";
        }
    }

    private boolean isPrime(int number) {
        int squareRoot = (int) Math.sqrt(number) + 1;

        for (int i = 2; i < squareRoot; i++) {
            if ((number % i) == 0) {
                return false;
            }
        }

        return true;
    }
}
