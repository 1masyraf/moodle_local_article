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
 * Version information for the local_message plugin.
 *
 * @package    local_article
 * @author     Wan Asyraf
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php"); // Load form library

class articleform_form extends moodleform {

    //add elements to the form
    public function definition() {
        
        global $CFG;
        $mform = $this->_form; //Dont forget the underscore

        // article title field
        $mform->addElement('text', 'article_title', get_string('label_article_title', 'local_article')); //Form element (dataType, name of the element, label)
        $mform->setType('article_title', PARAM_NOTAGS); //Set the type of element
        $mform->setDefault('article_title',get_string('default_article_title', 'local_article')); //Default value (string is from lang/en folder)

        //article description/item field
        $mform->addElement('textarea', 'article_item', get_string('label_article_item', 'local_article')); //Form element (dataType, name of the element, label)
        $mform->setType('article_item', PARAM_NOTAGS); //Set the type of element
        $mform->setDefault('article_item',get_string('default_article_item', 'local_article')); //Default value (string is from lang/en folder)
        
        //article image upload field
        $maxbytes= get_max_upload_sizes();
        $mform->addElement( 'filemanager','article_pic',
        get_string('label_article_pic','local_article'), null,
            [
                'subdirs' => 0,
                'maxbytes' => $maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => 1,
                'accepted_types' =>array('image'),
                // 'return_types' => FILE_INTERNAL | FILE_EXTERNAL,
            ]
        );

        $this->add_action_buttons(); //form submit and cancel buttons
    }

    //custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}