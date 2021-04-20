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
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/output/renderer.php');

global $DB, $OUTPUT, $PAGE;

//REQUIRE LOGIN TO SHOW THE CONTENT
require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$date = optional_param('date', 0, PARAM_INT);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/blocks/totem/fullscreen.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$url = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
$node = $settingsnode->add($block->get_title(), $url);
$node->make_active();
$url = new moodle_url('/blocks/totem/fullscreen.php', array('blockid' => $blockid, 'date' => $date));
$editnode = $node->add(get_string('fullscreen', 'block_totem'), $url);
$editnode->make_active();

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();

$d = new DateTime();
if ($date) $d->setTimestamp($date);
$d->setTime(0,0);
echo html_writer::start_tag('div', array('data-region' => "totem_fullscreen", 'class' => 'totem-fullscreen'));
echo html_writer::end_tag('div');

$PAGE->requires->js_call_amd('block_totem/add_totemfullscreen_dynamics', 'init', array([
    'blockid' => intval($block->instance->id),
    'date' => $d->getTimestamp(),
    'offset' => 0,
    'limit' => intval($block->config->fullscreendays),
    'skipweekend' => $block->config->fullscreenskipweekend
]));

echo $OUTPUT->footer();