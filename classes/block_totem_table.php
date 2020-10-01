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

class block_totem_table implements renderable, templatable {
    private $data = [];
    
    /**
     * Set the initial properties for the block
     * 
     */
    public function __construct($params) {
        $this->data = $params;
        $this->set_date($params['date']);
    }
    
    public function get_id() {
        return $this->data['blockid'];
    }
    
    public function set_date($date) {
        $d = new DateTime();
        $d->setTimestamp($date);
        
        $d->setTime(0,0);
        $this->data['date'] = $d->getTimestamp();
        $this->data['date_text'] = date('l d F Y', $date);
        return $this->data['date'];
    }
    
    public function get_records() {
        global $DB;
        $return = array();
        $sql = '';
        $params = array();
        $rs = null;
        $sql = "SELECT te.id, te.eventtype, u.idnumber, te.subject, te.section, te.time, te.displaytext
            FROM mdl_block_totem_event te
            LEFT JOIN mdl_user u ON te.userid = u.id
            WHERE te.blockid = :blockid AND te.date = :date
            ORDER BY te.date, te.time, u.idnumber";
        
        $params['blockid'] = $this->data['blockid'];
        $params['date'] = $this->data['date'];
        
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            $return[] = array(
                'eventtype' => $record->eventtype,
                'idnumber' => $record->idnumber,
                'subject' => $record->subject,
                'section' => $record->section,
                'time' => $record->time,
                'displaytext' => $record->displaytext
            );
        }
        $this->data['recordcount'] = count($return);
        $this->data['records'] = $return;

        return $data;
    }
    
    /**
     * Export this data so it can be used as the context for a mustache template
     * 
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $this->get_records();
        return $this->data;
    }
}