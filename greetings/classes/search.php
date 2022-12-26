<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class MyForm extends moodleform {
    public function definition() {
        global $USER;

        // Get a list of the user's cohorts.
        $cohorts = cohort_get_user_cohorts($USER->id);

        // Create a select element for the cohort.
        $mform = $this->_form;
        $select = $mform->addElement('select', 'cohortid', 'Cohort name');
        foreach ($cohorts as $cohort) {
            $select->addOption($cohort->name, $cohort->id);
        }

        // Add a submit button to the form.
        $this->add_action_buttons(false, get_string('search'));
    }
}
