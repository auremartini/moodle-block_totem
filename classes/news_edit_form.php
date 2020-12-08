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

class news_edit_form extends \moodleform {
    
    function definition() {
        $mform =& $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'blockid');
        
        $NEWS_TYPES = array(
            '-' => get_string('white', 'block_totem'),
            'R' => get_string('red', 'block_totem'),
            'B' => get_string('blue', 'block_totem'),
            'R' => get_string('green', 'block_totem'),
            'Y' => get_string('yellow', 'block_totem')
        );
        
        $mform->addElement('header', 'generalhdr', get_string('general'));

        // add type element
        $mform->addElement('select', 'eventtype', get_string('eventtype', 'block_totem'), $NEWS_TYPES);
        $mform->setType('eventtype', PARAM_TEXT);
        
        // add date from element
        $mform->addElement('date_time_selector', 'date_from', get_string('displayfrom', 'block_totem'));
        $mform->setType('date_from', PARAM_INT);

        // add date to element
        $mform->addElement('date_time_selector', 'date_to', get_string('displayto', 'block_totem'));
        $mform->setType('date_to', PARAM_INT);
        
        // add message element
        $mform->addElement('htmleditor', 'displaytext', get_string('displaytext', 'block_totem'), array('size'=>'255'));
                           
        $this->add_action_buttons();
    }
}