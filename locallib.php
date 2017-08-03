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

class block_lp_result_get {
    function get_lp_result($ctid) {
        global $DB;
        $sql = "SELECT cu.id, ct.id as 'planid', u.idnumber as 'numero_etu', u.firstname as 'prenom', u.lastname as 'nom', u.department as 'codeetape', ct.shortname as 'plan_nom',
        c.shortname as 'competence', c.idnumber as 'competence_id',
        cu.grade as 'note',u.id as 'userid', cf.scaleid as 'scaleid'
        from {competency_plan} as cp
        join {competency_template} as ct on cp.templateid = ct.id
        join {competency_usercomp} as cu on cp.userid = cu.userid
        join {user} as u on cp.userid = u.id
        join {competency} as c on cu.competencyid = c.id
        join {competency_framework} as cf on cf.id = c.competencyframeworkid
        where ct.id = ? order by u.idnumber, c.shortname asc";
        $result = $DB->get_records_sql($sql, array($ctid));
        return $result;
    }

    function get_fields($result, $fields) {
        foreach ($result as $line) {
            $temp = str_replace('-', '_', $line->competence_id);
            $competence_id = str_replace('.', '', $temp);
            $fields[$competence_id] = $line->competence_id;
        }
        return $fields;
    }

    function get_lp_result_per_user($result, $fields) {
        foreach ($result as $line) {
        // Define object.
        if (!isset($iterator[$line->userid])) $iterator[$line->userid] = new stdClass();

            //create empty object $iterator with all properties
            foreach ($fields as $key => $value) {
                if (!isset($iterator[$line->userid]->$key)) {
                $iterator[$line->userid]->$key = '';
                }
            }

            // Fill arrays.
            if ($iterator[$line->userid]->lastname == '') {
                $iterator[$line->userid]->lastname = $line->nom;
            }
            if ($iterator[$line->userid]->firstname == '')  {
                $iterator[$line->userid]->firstname = $line->prenom;
            }
            if ($iterator[$line->userid]->idnumber == '') {
                $iterator[$line->userid]->idnumber = $line->numero_etu;
            }
            if ($iterator[$line->userid]->codeetape == '') {
                $iterator[$line->userid]->codeetape = $line->codeetape;
            }
            if ($iterator[$line->userid]->planname == '') {
                $iterator[$line->userid]->planname = $line->plan_nom;
            }
            if ($iterator[$line->userid]->scaleid == '') {
                $iterator[$line->userid]->scaleid = $line->scaleid;
            }
            $temp = str_replace('-', '_', $line->competence_id);
            $competence_id = str_replace('.', '', $temp);
            if ($iterator[$line->userid]->$competence_id == '') {
                $iterator[$line->userid]->$competence_id = $line->note;
            }
        }
        return $iterator;
    }
}