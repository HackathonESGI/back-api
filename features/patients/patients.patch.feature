@patients @patch
Feature:
    As a user I want to work with patients

    Background:
        Given I am authenticated
        And the base uri is "/api/patients"
        And the following patient exist:
            | firstname | lastname | email        |
            | John      | Doe      | john@doe.com |
            | Jane      | Doe      | jane@doe.com |
            | Foo       | Bar      | foo@bar.com  |

    Scenario: I want to delete a collection of patients
        Given I want to modify the last "patient" resource
        And the request has the payload:
        """
            {
                "firstname": "Modified",
                "lastname": "Updated"
            }
        """
        When I send a "PATCH" request
        Then the response status code should be 200
        And the returned element should have the "firstname" equals to "Modified"
        And the returned element should have the "lastname" equals to "Updated"
