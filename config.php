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
 * Manage multiblock instances.
 *
 * @package   block_multiblock
 * @copyright 2019 Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../edit_form.php');
require_once(__DIR__ . '/edit_form.php');

global $DB, $OUTPUT, $PAGE;

require_login();

//LOAD PARAMS & OBJECTS
$blockname = 'totem';
$blockid = required_param('blockid', PARAM_INT);
$blockinstance = $DB->get_records('block_instances', array('id' => $blockid));
$block = block_instance($blockname, $blockinstance[$blockid]);
$date = optional_param('date', 0, PARAM_INT);

// START PAGE
$PAGE->set_context(\context_system::instance());


// SET PAGE ELEMENTS (HEADER)
$PAGE->set_url(new moodle_url('/blocks/totem/view.php'));
$PAGE->set_title($block->get_title());
$PAGE->set_heading($block->get_title());
$settingsnode = $PAGE->settingsnav->add(get_string('plugintitle', 'block_totem'));
$url = new moodle_url('/blocks/totem/view.php', ['blockid' => $blockid, 'date' => $date]);
$node = $settingsnode->add($block->get_title(), $url);
$node->make_active();

$actionurl = new moodle_url('/blocks/totem/config.php', ['blockid' => $blockid, 'date' => $date]);
$form = new block_totem_edit_form($actionurl, $block, $PAGE);

if ($form->is_cancelled()) {
    redirect($url);
} else if ($data = $form->get_data()) {
    $config = new stdClass;
    foreach ($data as $configfield => $value) {
        if (strpos($configfield, 'config_') !== 0) {
            continue;
        }
        $field = substr($configfield, 7);
        $config->$field = $value;
    }
    $block->instance_config_save($config);
    redirect($url);
}

echo $OUTPUT->header();

$form->set_data($blockinstance->instance);
$form->display();

echo $OUTPUT->footer();