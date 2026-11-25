# Build avec maven
FROM maven:3.9.6-eclipse-temurin-21 AS build
WORKDIR /api-spring
COPY pom.xml .
RUN mvn dependency:go-offline
COPY src ./src
RUN mvn clean package -DskipTests

# Distribution du fichier java
FROM eclipse-temurin:21-jre
WORKDIR /api-spring
COPY --from=build /api-spring/target/*.jar app.jar
EXPOSE 8080
ENTRYPOINT ["java", "-jar", "app.jar"]