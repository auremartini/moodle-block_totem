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

namespace block_totem\data;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");
//require_once("$CFG->dirrot/webservice/externallib.php");

class userlist extends \external_api {
    public static function get_userlist_parameters() {
        return new \external_function_parameters(array(
            'source' => new \external_value(PARAM_INT, 'Filter type (0: Roles, 1: Cohorts)', PARAM_REQUIRED),
            'sourceid' => new \external_value(PARAM_INT, 'Filter ID', PARAM_REQUIRED)
        ));
    }
    
    public static function get_userlist_is_allowed_from_ajax() {
        return true;
    }
    
    public static function get_userlist($source, $sourceid) {
        global $DB;
        $return = array();
        $sql = '';
        $params = array();
        $rs = null;
        if ($source == 0) {
            $sql = "SELECT u.id, u.firstname, u.lastname
                    FROM mdl_role_assignments r
                    LEFT JOIN mdl_user u ON r.userid = u.id
                    WHERE r.roleid = :sourceid AND r.contextid = 1
                    ORDER BY u.lastname, u.firstname";
        } else {
            $sql = "SELECT u.id, u.firstname, u.lastname
                    FROM mdl_cohort_members c
                    LEFT JOIN mdl_user u ON c.userid = u.id
                    WHERE c.cohortid = :sourceid
                    ORDER BY u.lastname, u.firstname";
        }
        
        $params['sourceid'] = intval($sourceid);
        
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            $return[] = array(
                'id' => $record->id,
                'firstname' => $record->firstname,
                'lastname' => $record->lastname
            );
        }
        
        return $return;
    }
    
    public static function get_userlist_returns() {
        return new \external_multiple_structure(new \external_single_structure(array(
            'id' => new \external_value(PARAM_TEXT, 'User ID', PARAM_REQUIRED),
            'firstname' => new \external_value(PARAM_TEXT, 'User firstname', PARAM_REQUIRED),
            'lastname' => new \external_value(PARAM_TEXT, 'User lastname', PARAM_REQUIRED)
        )));
    }
}