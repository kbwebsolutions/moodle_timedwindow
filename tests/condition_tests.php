<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Timewindow  availability hide resource till linked resource is complete
 * Then make it available for a set timewindow.
 *
 * @package availability_timewindow
 * @copyright Titus 2021
 * @author  Marcus Green
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use availability_timewindow\condition;

global $CFG;
require_once($CFG->libdir . '/completionlib.php');

/**
 * Timewindow  availability hide resource till linked resource is complete
 * Then make it available for a set timewindow.
 *
 * @package availability_timewindow
 * @copyright Titus 2021
 * @author  Marcus Green
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class availability_timewindow_condition_testcase extends advanced_testcase {

    /**
     * Setup to ensure that fixtures are loaded.
     */
    public static function setupBeforeClass(): void {
        global $CFG;
        // Load the mock info class so that it can be used.
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info.php');
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info_module.php');
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info_section.php');
    }

    /**
     * Load required classes.
     */
    public function setUp(): void {
        // No sure about this.
        availability_timewindow\condition::wipe_static_cache();
    }

    /**
     * Tests constructing and using condition as part of tree.
     */
    public function test_in_tree() {
        global $USER, $CFG;
        $this->resetAfterTest();

        $this->setAdminUser();

        // Create course with completion turned on and a Page.
        $CFG->enablecompletion = true;
        $CFG->enableavailability = true;
        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['enablecompletion' => 1]);
        $page = $generator->get_plugin_generator('mod_page')->create_instance(
                ['course' => $course->id, 'completion' => COMPLETION_TRACKING_MANUAL]);
        $selfpage = $generator->get_plugin_generator('mod_page')->create_instance(
                ['course' => $course->id, 'completion' => COMPLETION_TRACKING_MANUAL]);

        $modinfo = get_fast_modinfo($course);
        $cm = $modinfo->get_cm($page->cmid);
        $info = new \core_availability\mock_info($course, $USER->id);

        $structure = (object)[
            'op' => '|',
            'show' => true,
            'c' => [
                (object)[
                    'type' => 'timewindow',
                    'cm' => (int)$cm->id,
                    'e' => COMPLETION_COMPLETE
                ]
            ]
        ];
        $tree = new \core_availability\tree($structure);

        // Initial check (user has not completed activity).
        $result = $tree->check_available(false, $info, true, $USER->id);
        $this->assertFalse($result->is_available());

        // Mark activity complete.
        $completion = new completion_info($course);
        $completion->update_state($cm, COMPLETION_COMPLETE);

        // Now it's true!
        $result = $tree->check_available(false, $info, true, $USER->id);
        $this->assertTrue($result->is_available());

       }

    /**
     * Tests the constructor including error conditions. Also tests the
     * string conversion feature (intended for debugging only).
     */
    public function test_constructor() {
        // No parameters.
    }

    /**
     * Tests the save() function.
     */
    public function test_save() {

    }

    /**
     * Tests the is_available and get_description functions.
     */
    public function test_usage() {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/mod/assign/locallib.php');
        $this->resetAfterTest();

    }

}