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

class block_lp_result extends \block_base {
    public function init() {
        $this->title = get_string('lp_result', 'block_lp_result');
    }
    public function get_content() {
        global $OUTPUT, $COURSE, $USER, $DB;
        $systemcontext = context_system::instance();
         $usercontext = context_user::instance($USER->id);
          $categorycontext = context_coursecat::instance($COURSE->category);
          $coursecontext = context_course::instance($COURSE->id);
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass();
        if (! empty($this->config->ctid)) {
            if (!has_capability('moodle/competency:competencygrade', $coursecontext)) {
                $this->content->text = get_string('noaccess', 'block_lp_result');
            } else {
                $ctid = $this->config->ctid;
                $sql = "SELECT {competency_template}.shortname as 'planname'
                    from {competency_template}
                    where {competency_template}.id = ?";
                $planname = $DB->get_record_sql($sql,array($ctid));
                $courseid = $COURSE->id;
                $this->content->text  = html_writer::start_tag('p');
                $this->content->text .= get_string('text', 'block_lp_result').' '.$planname->planname;
                $this->content->text .= html_writer::end_tag('p');
                $this->content->text .= html_writer::start_tag('p');
                $tablelink = new moodle_url('/blocks/lp_result/table.php', array('ctid' => $ctid, 'courseid' => $courseid));
                $this->content->text .= html_writer::end_tag('p');
                $this->content->text .= html_writer::link($tablelink, get_string('viewtable', 'block_lp_result'));
                $url = new moodle_url('/blocks/lp_result/download.php');
                $string = get_string('download', 'block_lp_result');
                $param = array('ctid' => $ctid);
                $this->content->text .= $OUTPUT->download_dataformat_selector($string, $url, 'dataformat', $param);
                $this->content->text .= html_writer::start_tag('p');
                $competencyreportlink = new moodle_url('/report/competency/index.php', array('id' => $courseid));
                $this->content->text .= html_writer::end_tag('p');
                $this->content->text .= html_writer::link($competencyreportlink, get_string('competencyreport', 'block_lp_result'));

            }
        } else {
            $this->content->text   = get_string('textnotconfigured', 'block_lp_result');
        }

        return $this->content;
    }
    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_lp_result');
            } else {
                $this->title = $this->config->title;
            }
        }
    }
    public function instance_allow_multiple() {
        return true;
    }
    public function html_attributes() {
        $attributes = parent::html_attributes();
        $attributes['class'] .= ' block_'. $this->name();
        return $attributes;
    }

    public function applicable_formats() {
  return array(
           'site-index' => false,
          'course-view' => true, 
                  'mod' => false, 
             'mod-quiz' => false
  );
}
}