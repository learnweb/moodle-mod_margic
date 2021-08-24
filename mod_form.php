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
 * This file contains the forms to create and edit an instance of the annotateddiary module.
 *
 * @package   mod_annotateddiary
 * @copyright 2019 AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * annotateddiary settings form.
 *
 * @package   mod_annotateddiary
 * @copyright 2019 AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_annotateddiary_mod_form extends moodleform_mod {

    /**
     * Define the annotateddiary activity settings form.
     *
     * @return void
     */
    public function definition() {
        global $COURSE;

        $mform = &$this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('annotateddiaryname', 'annotateddiary'), array(
            'size' => '64'
        ));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements(get_string('annotateddiarydescription', 'annotateddiary'));

        // Add the availability header.
        $mform->addElement('header', 'availibilityhdr', get_string('availability'));

        // 20200915 Moved check so daysavailable is hidden unless using weekly format.
        if ($COURSE->format == 'weeks') {
            $options = array();
            $options[0] = get_string('alwaysopen', 'annotateddiary');
            for ($i = 1; $i <= 13; $i ++) {
                $options[$i] = get_string('numdays', '', $i);
            }
            for ($i = 2; $i <= 16; $i ++) {
                $days = $i * 7;
                $options[$days] = get_string('numweeks', '', $i);
            }
            $options[365] = get_string('numweeks', '', 52);
            $mform->addElement('select', 'days', get_string('daysavailable', 'annotateddiary'), $options);
            $mform->addHelpButton('days', 'daysavailable', 'annotateddiary');

            $mform->setDefault('days', '7');
        } else {
            $mform->setDefault('days', '0');
        }

        $mform->addElement('date_time_selector', 'timeopen', get_string('annotateddiaryopentime', 'annotateddiary'), array(
            'optional' => true,
            'step' => 1
        ));
        $mform->addHelpButton('timeopen', 'annotateddiaryopentime', 'annotateddiary');

        $mform->addElement('date_time_selector', 'timeclose', get_string('annotateddiaryclosetime', 'annotateddiary'), array(
            'optional' => true,
            'step' => 1
        ));
        $mform->addHelpButton('timeclose', 'annotateddiaryclosetime', 'annotateddiary');

        // 20201015 Added Edit all, enable/disable setting.
        $mform->addElement('selectyesno', 'editall', get_string('editall', 'annotateddiary'));
        $mform->addHelpButton('editall', 'editall', 'annotateddiary');

        // 20201119 Added Edit dates, enable/disable setting.
        $mform->addElement('selectyesno', 'editdates', get_string('editdates', 'annotateddiary'));
        $mform->addHelpButton('editdates', 'editdates', 'annotateddiary');

        // Add the rest of the common settings.
        $this->standard_grading_coursemodule_elements();
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}
