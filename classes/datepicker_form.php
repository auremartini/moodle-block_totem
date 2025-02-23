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

class datepicker_form extends \moodleform {
    
    function definition() {
        $mform =& $this->_form;
        $mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_INT);
        
        $a=array();
        $a[] =& $mform->createElement('date_selector', 'date_search', '');
        $a[] =& $mform->createElement('submit', 'submitbutton', get_string('gotodate', 'block_totem'));
        $mform->addGroup($a, 'gotodate', '', '', FALSE);
    }
}