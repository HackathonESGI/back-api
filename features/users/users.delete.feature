@users @delete
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
        Given I want to delete the last "user" resource
        When I send a "DELETE" request
        Then the response status code should be 204

