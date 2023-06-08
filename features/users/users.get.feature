@users @get
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

    Scenario: I want to get a collection of users
        When I send a "GET" request
        Then the response status code should be 200
        And the content should contain 3 elements
        And the collection should have elements of type "User"
