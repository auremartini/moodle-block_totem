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

namespace block_totem\classes;

require_once("{$CFG->libdir}/formslib.php");
//require_once("userlist.php");

class event_edit_form extends \moodleform {
    
    function definition() {
        $mform =& $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'userid');
        $mform->addElement('hidden', 'teaching');
        
        $EVENT_TYPES = array(
            '-' => '',
            'A' => get_string('absentteacher', 'block_totem'),
            'R' => get_string('reportedclass', 'block_totem')
        );
        
        $TEACHER_LIST = array('0' => '');
        
        $TEACHINGS = array();
        
        $mform->addElement('header', 'generalhdr', get_string('general'));

        // add type element
        $mform->addElement('select', 'eventtype', get_string('eventtype', 'block_totem'), $EVENT_TYPES);
        $mform->setType('eventtype', PARAM_TEXT);
        
        // add teacher element
        $mform->addElement('select', 'useridlist', get_string('teacher', 'block_totem'), $TEACHER_LIST);
        $mform->setType('useridlist', PARAM_INT);
        
        // add subject element
        $a=array();
        $a[] =& $mform->createElement('select', 'teachinglist', '', $TEACHINGS);
        $a[] =& $mform->createElement('text', 'subject', '', array('size'=>'10'));
        $mform->addGroup($a, 'teachingandsubject', get_string('teaching', 'block_totem'), '', FALSE);
        $mform->setType('teaching', PARAM_TEXT);
        $mform->setType('subject', PARAM_TEXT);
        
        // add time element
        $mform->addElement('text', 'section', get_string('classsection', 'block_totem'), array('size'=>'20'));
        $mform->setType('section', PARAM_TEXT);
        
        // add date element
        $mform->addElement('date_selector', 'date', get_string('displaydate', 'block_totem'));
        $mform->setType('date', PARAM_INT);
        
        // add time element
        $mform->addElement('text', 'time', get_string('displaytime', 'block_totem'), array('size'=>'20'));
        $mform->setType('time', PARAM_TEXT);
        
        // add message element
        $mform->addElement('text', 'displaytext', get_string('displaytext', 'block_totem'), array('size'=>'255'));
                           
        $this->add_action_buttons();
    }
    
/*    public function load_list($list, $params){
        switch ($list) {
            case 'userid': //GET FILTERED TEACHER LIST
                $rs = \block_totem\data\userlist::get_userlist($params['source'], $params['sourceid']);
                foreach ($rs as $record) {
                    $this->_form->getElement('userid')->_options[$record['id']] = array(
                        'text' => $record['lastname'].' '.$record['firstname'],
                        'attr' => array('value' => $record['id'])
                    );
                }
            default:
        }
    }*/
}