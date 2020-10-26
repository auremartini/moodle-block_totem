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

$functions = array(
    'get_userlist' => array(
        'classname' => 'block_totem\data\userlist',
        'methodname' => 'get_userlist',
        'classpath' => 'blocks/totem/classes/userlist.php',
        'description' => '',
        'type' => 'read',
        'ajax' => true,
//      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => array()
    ),
    'get_teachinglist' => array(
        'classname' => 'block_totem\data\teachinglist',
        'methodname' => 'get_teachinglist',
        'classpath' => 'blocks/totem/classes/teachinglist.php',
        'description' => '',
        'type' => 'read',
        'ajax' => true,
        //      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => array()
    ),'get_totemtable' => array(
        'classname' => 'block_totem\data\totemtable',
        'methodname' => 'get_totemtable',
        'classpath' => 'blocks/totem/classes/totemtable.php',
        'description' => '',
        'type' => 'read',
        'ajax' => true,
        //      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'capabilities' => array()
    )
);