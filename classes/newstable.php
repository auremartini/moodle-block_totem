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

namespace block_totem\data;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class newstable extends \external_api implements \renderable, \templatable {
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
        $d = new \DateTime();
        $d->setTimestamp($date);        
        $d->setTime(0,0);
        
        while ($this->data['skipweekend'] == 1 && intval($d->format('N')) > 5) {
            $d->modify('+1 day');
        }
        
        $this->data['date'] = $d->getTimestamp();
        $this->data['date_text'] = get_string('day-'.$d->format('N'), 'block_totem').' '.$d->format('j').' '.get_string('month-'.$d->format('n'), 'block_totem').' '.$d->format('Y');

        return $this->data['date'];
    }
    
    public function get_records($params = NULL) {
        global $DB;

        $blockname = 'totem';
        $blockinstance = $DB->get_records('block_instances', array('id' => $this->data['blockid']));
        $block = block_instance($blockname, $blockinstance[$this->data['blockid']]);
        $list = explode("\n", $block->config->eventtypelist);
        $eventtypelist = array();
        foreach ($list as $item) {
            $param = explode('|', $item);
            if (count($param)==3) {
                $eventtypelist[$param[0]] = $param[2];
            }
        }
        
        $return = array();
        $sql = '';
        $rs = null;
        $sql = "SELECT tn.id, tn.eventtype, tn.date_from, tn.date_to, tn.displaytext, tn.displayevent
            FROM mdl_block_totem_news tn
            WHERE (tn.date_from >= :date_from AND tn.date_to <= :date_to) AND (tn.displayevent = 1 OR tn.displayevent = :hidden)
            ORDER BY tn.date_from, tn.eventtype";

        if (!$params) {
            $params = array();
            $params['date_from'] = $this->data['date_from'];
            $params['date_to'] = $this->data['date_to'];
            $params['hidden'] = ($this->data['showHidden'] == TRUE ? 0 : 1);
        }
        
        $rs = $DB->get_records_sql($sql, $params);
        foreach ($rs as $record) {
            $displaydate = 'DATE';
            if (date('Y', $record->date_from) == date('Y', $record->date_from)) {
                if (date('m', $record->date_from) == date('m', $record->date_from)) {
                    if (date('m', $record->date_from) == date('m', $record->date_from)) {
                        $displaydate = date('d.m.Y', $record->date_from); //STESSO GIORNO
                    } else {
                        $displaydate = date('d.m', $record->date_from).' - '.date('d.m.Y', $record->date_to); //GIORNO DIFFERENTE
                    }
                } else {
                    $displaydate = date('d.m', $record->date_from).' - '.date('d.m.Y', $record->date_to); //MESE DIFFERENTE
                }
            } else {
                $displaydate = date('d.m.Y', $record->date_from) + ' - ' +  date('d.m.Y', $record->date_to); //ANNO DIFFERENTE
            }
            
            $return[] = array(
                'id' => $record->id,
                'eventtype' => $record->eventtype,
                'eventtypecss' => ($record->eventtype=='' ? '' : $eventtypelist[$record->eventtype]),
                'date_from' => $record->date_from,
                'date_to' => $record->date_to,
                'displayhidden' => ($record->displayevent == 1 ? FALSE : TRUE),
                'displaydate' => $displaydate,
                'displaytext' => $record->displaytext,
                'displayevent' => ($record->displayevent == 1 ? 'eye-slash' : 'eye')
            );
        }
        $this->data['recordcount'] = count($return);
        $this->data['records'] = $return;

        return $return;
    }
    
    /**
     * Export this data so it can be used as the context for a mustache template
     * 
     * @return \stdClass
     */
    public function export_for_template(\renderer_base $output = null) {
        $this->get_records();
        return $this->data;
    }

    public static function get_newstable_parameters() {
        return new \external_function_parameters(array(
            'blockid' => new \external_value(PARAM_INT, 'Totem block ID', PARAM_REQUIRED),
            'date' => new \external_value(PARAM_INT, 'Timetable start date', PARAM_REQUIRED),
            'offset' => new \external_value(PARAM_INT, 'Offset filter day', VALUE_OPTIONAL, 0),
            'skipweekend' => new \external_value(PARAM_INT, 'Skip weekend in offset', PARAM_OPTIONAL, 0)
        ));
    }
    
    public static function get_newstable_is_allowed_from_ajax() {
        return true;
    }
    
    public static function get_newstable($blockid, $date, $offset, $skipweekend) {
        
        //Calculate the day to show
        $d = new \DateTime();
        $d->setTimestamp($date);
        $d->setTime(0,0);
        $i=0;
        
        while($i<$offset || ($skipweekend == 1 && intval($d->format('N')) > 5)) {
            if ($skipweekend == 0 || intval($d->format('N')) <= 5) {
                $i++;
            }
            $d->modify('+1 day');
        }
        
        //get_records
        $totem = new totemtable(array(
            'blockid' => $blockid,
            'date' => $d->getTimestamp()
        ));
        
        return $totem->export_for_template();
    }
    
    public static function get_newstable_returns() {
        return new \external_single_structure(array(
            'blockid' => new \external_value(PARAM_TEXT, 'Totemtable block ID', PARAM_REQUIRED),
            'date' => new \external_value(PARAM_TEXT, 'Totemtable date', PARAM_REQUIRED),
            'date_text' => new \external_value(PARAM_TEXT, 'Totemtable date extended text', PARAM_REQUIRED),
            'recordcount' => new \external_value(PARAM_TEXT, 'Totemtable date extended text', PARAM_REQUIRED),
            'records' => new \external_multiple_structure(new \external_single_structure(array(
                'id' => new \external_value(PARAM_INT, 'Event ID', PARAM_REQUIRED),
                'eventtype' => new \external_value(PARAM_TEXT, 'Event type', PARAM_REQUIRED),
                'eventtypecss' => new \external_value(PARAM_TEXT, 'Event type css', PARAM_REQUIRED),
                'date_from' => new \external_value(PARAM_INT, 'Display from date', PARAM_REQUIRED),
                'date_to' => new \external_value(PARAM_INT, 'Display to date', PARAM_REQUIRED),
                'displayhidden' => new \external_value(PARAM_BOOL, 'Hidden', PARAM_REQUIRED),
                'displaydate' => new \external_value(PARAM_TEXT, 'Display date', PARAM_REQUIRED),
                'displaytext' => new \external_value(PARAM_TEXT, 'Display text', PARAM_REQUIRED),
                'displayevent' => new \external_value(PARAM_TEXT, 'Show to public', PARAM_REQUIRED)
            )))
        ));
    }
}