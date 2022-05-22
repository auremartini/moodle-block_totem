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
require_once('classes/filter_selection.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$action = optional_param('action', '', PARAM_TEXT);
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$url = optional_param('url', '/blocks/totem/view.php', PARAM_TEXT);

// START PAGE
$PAGE->set_context(\context_system::instance());

// SET FORM
$form = new \block_totem\classes\filter_selection();

// HANDLE EVENTS
if($form->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url($url, array('blockid' => $blockid));
    redirect($courseurl);
    
} elseif ($action == '') {
    $record = $form->get_data();
    
    $courseurl = new moodle_url($url, array(
        'blockid' => $blockid,
        'url' => $url,
        'date' => $record->date,
        'date_to' => $record->date_to,
        'teacher' => $record->teacher,
        'teaching' => $record->teaching,
        'classsection' => $record->classsection
    ));
    redirect($courseurl);
} else {
    $date = optional_param('date', '', PARAM_INT);
    $date_to = optional_param('date_to', '', PARAM_INT);
    $teacher = optional_param('teacher', '', PARAM_TEXT);
    $teaching = optional_param('teaching', '', PARAM_TEXT);
    $classsection = optional_param('classsection', '', PARAM_TEXT);
}

$form->set_data(array(
    'blockid' => $blockid,
    'url' => $url,
    'date' => $date,
    'date_to' => $date_to,
    'teacher' => $teacher,
    'teaching' => $teaching,
    'classsection' => $classsection
));

// SET PAGE ELEMENTS (HEADER)
//$PAGE->requires->js_call_amd('block_totem/add_event_edit_form_dynamics', 'init', array([
//    'blockteachings' => $block->config->teachings,
//    'eventtypelist' => $block->config->eventtypelist,
//    'source' => $block->config->source,
//    'sourceid' => ($block->config->source == 0 ? $block->config->sourceroleid : $block->config->sourcecohortid)
//]));
$PAGE->set_url(new moodle_url('/blocks/totem/filter_selection.php'));
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$url = new moodle_url($url, array('blockid' => $blockid));
$node = $PAGE->settingsnav->add($block->get_title(), $url);
$node->make_active();
$editnode = $node->add(get_string('selectfilter', 'block_totem'));
$editnode->make_active();

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();