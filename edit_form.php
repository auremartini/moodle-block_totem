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

        // Totem block title
        $mform->addElement('text', 'config_title', get_string('configtitledesc', 'block_totem'));
        $mform->setType('config_title', PARAM_TEXT);
        
        // Teachers list
        $SOURCE = array();
        $SOURCE[0] = get_string('roles', 'moodle');
        $SOURCE[1] = get_string('cohorts', 'core_cohort');
        
        $sql = 'SELECT id, idnumber FROM mdl_cohort c
                ORDER BY c.idnumber';
        $rs = $DB->get_records_sql($sql);
        $COHORTS = array();
        foreach ($rs as $record) {
            $COHORTS[$record->id] = $record->idnumber;
        }
        
        $sql = 'SELECT r.id, r.shortname FROM mdl_role r
                LEFT JOIN mdl_role_context_levels cl ON r.id = cl.roleid
                WHERE cl.contextlevel = 10 
                ORDER BY r.shortname';
        $rs = $DB->get_records_sql($sql);
        $ROLES = array();
        foreach ($rs as $record) {
            $ROLES[$record->id] = $record->shortname;
        }
        
        $a=array();
        $a[] =& $mform->createElement('select', 'config_source', '', $SOURCE);
        $a[] =& $mform->createElement('select', 'config_sourceroleid', '', $ROLES);
        $a[] =& $mform->createElement('select', 'config_sourcecohortid', '', $COHORTS);
        $mform->addGroup($a, 'teachersdays', get_string('configsourcedesc', 'block_totem'), '', FALSE);
        $mform->setType('config_source', PARAM_INT);
        $mform->setType('config_sourceroleid', PARAM_INT);
        $mform->setType('config_sourcecohortid', PARAM_INT);
        $mform->hideIf('config_sourceroleid', 'config_source', 'neq', '0');
        $mform->hideIf('config_sourcecohortid', 'config_source', 'neq', '1');
        
        // Teaching groups list
        $mform->addElement('autocomplete', 'config_teachings', get_string('configteachingdesc', 'block_totem'), $COHORTS, array(
            'size'=>'20',
            'multiple' => TRUE
        ));
        
        // Block settings
        $a=array();
        $a[] =& $mform->createElement('text', 'config_blockdays', '', array('size'=>'3'));
        $a[] =& $mform->createElement('advcheckbox', 'config_blockskipweekend', '', get_string('configskipweekend', 'block_totem', '', array(0, 1)));
        $mform->addGroup($a, 'blockdays', get_string('configblockdaysdesc', 'block_totem'), '', FALSE);
        $mform->setType('config_blockdays', PARAM_INT);
        $mform->setType('config_blockskipweekend', PARAM_BOOL);
        $mform->setDefault('config_blockdays', 1);
        
        // Page settings
        $a=array();
        $a[] =& $mform->createElement('text', 'config_pagedays', '', array('size'=>'3'));
        $a[] =& $mform->createElement('advcheckbox', 'config_pageskipweekend', '', get_string('configskipweekend', 'block_totem', '', array(0, 1)));
        $mform->addGroup($a, 'pagedays', get_string('configpagedaysdesc', 'block_totem'), '', FALSE);
        $mform->setType('config_pagedays', PARAM_INT);
        $mform->setType('config_pageskipweekend', PARAM_BOOL);
        $mform->setDefault('config_pagedays', 5);
        
        // Fullscreen settings
        $a=array();
        $a[] =& $mform->createElement('text', 'config_fullscreendays', '', array('size'=>'3'));
        $a[] =& $mform->createElement('advcheckbox', 'config_fullscreenskipweekend', '', get_string('configskipweekend', 'block_totem', '', array(0, 1)));
        $mform->addGroup($a, 'fullscreendays', get_string('configfullscreendaysdesc', 'block_totem'), '', FALSE);
        $mform->setType('config_fullscreendays', PARAM_INT);
        $mform->setType('config_fullscreenskipweekend', PARAM_BOOL);
        $mform->setDefault('config_fullscreendays', 3);

        // Fullscreen settings
        $mform->addElement('textarea', 'config_eventtypelist', get_string('configeventtypelist', 'block_totem'), array('cols'=>'50', rows=>'4'));
        $mform->setDefault('config_eventtypelist', get_string('configeventtypelistdefault', 'block_totem'));
    }

    function set_data($defaults) {
        parent::set_data($defaults);
    }
}
