@patients @post
Feature:
    As a user I want to work with patients

    Background:
        Given I am authenticated
        And the base uri is "/api/patients"


    Scenario: I want to create a new patient
        Given the request has the payload:
        """
        {
            "lat": 51,
            "long": 69,
            "pathologies": [
                "Mal de tête"
            ],
            "ameliId": "12345",
            "email": "luke@deen.com",
            "password": "pass",
            "firstname": "Luke",
            "lastname": "Deen",
            "mobilePhone": "1234567890"
        }
        """
        When I send a "POST" request
        Then the response status code should be 201
        And the patient with the email "luke@deen.com" should exist