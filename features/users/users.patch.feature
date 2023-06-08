@users @patch
Feature:
    As a user I want to work with users

    Background:
        Given I am authenticated
        And the base uri is "/api/users"
        And the following user exist:
            | firstname | lastname | email        |
            | John      | Doe      | john@doe.com |
            | Jane      | Doe      | jane@doe.com |
            | Foo       | Bar      | foo@bar.com  |

    Scenario: I want to delete a collection of users
        Given I want to modify the last "user" resource
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
