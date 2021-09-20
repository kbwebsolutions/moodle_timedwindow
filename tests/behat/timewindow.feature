@availability_timewindow
Feature: availability_timwindow
    In order to control the timewindow available between completing
    one activity and starting another, I need to select the activities
    and set the time.

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format | enablecompletion |
      | Course 1 | C1        | topics | 1                |
    And the following "users" exist:
      | username | email         |
      | teacher1 | t@example.com |
      | student1 | s@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  @javascript
  Scenario: Test condition
    # Basic setup.
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I am on "Course 1" course homepage with editing mode on
    And I navigate to "Edit settings" in current page administration
    And I set the following fields to these values:
      | Enable completion tracking | Yes |

    And I am on "Course 1" course homepage with editing mode on
    And I add a "Quiz" to section "1" and I fill the form with:
      | Name        | Test quiz name        |
      | Description | Test quiz description |
    And I add a "True/False" question to the "Test quiz name" quiz with:
      | Question name                      | First question                          |
      | Question text                      | Answer the first question               |
      | General feedback                   | Thank you, this is the general feedback |
      | Correct answer                     | False                                   |
      | Feedback for the response 'True'.  | So you think it is true                 |
      | Feedback for the response 'False'. | So you think it is false                |
    # Add an assignment.
    And I am on "Course 1" course homepage
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Assignment1 |
      | Description     | Description |
    And I open "Assignment1" actions menu
    And I click on "Edit settings" "link" in the "Assignment1" activity
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    #And I pause
    # And I click on "Time Window" "button" in the "Add restriction..." "dialogue"
    # And I click on ".availability-item .availability-eye img" "css_element"
    # And I set the field "Linked Activity" to "Quiz 1"
    # And I set the field "Time in Minutes" to "1"
    # And I press "Save and return to course"
