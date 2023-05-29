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
 *
 * @package    local_article
 * @author     Wan Asyraf
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 use local_article\manage; //include this for class usage

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/forms/articleform.php');

global $DB, $USER, $CFG; //*IMPORTANT* Always import this for convenience

$PAGE-> set_url('/local/article/add.php'); //set the url to the page
$PAGE-> set_context(\context_system::instance());
$PAGE-> set_title(get_string('article_add_title', 'local_article')); // set title for the page

require_login(); // adding and editing article requires login

//initialize class(es)
$manage= new manage();

//get id
$id = optional_param('id','', PARAM_TEXT); //get the id form url parameter
// -----------------------------------------------

//display the form here------------------------
// if no id then it is a new article
$mform = new articleform_form(); //add form
$toform = [];
//---------------------------------------------

//Form data handling --------------------------
//* when the user press cancel button
if ($mform->is_cancelled()) {
    //Go back to the list page
    redirect("/local/article/index.php", '', 10);
}

//* if the user press submit button 
elseif ($fromform = $mform->get_data()){

    $manage->add_article($fromform,$mform);
    
    //After add or edit a  article, redirect user to list of article with a message (index.php)
     redirect("/local/article/index.php", 'Article Successfully Added', 10 , \core\output\notification::NOTIFY_SUCCESS);
}
    

echo $OUTPUT->header(); //header of the page

//Content Body----------------------------------------------------
$mform->display(); //display the forms
//Content Body Ends Here----------------------------------------------------

echo $OUTPUT->footer(); //footer of the page 



