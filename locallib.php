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

class block_lp_result_get {

    /**
     *   Get the results in the database. 1 line per user and per competence.
     */
    public function get_lp_result($ctid) {
        global $DB;
        $sql = "SELECT {competency_usercomp}.id as cuid,
        {competency_template}.id as 'planid',
        {user}.idnumber as 'idnumber',
        {user}.firstname as 'firstname',
        {user}.lastname as 'lastname',
        {user}.department as 'codeetape',
        {competency_template}.shortname as 'planname',
        {competency}.shortname as 'competence',
        {competency}.idnumber as 'competence_id',
        {competency_usercomp}.grade as 'grade',
        {user}.id as 'userid',
        {competency_framework}.scaleconfiguration as 'scaleconfiguration'
        from {competency_plan}
        inner join {competency_template} on {competency_plan}.templateid = {competency_template}.id
        inner join {competency_usercomp} on {competency_plan}.userid = {competency_usercomp}.userid
        inner join {user} on {competency_plan}.userid = {user}.id
        inner join {competency} on {competency_usercomp}.competencyid = {competency}.id
        inner join {competency_framework} on {competency_framework}.id = {competency}.competencyframeworkid
        where {competency_template}.id = ?
        order by {user}.idnumber, {competency}.shortname asc";
        $result = $DB->get_records_sql($sql, array($ctid));
        return $result;
    }

    /**
     * Number of colums depends on how many competencies are in the learning plan.
     * Get all columns titles
     */
    public function get_fields($result, $fields) {
        foreach ($result as $line) {
            $temp = str_replace('-', '_', $line->competence_id);
            $competenceid = str_replace('.', '', $temp);
            $fields[$competenceid] = $line->competence_id;
        }
        return $fields;
    }

     /**
      * Transform the results in a table with one line per user with all his competencies
      */
    public function get_lp_result_per_user($result, $fields) {
        foreach ($result as $line) {
            // Define object.
            if (!isset($iterator[$line->userid])) {
                $iterator[$line->userid] = $this->init_iterator($fields);
            }

            // Fill arrays.
            if ($iterator[$line->userid]->lastname == '') {
                $iterator[$line->userid]->lastname = $line->lastname;
            }
            if ($iterator[$line->userid]->firstname == '') {
                $iterator[$line->userid]->firstname = $line->firstname;
            }
            if ($iterator[$line->userid]->idnumber == '') {
                $iterator[$line->userid]->idnumber = $line->idnumber;
            }
            if ($iterator[$line->userid]->codeetape == '') {
                $iterator[$line->userid]->codeetape = $line->codeetape;
            }
            if ($iterator[$line->userid]->planname == '') {
                $iterator[$line->userid]->planname = $line->planname;
            }
            $scaletable = explode("},",$line->scaleconfiguration);
            $scaleconfiguration = json_decode($scaletable[1]."}");
            $scaleid = $scaleconfiguration->id;
            if ($iterator[$line->userid]->scaleid == '') {
                $iterator[$line->userid]->scaleid = $scaleid;
            }
            $temp = str_replace('-', '_', $line->competence_id);
            $competenceid = str_replace('.', '', $temp);
            if ($iterator[$line->userid]->$competenceid == '') {
                $iterator[$line->userid]->$competenceid = $line->grade;
            }
        }
        return $iterator;
    }

     /**
      * Create an empty object with the name of the table columns
      */
    protected function init_iterator($fields) {

        // Create empty object $iterator with all properties.
        $object = new StdClass();
        foreach ($fields as $key => $value) {
            if (!isset($object->$key)) {
                $object->$key = '';
            }
        }
        return $object;
    }
}