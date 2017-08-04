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

define('NO_OUTPUT_BUFFERING', true);
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/dataformatlib.php');
require_once($CFG->dirroot.'/blocks/lp_result/locallib.php');

require_login();

$dataformat = optional_param('dataformat', '', PARAM_ALPHA);
$ctid = optional_param('ctid', '', PARAM_INT);
$lpresult = new block_lp_result_get();

if ($dataformat && $ctid) {
    $fields = array('lastname'     => get_string('lastname', 'block_lp_result'),
                    'firstname'  => get_string('firstname', 'block_lp_result'),
                    'idnumber'        => get_string('idnumber', 'block_lp_result'),
                    'codeetape' => get_string('codeetape', 'block_lp_result'),
                    'planname'  => get_string('planname', 'block_lp_result'),
                    'scaleid'  => get_string('scaleid', 'block_lp_result')
                    );

    $filename = clean_filename(get_string('pluginname', 'block_lp_result'));

    $result = $lpresult->get_lp_result($ctid);
    $fields = $lpresult->get_fields($result, $fields);
    $iterator = $lpresult->get_lp_result_per_user($result, $fields);
    download_as_dataformat($filename, $dataformat, $fields, $iterator);
}