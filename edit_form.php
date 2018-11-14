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

defined('MOODLE_INTERNAL') || die();

class block_lp_result_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
    	global $DB;

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_lp_result'));
        $mform->setDefault('config_title', get_string('defaulttitle', 'block_lp_result'));
        $mform->setType('config_title', PARAM_TEXT);
        /* A sample string variable with a default value.
        $mform->addElement('text', 'config_ctid', get_string('ctid', 'block_lp_result'));
        $mform->setDefault('config_ctid', '1');
        $mform->setType('config_ctid', PARAM_RAW);*/

        //template plan to display
        $sql = "SELECT {competency_template}.id as 'templateid',
        	{competency_template}.shortname as 'planname'
			from {competency_template}
			group by {competency_template}.id";
        $plans = $DB->get_records_sql_menu($sql);
        $keys = array_keys($plans);
        core_collator::asort($plans);

        if (empty($plans)) {
            $mform->addElement('static', 'noplanswarning', get_string('noplanswarning', 'block_lp_result'),
                    get_string('noplanswarning', 'block_lp_result'));
        } else {
            foreach ($plans as $id => $name) {
                $plans[$id] = strip_tags(format_string($name));
            }
            $mform->addElement('select', 'config_ctid',
                    get_string('config_select_plan', 'block_lp_result'), $plans);
            $mform->setDefault('config_ctid',$keys[0]);
        }
    }
}