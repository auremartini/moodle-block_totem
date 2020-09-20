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
require_once(__DIR__ . '/renderer/event.php');

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

//JAVASCRIPT CODE

$jscode =  'window.onload = function () {
                hideSlides();
                showSlide(0);
            }

            var cicles = 0;

            function hideSlides() {
                var slides = document.getElementById("totem-fullscreen").children;
                for (var i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
            }
            
            function showSlide(slideIndex) {
                var speed = 5000; //change slide every 5 seconds 
                var slides = document.getElementById("totem-fullscreen").children;
                //show slide
                if (slideIndex >= slides.length) {
                    slideIndex = 0;
                    cicles++;
                }
                if (cicles == 10) { location.reload(); }
                slides[slideIndex].style.display = "block";
                //hide previous
                if (slideIndex == 0) {
                    slides[slides.length-1].style.display = "none";
                } else {
                   slides[slideIndex-1].style.display = "none";
                }
                setTimeout(showSlide, speed, slideIndex+1);
            }
            ';

// PRINT CONTENT TO PAGE
echo $OUTPUT->header();

$d = new DateTime();
if ($date) $d->setTimestamp($date);
$d->setTime(0,0);
$i = 0;
echo html_writer::start_tag('div', array('id' => 'totem-fullscreen','class' => 'totem-fullscreen'));
while ($i < $block->config->pagedays) {
    if ($block->config->pageskipweekend == 0 || intval($d->format('N')) <= 5) {
        echo event_render_table($blockid, $d->getTimestamp(), 0);
        $i++;
    }
    $d->modify('+1 day');
}
echo html_writer::end_tag('div');
echo html_writer::script($jscode);

echo $OUTPUT->footer();