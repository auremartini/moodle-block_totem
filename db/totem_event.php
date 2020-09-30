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

require_once(__DIR__ . '/../../../config.php');

global $DB;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//GET PARAMETERS
$cohortid = optional_param('cohortid', null, PARAM_INT);

//SET DEFAULTS
$json = new stdClass();

$sql = '';
$params = array();
$rs = null;
//$sql = 'SELECT u.id, u.firstname, u.lastname FROM mdl_user u
//        LEFT JOIN mdl_cohort_members cm ON u.id = cm.userid';
//if ($cohortid) {
//    $sql .= ' WHERE cm.cohortid = :cohortid';
//    $params['cohortid'] = $cohortid;
//    $json->filter = $params;
//}
//$sql .= ' ORDER BY u.lastname, u.firstname';

//$rs = $DB->get_records_sql($sql, $params);
//foreach ($rs as $record) {
//    $json->data[] = [
//        'userid' => $record->id,
//        'lastname' => $record->lastname,
//        'firstname' => $record->firstname
//    ];
//}
//$json->recordcount = count($json->data);
echo json_encode($json);