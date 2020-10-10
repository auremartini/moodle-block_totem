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

class block_totem_renderer extends plugin_renderer_base {
    
    /**
     * Defer to template
     * 
     * @param block_totem_element $totem
     * @return string|boolean
     */
    public function render($totem) {
        return parent::render_from_template('block_totem/totem_table', $totem->export_for_template($this));
    }
    
    public function render_fullscreen($totem) {
        return parent::render_from_template('block_totem/totem_table_fullscreen', $totem->export_for_template($this));
    }
    
    public function open_totem($totem) {
        $footer = null;
        $url = new moodle_url('/blocks/totem/view.php', array('blockid' => $totem->get_id()));
        $footer = '<div style="text-align:right"><form method="post" action="'.$url.'">
                   <button type="submit" class="btn btn-secondary" title="">'.get_string('opentotempage', 'block_totem').'</button>
                   </form></div>';
        
        return $footer;
    }   
}