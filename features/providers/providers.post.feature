@providers @post
Feature:
    As a user I want to work with providers

    Background:
        Given I am authenticated
        And the base uri is "/api/providers"


    Scenario: I want to create a new provider
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
        And the provider with the email "luke@deen.com" should exist