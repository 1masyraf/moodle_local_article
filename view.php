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
require_once('lib.php');
require_once($CFG->libdir.'/moodlelib.php');

require_login();

global $DB; //*IMPORTANT* This is needed for querying the database without writing direct statements

$PAGE-> set_url(new moodle_url('/local/article/view.php')); //set the url to the page
$PAGE-> set_context(\context_system::instance());
$PAGE-> set_title(get_string('article_view_page_title', 'local_article')); // set title for the page

$context = context_system::instance();
$PAGE->set_context( $context );

$id = optional_param('id','', PARAM_TEXT); //get the id from the url parameter

$articleview = $DB->get_records('local_article', ['id' => $id]); //fetch all article data from db

echo $OUTPUT->header(); //header of the page

//Image URL fetching setup -----------------------------------------------------------------
$fs = get_file_storage();
if ($files = $fs->get_area_files($context->id, 'local_article', 'attachment', '0' , 'sortorder', false)) {

    //a loop to gather all the files in area files
    foreach($files as $file) {
    // Build the File URL. Long process! But extremely accurate.
	$fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    // Display the image
    $url = $fileurl->get_port() ? $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path() . ':' . $fileurl->get_port() : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
    }

} else {
    //display default image //TODO: find nicer image for this
	$url = 'https://cdn1.iconfinder.com/data/icons/ui-icon-part-3/128/image-1024.png'; //default image
}
//Image URL fetching setup ends here -----------------------------------------------------------------

//*Content Body----------------------------------------------------
//template context (from local/templates)----------------------------------------------------
$templatecontext = (object)[

    'articleviews' => array_values($articleview), //send the array values from db to mustache template
    'indexURL' => new moodle_url('/local/article/index.php'), //set the list url for navigation
    'deleteURL' => new moodle_url('/local/article/delete.php?id='.$id), //set the list url for navigation
    'editURL' => new moodle_url('/local/article/add.php?id='.$id), //set the list url for navigation
    'imgURL' => new moodle_url($url), //url of image (if there's any)
];
echo $OUTPUT->render_from_template('local_article/view', $templatecontext);
//template context ends here ----------------------------------------------------------------
//*Content Body Ends Here----------------------------------------------------

echo $OUTPUT->footer(); //footer of the page 
