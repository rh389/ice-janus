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
    And the response JSON should contain "resources/rh1.json"
