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
 * @since     Moodle 3.6
 * @package   block_totem
 * @copyright 2020 Aureliano Martini
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/classes/totemtable.php');
require_once(__DIR__ . '/output/renderer.php');

class block_totem extends block_base {

    /**
     * Core function used to initialize the block.
     */
    function init() {
        $this->blockname = get_class($this);
        $this->title = get_string('pluginname', $this->blockname);
    }
 
    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    function has_sconfig() {
        return true;
    }

    /**
     * Core function, specifies where the block can be used.
     * @return array
     */
//    public function applicable_formats() {
//        return array('course-view' => true, 'mod' => true);
//    }
    
    /**
     * Allow the block instance to have a configuration page
     * @return array
     */
    function instance_allow_config() {
        return true;
    }
    
    /**
     * Allows the block to be added multiple times to a single page
     * @return boolean
     */
    function instance_allow_multiple() {
        return true;
    }
    
    /**
     * Allows the block to be added multiple times to a single page
     * @return boolean
     */
    function specialization() {
        // After the block has been loaded we customize the block's title display
        if (!empty($this->config) && !empty($this->config->title)) {
            // There is a customized block title, display it
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            // No customized block title, use localized remote news feed string
            $this->title = get_string('plugintitle', 'block_totem');
        }
    }
    
    /**
     * Cleanup of all data associated with the block on deletion
     */
    public function instance_delete() {
//        global $DB;
//        $DB->delete_records('block_simplehtml', array('blockid' => $this->instance->id));
    }
    
    /**
     * Used to generate the content for the block.
     * @return string
     */
    function get_content() {
        global $CFG, $DB, $PAGE;
        
        // First check if we have already generated, don't waste cycles
        if ($this->content !== NULL) {
            return $this->content;
        }
        
        // initalise block content object
        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';
        
        if (empty($this->instance)) {
            return $this->content;
        }
        
        if (!isset($this->config)) {
            // The block has yet to be configured - just display configure message in
            // the block if user has permission to configure it
            
            if (has_capability('block/totem:manageanyfeeds', $this->context)) {
                $this->content->text = get_string('configurenewinstance', 'block_totem');
            }
            
            return $this->content;
        }

        $d = new DateTime();
        $d->setTime(0,0);
        $i = 0;
        while ($i < $this->config->blockdays) {
            $collapsible = ($this->config->blockdays == 1 ? FALSE : TRUE);
            $collapsed = ($i==0 ? FALSE : TRUE);
            if ($this->config->blockskipweekend == 0 || intval($d->format('N')) <= 5) {
                // initalise new totem element
                $this->content->text .= $PAGE->get_renderer('block_totem')->render(new \block_totem\data\totemtable([
                    'blockid' => $this->instance->id,
                    'date' => $d->getTimestamp(),
                    'collapsible' => $collapsible,
                    'collapsed' => $collapsed,
                    'showDate' => TRUE
                ]));
                $i++;
            }
            $d->modify('+1 day');
            
        }
        
        $this->content->footer = $PAGE->get_renderer('block_totem')->open_totem($this->instance->id);
        
        return $this->content;
    }
}
