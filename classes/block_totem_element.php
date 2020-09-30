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

class block_totem_element implements renderable, templatable {
    private $data = [];
    
    /**
     * Set the initial properties for the block
     * 
     */
    public function __construct($params) {
        $this->data = $params;
        $this->set_date($params['date']);
    }
    
    public function set_date($date) {
        if ($date > 0) {
            $d = new DateTime();
            $d->setTime(0,0);
            $this->data['date'] = $d->getTimestamp();
            $this->data['date_text'] = date('l d F Y', $date);
        } else {
            $this->data['date'] = 0;
            $this->data['date_text'] = '';
        }
        return $this->data['date'];
    }
    /**
     * Export this data so it can be used as the context for a mustache template
     * 
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        return $this->data;
    }
}