<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // 質問    
    $questions_sel = qa_db_user_recent_qs_selectspec($loginuserid, $identifier, qa_opt_if_loaded('page_size_qs'), 0);
    $questions_sel['columns']['content'] = '^posts.content ';
    $questions = qa_db_select_with_pending($questions_sel);
    
    $pagesize = qa_opt('page_size_qs');
    $questions = array_slice($questions, 0, $pagesize);
    $usershtml = qa_userids_handles_html($questions, false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('Q');
    $htmldefaults['whoview'] = false;
    $htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($questions as $question) {
		$values[] = qa_post_html_fields($question, $loginuserid, qa_cookie_get(),
			$usershtml, null, qa_post_html_options($question, $htmldefaults));
    }
    
    return $values;
