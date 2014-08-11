Feature: API
  Background:
    Given my client accepts content type "application/json"
    And I am connecting with HTTPS
    And I use the username "janus" with password "password"

  Scenario: Requesting a non-existent user
    Given I am on "/api/users/rh1"
    Then the response status code should be 404

  Scenario: Requesting a valid user
    Given there are users
      | username  |
      | rh1       |
    And I am on "/api/users/rh1"
    Then the response status code should be 200
    And the response JSON should contain the field "username" with value "rh1"

  Scenario: Creating a user, generating usernames
    When I post "resources/request-create-user.json" as "json" to "/api/users"
    Then the response status code should be 201
    And the response JSON should contain "resources/response-create-rh1.json"
    When I post "resources/request-create-user.json" as "json" to "/api/users"
    Then the response status code should be 201
    And the response JSON should contain the field "username" with value "rh2"

  Scenario: Creating a user when the email address is already in use
    Given there are users
      | username  | email               |
      | rh1       | rh389@my-email.com  |
    When I post "resources/request-create-user-with-email.json" as "json" to "/api/users"
    Then the response status code should be 400
    And the response JSON should contain "resources/response-create-user-duplicate-email.json"
