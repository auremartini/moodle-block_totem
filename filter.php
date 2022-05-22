<?php

use block_totem\classes\datepicker_form;

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
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/classes/datepicker_form.php');
require_once(__DIR__ . '/output/renderer.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
//require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);

$date = intval(optional_param('date', '', PARAM_TEXT));
$date_to = intval(optional_param('date_to', '', PARAM_TEXT));
$eventtype = optional_param('eventtype', '', PARAM_TEXT);
$teacher = optional_param('teacher', '', PARAM_TEXT);
$teaching = optional_param('teaching', '', PARAM_TEXT);
$classsection = optional_param('classsection', '', PARAM_TEXT);

if ($date_to == 0) {
    $d = new DateTime();
    $d->setTimestamp(($date == 0 ? time() : $date));
    $d->setTime(0,0);
    $date = $d->getTimestamp()- 1*24*60*60*30;
    $date_to = $d->getTimestamp() + 1*24*60*60*31;
}

// START PAGE
$PAGE->set_context(\context_system::instance());

// LOAD AND HANDLE TOOLBAR FORM EVENT
//$toolbar = new \block_totem\classes\datepicker_form();
//if($toolbar->get_data()) $date = $toolbar->get_data()->date_search;
//$toolbar->set_data(array('blockid' => $blockid, 'date_search' => $date));

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/blocks/totem/filter.php'));
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());

$url = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
$node = $PAGE->settingsnav->add($block->get_title(), $url);
$node->make_active();
$url = new moodle_url('/blocks/totem/filter.php', array('blockid' => $blockid));
$editnode = $node->add(get_string('filterevents', 'block_totem'), $url);
$editnode->make_active();

// PRINT CONTENT TO PAGE
$context = context_block::instance($blockid);
echo $OUTPUT->header();

//FILTER PAGE
$totem = new \block_totem\data\totemtable([
    'blockid' => $block->instance->id,
    'url'=>'/blocks/totem/filter.php',
    'date' => $date,
    'date_to' => $date_to,
    'teacher' => $teacher,
    'teaching' => $teaching,
    'classsection' => $classsection,
    'showHidden' => has_capability('block/totem:editevent', $context),
    'addbtn' => has_capability('block/totem:addevent', $context),
    'showbtn' => has_capability('block/totem:editevent', $context),
    'editbtn' => has_capability('block/totem:editevent', $context),
    'copybtn' => has_capability('block/totem:addevent', $context),
    'delete' => has_capability('block/totem:deleteevent', $context)
]);
        
echo $PAGE->get_renderer('block_totem')->render_list($totem);

$PAGE->requires->js_call_amd('block_totem/delete_confirm', 'init', array());
echo $OUTPUT->footer();
