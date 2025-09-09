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

namespace aiprovider_ai_manager\form;

use core_ai\form\action_settings_form;

/**
 * Generate text action provider settings form.
 *
 * @package    aiprovider_ai_manager
 * @copyright  2025 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class action_generate_text_form extends action_settings_form {
    #[\Override]
    protected function definition() {
        $mform = $this->_form;
        $actionconfig = $this->_customdata['actionconfig']['settings'] ?? [];
        $returnurl = $this->_customdata['returnurl'] ?? null;
        $actionname = $this->_customdata['actionname'];
        $action = $this->_customdata['action'];
        $providerid = $this->_customdata['providerid'] ?? 0;

        // System Instructions.
        $mform->addElement(
            'textarea',
            'systeminstruction',
            get_string("action:{$actionname}:systeminstruction", 'aiprovider_ai_manager'),
            'wrap="virtual" rows="5" cols="20"',
        );
        $mform->setType('systeminstruction', PARAM_TEXT);
        $mform->setDefault('systeminstruction', $actionconfig['systeminstruction'] ?? $action::get_system_instruction());
        $mform->addHelpButton('systeminstruction', "action:{$actionname}:systeminstruction", 'aiprovider_ai_manager');

        if ($returnurl) {
            $mform->addElement('hidden', 'returnurl', $returnurl);
            $mform->setType('returnurl', PARAM_LOCALURL);
        }

        // Add the action class as a hidden field.
        $mform->addElement('hidden', 'action', $action);
        $mform->setType('action', PARAM_TEXT);

        // Add the provider class as a hidden field.
        $mform->addElement('hidden', 'provider', 'aiprovider_ai_manager');
        $mform->setType('provider', PARAM_TEXT);

        // Add the provider id as a hidden field.
        $mform->addElement('hidden', 'providerid', $providerid);
        $mform->setType('providerid', PARAM_INT);

        $this->set_data($actionconfig);
    }
}
