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

require_login($course);


$blockname = 'totem';
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$id = optional_param('id', 0, PARAM_INT);

// if (!$blockid = $DB->get_record('block_instances', array('id' => $blockid))) {
//     print_error('invalidblock', 'blocks_totem', $block);
// }



$PAGE->set_url(new moodle_url('/blocks/totem/view.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title()); //@todo Change to block title

$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$editurl = new moodle_url('/blocks/totem/view.php', array('id' => $id, 'blockid' => $blockid));
$editnode = $settingsnode->add($block->get_title(), $editurl);
$editnode->make_active();

echo $OUTPUT->header();

echo "Prova!!!<br>";
echo "BlockID = ".$blockid."<br><br><br>";

echo $OUTPUT->footer();