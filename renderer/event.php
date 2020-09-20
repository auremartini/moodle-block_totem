<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

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
 
function event_render_table($blockid, $date, $show = 1, $cohortsourceid = NULL) {
    global $DB;
    $return = "";
    $edit = false;

    if (! $blockid > 0) return "";
    if (! $date > 0) {
        $d = new DateTime();
        $d->setTime(0,0);
        $date = $d->getTimestamp();
    }
    if ($cohortsourceid) $edit = true;

    $return .= html_writer::start_tag('div', array('id' => 'totem-event'.$date,'class' => 'totem-table'));
    $return .= html_writer::start_tag('h3', array('id' => 'totem-title-'.$date));
    if ($show != 0) {
        $url = 'javascript:
                document.getElementById("totem-table-'.$date.'").classList.add("hidden");
                document.getElementById("totem-switchon-'.$date.'").classList.add("hidden");
                document.getElementById("totem-switchoff-'.$date.'").classList.remove("hidden");
                ';
        $return .= html_writer::link($url, '&#9662;&nbsp;', array('id' => 'totem-switchon-'.$date, 'class' => ($show == 1 ? '' : 'hidden')));
        $url = 'javascript:
                document.getElementById("totem-table-'.$date.'").classList.remove("hidden");
                document.getElementById("totem-switchon-'.$date.'").classList.remove("hidden");
                document.getElementById("totem-switchoff-'.$date.'").classList.add("hidden");
                ';
        $return .= html_writer::link($url, '&#9656;&nbsp;', array('id' => 'totem-switchoff-'.$date, 'class' => ($show == 1 ? 'hidden' : '')));
    }
    $return .= date('l d F Y', $date);
    $return .= html_writer::end_tag('h3');
    $return .=  html_writer::start_tag('table', array('id' => 'totem-table-'.$date, 'class' => 'generaltable'.($show == -1 ? ' hidden' : ''), 'width' => '100%'));
    $sql = "SELECT te.id, te.eventtype, u.idnumber, te.subject, te.section, te.time, te.displaytext
            FROM mdl_block_totem_event te
            LEFT JOIN mdl_user u ON te.userid = u.id
            WHERE te.blockid = :blockid AND te.date = :date
            ORDER BY te.date, te.time, u.idnumber";
    if ($rs = $DB->get_records_sql($sql, array('blockid' => $blockid, 'date' => $date))) {
        $return .= html_writer::start_tag('tbody');
        foreach ($rs as $record) {
            $return .= html_writer::start_tag('tr');
            $return .= html_writer::start_tag('td', array('class' => 'cell c0', 'width' => '40rem')).$record->eventtype.html_writer::end_tag('td');
            $return .= html_writer::start_tag('td', array('class' => 'cell c1', 'width' => '80rem')).$record->idnumber.html_writer::end_tag('td');
            $return .= html_writer::start_tag('td', array('class' => 'cell c2', 'width' => '80rem')).$record->subject.html_writer::end_tag('td');
            $return .= html_writer::start_tag('td', array('class' => 'cell c3', 'width' => '80rem')).$record->section.html_writer::end_tag('td');
            $return .= html_writer::start_tag('td', array('class' => 'cell c4', 'width' => '80rem')).$record->time.html_writer::end_tag('td');
            $return .= html_writer::start_tag('td', array('class' => 'cell c5')).$record->displaytext.html_writer::end_tag('td');
            if ($edit) {
                $url = new moodle_url('/blocks/totem/event.php', array('id' => $record->id, 'blockid' => $blockid, 'cohortsourceid' => $cohortsourceid));
                $return .= html_writer::start_tag('td', array('class' => 'cell c5', 'width' => '50rem'));
                $return .= html_writer::link($url, '[[E]]');
                $return .= html_writer::end_tag('td');
            }
            $return .= html_writer::end_tag('tr');
        }       
        $return .= html_writer::end_tag('tbody');
    }
    $return .= html_writer::end_tag('table');
    $return .= html_writer::end_tag('div');

    return $return;
}