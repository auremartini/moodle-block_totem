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
$PAGE->set_url(new moodle_url('/blocks/totem/view.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$url = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
$node = $settingsnode->add($block->get_title(), $url);
$node->make_active();

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();

echo '<table width="100%"><tr><td style="vertical-align: top; width:15rem;">';
echo $OUTPUT->box_start();
echo 'DATE PICKER';
echo $OUTPUT->box_end();

echo '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('slideshow', 'block_totem').'</button>
      </form></p>';

$url = new moodle_url('/blocks/totem/event.php', array('blockid' => $blockid, 'cohortsourceid' => $block->config->cohortsourceid));
echo '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('addtotemelement', 'block_totem').'</button>
      </form></p>';

echo '</td><td style="width:1.25rem;">&nbsp;</td><td style="vertical-align: top;">';
echo '<div class="totem-box">';

if ($rs = $DB->get_records('block_totem_event', array('blockid' => $blockid))) {
    echo html_writer::start_tag('ul');
    foreach ($rs as $record) {
        $pageurl = new moodle_url('/blocks/totem/event.php', array('id' => $record->id, 'blockid' => $blockid, 'cohortsourceid' => $block->config->cohortsourceid));
        echo html_writer::start_tag('li');
        echo html_writer::link($pageurl, '[['.$record->id.']]');
        echo html_writer::end_tag('li');
    }
    echo html_writer::end_tag('ul');
}

echo '</div>';
echo '</td></tr></table>';

echo $OUTPUT->footer();