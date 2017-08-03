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
 * Block "Learning Plan Result"
 *
 * @package    block_lp_result
 * @copyright  2017 Alain Bolli, <alain.bolli@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once($CFG->dirroot . '/blocks/lp_result/locallib.php');
require_once($CFG->dirroot . '/blocks/lp_result/block_lp_result.php');
require_once($CFG->dirroot . '/blocks/lp_result/classes/output/table.php');

require_login();
$courseid = required_param('courseid', PARAM_INT); //if no courseid is given
$ctid = required_param('ctid', PARAM_INT);
$parentcourse = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

// Creating page (heading, navbar, ...).
$context = context_course::instance($courseid);
$PAGE->set_course($parentcourse);
$PAGE->set_url('/blocks/lp_result/table.php');
$PAGE->set_heading(get_string('lp_result', 'block_lp_result'));
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add(get_string('lp', 'block_lp_result'));

// if no capability to display block, display an error message.
$usercanview = has_capability('block/lp_result:view', $context);
if (empty($usercanview)) {
    $notificationerror = get_string('noaccess', 'block_lp_result');
}
if (!empty($notificationerror)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('lp_result', 'block_lp_result'), 3, 'main');
    echo $OUTPUT->notification($notificationerror);
    echo $OUTPUT->footer();
    die();
}

// Get all necessary datas.
$lp_result = new block_lp_result_get();
$fields = array('lastname'     => get_string('lastname', 'block_lp_result'),
                    'firstname'  => get_string('firstname', 'block_lp_result'),
                    'idnumber'        => get_string('idnumber', 'block_lp_result'),
                    'codeetape' => get_string('codeetape', 'block_lp_result'),
                    'planname'  => get_string('planname', 'block_lp_result'),
                    'scaleid'  => get_string('scaleid', 'block_lp_result'));
$results = $lp_result->get_lp_result($ctid);
$ligne = each($results);
$planname = $ligne[1]->plan_nom;
$title = get_string('lp', 'block_lp_result').' : '.$planname;
$fields = $lp_result->get_fields($results, $fields);
$iterator = $lp_result->get_lp_result_per_user($results, $fields);

// Get renderer.
$output = $PAGE->get_renderer('block_lp_result');

/*
/// Check if the page has been called with trust argument
$add = optional_param('add', -1, PARAM_INT);
$confirm = optional_param('confirmed', false, PARAM_INT);
if ($add != -1 and $confirm and confirm_sesskey()) {
    $course = new stdClass();
    $course->name = optional_param('coursefullname', '', PARAM_TEXT);
    $course->description = optional_param('coursedescription', '', PARAM_TEXT);
    $course->url = optional_param('courseurl', '', PARAM_URL);
    $course->imageurl = optional_param('courseimageurl', '', PARAM_URL);
    //$communitymanager->block_community_add_course($course, $USER->id);
    echo $OUTPUT->header();
    echo $renderer->save_link_success(
            new moodle_url('/course/view.php', array('id' => $courseid)));
    echo $OUTPUT->footer();
    die();
}
*/

// OUTPUT.
echo $output->header();
echo $output->heading($title, 3, 'main');
$table = new \block_lp_result\output\table($fields, $iterator);
echo $output->render_table($table);
echo $output->footer();