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
 * Base class for common support when editing a multiblock subblock.
 *
 * @package   block_multiblock
 * @copyright 2019 Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_totem\form;

use block_multiblock_proxy_edit_form;

defined('MOODLE_INTERNAL') || die();

/**
 * Base class for common support when editing a multiblock subblock.
 *
 * Note that this extends block_multiblock_proxy_edit_form - this does
 * not actually exist. This is an alias to whichever edit_form that the
 * subblock would instantiate, so that we can overlay our settings on
 * top and not deal with the full set of block settings which won't be
 * relevant.
 *
 * @package   block_multiblock
 * @copyright 2019 Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editblock extends block_multiblock_proxy_edit_form {
    /** @var block_base $block The block class instance that belongs to the block type being edited */
    public $block;

    /** @var object The page object for the page which contains the block being edited */
    public $page;

    /** @var object The multiblock object being edited */
    public $multiblock;

    /**
     * Creates the instance-specific editing form.
     *
     * @param string|moodle_url $actionurl The form action to submit to
     * @param block_base $block The block class being edited
     * @param object $page The contextually appropriate $PAGE type object of the block being edited
     * @param object $multiblock The multiblock object being edited (mostly for its configuration
     */
    public function __construct($actionurl, $block, $page, $multiblock = null) {
        $this->block = $block->blockinstance;
        $this->block->instance->visible = true;
        $this->block->instance->region = $this->block->instance->defaultregion;
        $this->block->instance->weight = $this->block->instance->defaultweight;
        $this->page = $page;
        $this->multiblock = $multiblock;

        if (!empty($this->block->configdata)) {
            $this->block->config = @unserialize(base64_decode($this->block->configdata));
        }

        parent::__construct($actionurl, $this->block, $this->page);
    }

    /**
     * Provides the save buttons for the edit-block form.
     */
    public function add_save_buttons() {
        // Now we add the save buttons.
        $mform =& $this->_form;

        $buttonarray = [];
        $buttonarray[] = &$mform->createElement('submit', 'saveandreturn',
            get_string('saveandreturntomanage', 'block_multiblock'));

        // If the page type indicates we're not on a wildcard page, we can probably* go back there.
        // Note: * for some definition of probably.
        if (strpos($this->multiblock->instance->pagetypepattern, '*') === false) {
            $buttonarray[] = &$mform->createElement('submit', 'saveanddisplay', get_string('savechangesanddisplay'));
        }

        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
    
    /**
     * Sets up the form definition - this will intentionally override the normal block
     * block configuration so we only get the parts specific to the subblock.
     */
    public function definition() {
        $mform =& $this->_form;
        
        $this->specific_definition($mform);
        
        if (!empty($this->multiblock) && $mform->elementExists('config_title')) {
            $presentations = block_multiblock::get_valid_presentations();
            $defaultconfig = (object) ['presentation' => block_multiblock::get_default_presentation()];
            $config = !empty($this->multiblock->config) ? $this->multiblock->config : $defaultconfig;
            if ($presentations[$config->presentation]->requires_title()) {
                $requiredmsg = get_string('requirestitle', 'block_multiblock', $this->multiblock->get_title());
                $mform->addRule('config_title', $requiredmsg, 'required', null, 'client');
            }
        }
        
        $this->add_save_buttons();
    }
}