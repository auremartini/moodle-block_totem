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
require_once(__DIR__ . '/classes/block_totem_element.php');
require_once(__DIR__ . '/output/renderer.php');

class block_totem extends block_base {

    /**
     * Set the initial properties for the block
     */
    function init() {
        $this->blockname = get_class($this);
        $this->title = get_string('pluginname', $this->blockname);
    }
 
    function has_sconfig() {
        return true;
    }

    function instance_allow_config() {
        return true;
    }
    
    /**
     * All multiple instances of this block
     * @return bool Returns false
     */
    function instance_allow_multiple() {
        return true;
    }
    
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
     * Gets the content for this block by grabbing it from $this->page
     *
     * @return object $this->content
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
        
        // How many feed items should we display?
//        $maxentries = 5;
//        if ( !empty($this->config->shownumentries) ) {
//            $maxentries = intval($this->config->shownumentries);
//        }elseif( isset($CFG->block_rss_client_num_entries) ) {
//            $maxentries = intval($CFG->block_rss_client_num_entries);
//        }
        
        /* ---------------------------------
         * Begin Normal Display of Block Content
         * --------------------------------- */
        
//        $renderer = $this->page->get_renderer('block_rss_client');
//        $block = new \block_rss_client\output\block();
        
//        if (!empty($this->config->rssid)) {
//            list($rssidssql, $params) = $DB->get_in_or_equal($this->config->rssid);
//            $rssfeeds = $DB->get_records_select('block_rss_client', "id $rssidssql", $params);
            
//            if (!empty($rssfeeds)) {
//                $showtitle = false;
//                if (count($rssfeeds) > 1) {
//                    // When many feeds show the title for each feed.
//                    $showtitle = true;
//                }
                
//                foreach ($rssfeeds as $feed) {
//                    if ($renderablefeed = $this->get_feed($feed, $maxentries, $showtitle)) {
//                        $block->add_feed($renderablefeed);
//                    }
//                }
                
//                $footer = $this->get_footer($rssfeeds);
//            }
//        }

        $d = new DateTime();
        $d->setTime(0,0);
        $i = 0;
        while ($i < $this->config->blockdays) {
            if ($this->config->blockskipweekend == 0 || intval($d->format('N')) <= 5) {
                // initalise new totem element
                $totem = new block_totem_element([
                    'id' => $this->instance->id,
                    'date' => $d->getTimestamp(),
                    'collapsible' => TRUE,
                    'collapsed' => TRUE,
                    'showDate' => TRUE]);
                
                $this->content->text .= $PAGE->get_renderer('block_totem')->render($totem);
               
               
               
                //event_render_table($this->instance->id, $d->getTimestamp(), ($this->config->blockdays == 1 ? 0 : ($i==0 ? 1 : -1)));
                $i++;
            }
            $d->modify('+1 day');
        }
        
        $this->content->footer = $this->get_footer();
        
        return $this->content;
    }
    

    /**
     * Gets the footer, which is the totem page link button
     *
     * @return string|null The renderable footer or null if none should be displayed.
     */
    protected function get_footer() {
        $footer = null;
        $url = new moodle_url('/blocks/totem/view.php', array('blockid' => $this->instance->id));
        $footer = '<div style="text-align:right"><form method="post" action="'.$url.'">
                   <button type="submit" class="btn btn-secondary" title="">'.get_string('opentotempage', 'block_totem').'</button>
                   </form></div>';
        
        return $footer;
    }    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
/**    function get_content() {
        $this->content = new stdClass();
        
        $this->content->text .= 'abbregiated list of teachers notices (next '.(($this->config->showdayinblock)-1).' days)';
        $this->content->text .= '<br>abbregiated list of text infos';
        $this->content->footer = '<div style="text-align:right"><form method="post" action="/blocks/totem/view.php"><button type="submit" class="btn btn-secondary" title="">'.get_string('text:showmore', 'block_totem').'</button></form></div>';
        
        return $this->content;
    }*/

}
