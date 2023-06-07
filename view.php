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
 * Version information for the local_article plugin.
 *
 * @package    local_article
 * @author     Wan Asyraf
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__ . '/../../config.php');

require_once($CFG->libdir.'/moodlelib.php');
require_login();

global $DB; //*IMPORTANT* This is needed for querying the database without writing direct statements

$PAGE-> set_url(new moodle_url('/local/article/view.php')); //set the url to the page
$PAGE-> set_context(\context_system::instance());
$PAGE-> set_title(get_string('article_view_page_title', 'local_article')); // set title for the page


$id = optional_param('id','', PARAM_TEXT); //get the id from the url parameter

$articleview = $DB->get_records('local_article', ['id' => $id]); //fetch all article data from db

//-----Fetch URL for displaying image -----
$itemid = $id;
$component_name = 'local_article';
$filearea = 'attachment';

$sql = "select * from mdl_files where itemid = ? and component = ? and filearea = ? and source is not null ";
$data = $DB->get_record_sql($sql, array($itemid, $component_name, $filearea));

$fs = get_file_storage();

$fileinfo = array(
    'component' => $data->component,
    'filearea' => $data->filearea,     
    'itemid' => $data->itemid,              
    'contextid' => $data->contextid, 
    'filepath' => '/',           
    'filename' => $data->filename); 

//fetch the file based on information in $fileinfo variable 
$file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
$fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

$imageData = $file->get_content();
$base64Data = base64_encode($imageData);
$dataUrl = 'data:image/jpeg;base64,' . $base64Data;
//-------------------------------------------------

echo $OUTPUT->header(); //header of the page

//*Content Body----------------------------------------------------
//template context (from local/templates)----------------------------------------------------
$templatecontext = (object)[
    'articleviews' => array_values($articleview), //send the array values from db to mustache template
    'indexURL' => new moodle_url('/local/article/index.php'), //set the list url for navigation
    'deleteURL' => new moodle_url('/local/article/delete.php?id='.$id), //set delete url
    'editURL' => new moodle_url('/local/article/add.php?id='.$id), //set edit url 
    'imgURL' => new moodle_url($dataUrl), //set img url (if there's any)
];

echo $OUTPUT->render_from_template('local_article/view', $templatecontext);
//template context ends here ----------------------------------------------------------------
//*Content Body Ends Here----------------------------------------------------

echo $OUTPUT->footer(); //footer of the page 