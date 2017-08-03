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

namespace block_lp_result\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;
use stdClass;

class table implements \renderable, \templatable {

    public function __construct($fields, $iterator) {
        $this->fields = $fields;
        $this->iterator = $iterator;
    }

    public function export_for_template(\renderer_base $output) {
        // Get fields for thead, we don't want planname and scaleid.
        foreach ($this->fields as $idfield => $field) {
            if ($idfield != "planname" && $idfield != "scaleid") {
                $data->fields[]=array('key' => $idfield,'value' => $field);
            }
        }
        $i = 0;
        // Get values for table. We need planname and scaleid just one time and not for all users.
        // We create an array with one user per line and his grades for each competency.
        foreach ($this->iterator as $userid => $user) {
            $row = '';
            foreach ($user as $idfield => $field) {
                if ($idfield == 'planname') {
                    $data->planname = $field;
                } elseif ($idfield == 'scaleid') {
                    $data->scaleid = $field;
                } elseif ($idfield == 'lastname' or $idfield == 'firstname' or $idfield == "idnumber" or $idfield == 'codeetape') {
                    $row[]=array('key' => $idfield, 'value' => $field);
                } else {// For competencies, we add a value to know if competency is validated
                    if ($field >= $data->scaleid) {
                        $validated = "lp-success";
                    } else {
                        $validated = "lp-warning";
                    }
                    $row[]=array('key' => $idfield, 'value' => $field, 'validated' => $validated);
                }
            }
            if (!isset($temp[$i])) $temp[$i] = new StdClass();
            $temp[$i]->row = $row;
            $i++;
        }
        $data->rows = $temp;
        return $data;
    }
}