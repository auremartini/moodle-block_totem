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
 * Strings for component 'block_totem', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   block_totem
 * @copyright 2020 Aureliano Martini
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once(__DIR__ . '/../../config.php');
require_once('classes/event_edit_form.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$id = optional_param('id', 0, PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$cohortsourceid = required_param('cohortsourceid', PARAM_INT);

// SET FORM
$form = new \block_totem\classes\event_edit_form();
$form->load_list('userid', array('cohortsourceid' => $cohortsourceid));
$form->set_data(array(
    'id' => $id,
    'blockid' => $blockid,
    'cohortsourceid' => $cohortsourceid
));

// LOAD VALUES TO EDIT
if ($id) {
    $record = $DB->get_record('block_totem_event', array('id' => $id));
    $form->set_data($record);
}

// HANDLE EVENTS
if($form->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
    redirect($courseurl);
} else if ($record = $form->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
    if ($id != 0) {
        if (!$DB->update_record('block_totem_event', $record)) {
            print_error('updateeventerror', 'block_totem');
        }
    } else {
        if (!$DB->insert_record('block_totem_event', $record)) {
            print_error('inserteventerror', 'block_totem');
        }
    }
    $courseurl = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid, 'date' => $record->date));
    redirect($courseurl);
}

// SET PAGE ELEMENTS (HEADER)
//$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/totem/js/event_edit_form.js'));
$PAGE->requires->js_call_amd('block_totem/event_edit_form', 'init', array(['cohortid' => cohortsourceid]));
$PAGE->set_url(new moodle_url('/blocks/totem/event.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$url = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
$node = $settingsnode->add($block->get_title(), $url);
$node->make_active();
$url = new moodle_url('/blocks/totem/event.php', array('id' => $id, 'blockid' => $blockid, 'cohortsourceid' => $cohortsourceid));
$editnode = $node->add(get_string('addtotemelement', 'block_totem'), $url);
$editnode->make_active();

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();