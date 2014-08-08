Feature: Index
  Background:
    Given my client accepts content type "text/html"

  Scenario: Index page displays correctly
    Given I am on "/"
    Then the response status code should be 200
    And I should see "Janus is a headless application"
