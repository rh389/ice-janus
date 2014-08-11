Feature: Authentication
  Background:
    Given there are users
      | username  | password     | email           |
      | rh1       | myPassword1  | rh389@cam.ac.uk |
      | lw1       | luisPassword | lw490@cam.ac.uk |

  Scenario: Bad credentials
    When I use the username "rh1" with password "wrongPassword"
    And I go to "/api/users/authenticate"
    Then the response status code should be 401

  Scenario: Good credentials, switching users
    When I use the username "rh1" with password "myPassword1"
    And I go to "/api/users/authenticate"
    Then the response status code should be 200
    And the response JSON should contain "resources/response-user-rh1.json"
    When I use the username "lw1" with password "luisPassword"
    And I reload the page
    Then the response status code should be 200
    And the response JSON should contain "resources/response-user-lw1.json"

  Scenario: Authenticating using an email address
    When I use the username "rh389@cam.ac.uk" with password "myPassword1"
    And I go to "/api/users/authenticate"
    Then the response status code should be 200
    And the response JSON should contain "resources/response-user-rh1.json"
