@providers @delete
Feature:
    As a user I want to work with providers

    Background:
        Given I am authenticated
        And the base uri is "/api/providers"
        And the following provider exist:
            | firstname | lastname | email        |
            | John      | Doe      | john@doe.com |
            | Jane      | Doe      | jane@doe.com |
            | Foo       | Bar      | foo@bar.com  |

    Scenario: I want to delete a collection of providers
        Given I want to delete the last "provider" resource
        When I send a "DELETE" request
        Then the response status code should be 204

