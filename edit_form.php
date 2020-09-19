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
 * Form for editing HTML block instances.
 *
 * @package   block_html
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing HTML block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_totem_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG, $DB;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitledesc', 'block_totem'));
        $mform->setType('config_title', PARAM_TEXT);

        $sql = 'SELECT id, idnumber FROM mdl_cohort c
                ORDER BY c.idnumber';
        $rs = $DB->get_records_sql($sql);
        $COHORTS = array();
        foreach ($rs as $record) {
            $COHORTS[$record->id] = $record->idnumber;
        }
        $mform->addElement('select', 'config_cohortsourceid', get_string('configcohortsourcedesc', 'block_totem'), $COHORTS);
        $mform->setType('config_cohortsourceid', PARAM_NUMBER);
        
        $mform->addElement('text', 'config_blockdays', get_string('configblockdaysdesc', 'block_totem'));
        $mform->setType('config_blockdays', PARAM_NUMBER);

        $mform->addElement('text', 'config_pagedays', get_string('configpagedaysdesc', 'block_totem'));
        $mform->setType('config_pagedays', PARAM_NUMBER);
    }

    function set_data($defaults) {
        parent::set_data($defaults);
    }
}
