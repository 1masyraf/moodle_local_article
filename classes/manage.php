<?php

namespace local_article;

use dml_exception;
use stdClass;
use context_system;
use moodle_url;

class manage {
    
     // Function for creating a new article
     public function add_article($fromform, $mform){
        global $DB;

        //if no id then add new
        $newArticle = new stdClass();
        $systemcontext = context_system::instance();

        $newArticle->article_title = $fromform->article_title;
        $newArticle->article_item = $fromform->article_item;
        
        $newArticleID = $DB->insert_record('local_article', $newArticle, true, false);

        //?IMAGE HANDLING HERE-------------------------------------------------
        //get image in draft area in file manager field
        $newArticleFileID = file_get_submitted_draft_itemid('article_pic');
        //set max upload size
        $maxbytes= get_max_upload_sizes();
        //save the image in the draft area to the db
        file_save_draft_area_files($newArticleFileID, $systemcontext->id, 'local_article', 'attachment', $newArticleID,
        [
          'subdirs' => 0,
          'maxbytes' => $maxbytes,
          'maxfiles' => 1,
      ]);

        return true;
    }

      // Function for deleting article
      public function delete_article($id){
        global $DB;

        $id = optional_param('id','', PARAM_TEXT);

        $transaction= $DB->start_delegated_transaction();
        $deletedArticle = $DB->delete_records('local_article', ['id' => $id]);

        if($deletedArticle){
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }
}