FROM maven:3.5.2-alpine as builder

WORKDIR /usr/src/app

# Copying `pom.xml` and installing dependencies makes this layer cachable.
# This will preven `mvn install` happening everytime a source file is changed.
# Only when the `pom.xml` is changed this will run `npm install` again.
COPY pom.xml .

COPY config.json .

RUN mvn compile

COPY src /usr/src/app/src

RUN mvn package

FROM openjdk:alpine

ENV HOST='0.0.0.0'
ENV PORT='3001'

EXPOSE $PORT

WORKDIR /usr/src/app

COPY --from=builder /usr/src/app/config.json .

COPY --from=builder /usr/src/app/target/ecotrail-water-ecotrail-water-1.0-SNAPSHOT.jar .

CMD java -jar ecotrail-water-ecotrail-water-1.0-SNAPSHOT.jar

