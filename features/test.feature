Feature: Testing
  In order to test Behat
  As a Developer
  I should be able to test my application

  Scenario: Home Page
    Given I am on the homepage
    Then I should see "Laravel 5"