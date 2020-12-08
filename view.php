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
require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$date = optional_param('date', 0, PARAM_INT);

// LOAD AND HANDRE TOOLBAR FORM EVENT
$toolbar = new \block_totem\classes\datepicker_form();
if($toolbar->get_data()) $date = $toolbar->get_data()->date_search;
$toolbar->set_data(array('blockid' => $blockid, 'date_search' => $date));

// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/blocks/totem/view.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$url = new moodle_url('/blocks/totem/view.php', array('blockid' => $blockid));
$node = $settingsnode->add($block->get_title(), $url);
$node->make_active();

// SET MENU
$menu = '';

$url = new moodle_url('/blocks/totem/event.php', array('blockid' => $blockid));
$menu .= '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('addtotemelement', 'block_totem').'</button>
      </form></p>';

$url = new moodle_url('/blocks/totem/fullscreen.php', array('blockid' => $blockid));
$menu .= '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('fullscreen', 'block_totem').'</button>
      </form></p>';

$url = new moodle_url('/blocks/totem/config.php', array('blockid' => $blockid, 'date' => $date));
$menu .= '<p><form method="post" action="'.$url.'">
      <button type="submit" class="btn btn-secondary" title="">'.get_string('config', 'block_totem').'</button>
      </form></p>';

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();

// ADD MENU
$menu = array();
$menu[] = array(
    'id' => 'totem_block_dropmenuitem_addevent',
    'icon' => 'fa-calendar-plus-o',
    'url' => new moodle_url('/blocks/totem/event.php', array('blockid' => $blockid)),
    'date' => $date,
    'title' => get_string('addtotemevent', 'block_totem')
);
$menu[] = array(
    'id' => 'totem_block_dropmenuitem_addnews',
    'icon' => 'fa-newspaper-o',
    'url' => new moodle_url('/blocks/totem/news.php', array('blockid' => $blockid)),
    'date' => $date,
    'title' => get_string('addtotemnews', 'block_totem')
);
$menu[] = array(
    'id' => 'totem_block_dropmenuitem_fullscreen',
    'icon' => 'fa-window-maximize',
    'url' => new moodle_url('/blocks/totem/fullscreen.php', array('blockid' => $blockid)),
    'title' => get_string('fullscreen', 'block_totem')
);
$menu[] = array(
    'id' => 'totem_block_dropmenuitem_config',
    'icon' => 'fa-cog',
    'url' => new moodle_url('/blocks/totem/config.php', array('blockid' => $blockid)),
    'title' => get_string('config', 'block_totem')
);
if (count($menu) > 0) {
    echo $PAGE->get_renderer('block_totem')->renderGearMenu(array('records' => $menu));
}

// ADD DATEPICKER
$toolbar->display();

// ADD TOTEM TABLE
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
            'edit' => TRUE,
            'copy' => TRUE,
            'delete' => TRUE,
            'lang_edit_totemelement' => get_string('edittotemelement', 'block_totem'),
            'lang_copy_totemelement' => get_string('copytotemelement', 'block_totem'),
            'lang_delete_totemelement' => get_string('deletetotemelement', 'block_totem')
        ]));
        $i++;
    }
    $d->modify('+1 day');
}
$PAGE->requires->js_call_amd('block_totem/delete_confirm', 'init', array());
echo $OUTPUT->footer();