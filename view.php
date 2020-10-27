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

$url = new moodle_url('/blocks/totem/event.php', array('blockid' => $blockid));
echo '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('addtotemelement', 'block_totem').'</button>
      </form></p>';
$url = new moodle_url('/blocks/totem/fullscreen.php', array('blockid' => $blockid));
echo '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('fullscreen', 'block_totem').'</button>
      </form></p>';

$d = new DateTime();
if ($date) $d->setTimestamp($date);
$d->setTime(0,0);
$i = 0;
while ($i < $block->config->pagedays) {
    $collapsible = ($block->config->pagedays == 1 ? FALSE : TRUE);
    $collapsed = ($i==0 ? FALSE : TRUE);
    if ($block->config->pageskipweekend == 0 || intval($d->format('N')) <= 5) {
        // initalise new totem element
        echo $PAGE->get_renderer('block_totem')->render(new \block_totem\data\totemtable([
            'blockid' => $block->instance->id,
            'date' => $d->getTimestamp(),
            'collapsible' => $collapsible,
            'collapsed' => $collapsed,
            'showDate' => TRUE,
            'edit' => TRUE
        ]));
        $i++;
    }
    $d->modify('+1 day');
    
}

echo $OUTPUT->footer();