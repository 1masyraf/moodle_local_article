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

require_once(__DIR__ . '/../../config.php');

global $DB; //*IMPORTANT* This is needed for querying the database without writing direct statements

$PAGE->set_url(new moodle_url('/local/article/index.php')); //set the url to the page
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('article_page_title', 'local_article')); // set title for the page

require_login(); // to view this page, user need to be logged in using their credentials

//get all record from db
$articles = $DB->get_records('local_article');
$imgArr = [];

foreach ($articles as $article) {

    // $articleID = $article->id;
    // var_dump($articleID);
    // die();
    //-----Fetch URL for displaying image -----
    $itemid = $article->id;
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
        'filename' => $data->filename
    );

    //fetch the file based on information in $fileinfo variable 
    $file = $fs->get_file(
        $fileinfo['contextid'],
        $fileinfo['component'],
        $fileinfo['filearea'],
        $fileinfo['itemid'],
        $fileinfo['filepath'],
        $fileinfo['filename']
    );

    $imageData = $file->get_content(); //get the image data
    $base64Data = base64_encode($imageData); //encode the image data to 64 bit
    // $dataUrl = 'data:image/jpeg;base64,' . $base64Data; //generate the url of the encoded image data
    $article->article_img = new moodle_url('data:image/jpeg;base64,' . $base64Data);

    //-------------------------------------------------
}
echo $OUTPUT->header(); //header of the page

//template context (from local/templates)----------------------------------------------------
$templatecontext = (object)[
    'articles' => array_values($articles), //list of all articles from the database
'base_url' => $CFG->wwwroot
];
echo $OUTPUT->render_from_template('local_article/article', $templatecontext);
//template context ends here -----------------------------------------------------------------

echo $OUTPUT->footer(); //footer of the page 
