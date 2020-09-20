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
$date = optional_param('date', now(), PARAM_INT);

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

echo html_writer::start_tag('div');

$url = new moodle_url('/blocks/totem/event.php', array('blockid' => $blockid, 'cohortsourceid' => $block->config->cohortsourceid));
echo '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('addtotemelement', 'block_totem').'</button>
      </form></p>';
echo html_writer::end_tag('div');


echo html_writer::start_tag('div', array('class' => 'totem-table'));
echo html_writer::start_tag('table', array('class' => 'generaltable', 'width' => '100%'));
//echo html_writer::start_tag('thead');
//echo html_writer::start_tag('tr');
//echo html_writer::start_tag('th', array('class' => 'header c0')).''.html_writer::end_tag('th');
//echo html_writer::start_tag('th', array('class' => 'header c1')).'Teacher'.html_writer::end_tag('th');
//echo html_writer::start_tag('th', array('class' => 'header c2')).'Message'.html_writer::end_tag('th');
//echo html_writer::end_tag('tr');
//echo html_writer::end_tag('thead');
$sql = "SELECT te.id, te.eventtype, u.idnumber, te.subject, te.section, te.time, te.displaytext
        FROM mdl_block_totem_event te
        LEFT JOIN mdl_user u ON te.userid = u.id
        WHERE te.blockid = :blockid
        ORDER BY te.date, te.time, u.idnumber";
if ($rs = $DB->get_records_sql($sql, array('blockid' => $blockid))) {
    echo html_writer::start_tag('tbody');
    foreach ($rs as $record) {
        echo html_writer::start_tag('tr');
        echo html_writer::start_tag('td', array('class' => 'cell c0', 'width' => '50rem')).$record->eventtype.html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('class' => 'cell c1')).$record->idnumber.html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('class' => 'cell c2', 'width' => '100rem')).$record->subject.html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('class' => 'cell c3', 'width' => '100rem')).$record->section.html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('class' => 'cell c4', 'width' => '100rem')).$record->time.html_writer::end_tag('td');
        echo html_writer::start_tag('td', array('class' => 'cell c5')).$record->displaytext.html_writer::end_tag('td');
        if (TRUE) {
            $url = new moodle_url('/blocks/totem/event.php', array('id' => $record->id, 'blockid' => $blockid, 'cohortsourceid' => $block->config->cohortsourceid));
            echo html_writer::start_tag('td', array('class' => 'cell c5', 'width' => '50rem'));
            echo html_writer::link($url, '[[E]]');
            echo html_writer::end_tag('td');
        }
        echo html_writer::end_tag('tr');
    }
    echo html_writer::end_tag('tbody');
}
echo html_writer::end_tag('table');
echo html_writer::end_tag('div');

echo $OUTPUT->footer();