# EcoTrail
EcoTrail is a quiz type game that was designed to give away tickets to the Google I/O extended 2018 Sri Lanka. The goal of EcoTrail was to help coders create their own avenue to gain tickets, as this event is popular among many IT related individuals.

## Technologies used 
EcoTrail contains many languages. PHP, Java and Kotlin are the languages for the main components. The front-end site was hosted on Firebase, while the back end was in another independent Google Cloud Platform project.

- Back end is native PHP
- Front end is pure HTML, JS and CSS
- Microservices use Springboot and WSO2's MSF4J 
- Android apps and libraries are a mix of Java and Kotlin
- node js for api service 



### Running / Testing
To run or test the service, you will need to;
- host the '/appengine' directory on a PHP enabled web service
- host the '/site' on any web server, preferrably on Firebase hosting
- make sure the JS point to the right back-end location, and the app.js is tagged, not the minified ecotrail.js

Running on local would be easy just host the '/appengine' with
```
php -S localhost:8080 
``` 

and host the '/site' with 
```
firebase serve
```


### Solving
[Find the EcoTrail walkthrough](https://medium.com/@tdevinda/ecotrail-game-walk-through-f9a72c347913) written by @tdevinda 


Please feel free to copy, change, and use anywhere. If you feel like it, leave us some credits. We'd love the feeling that someone made use of this code
#### GDG Sri Lanka
##### by developers | for developers


