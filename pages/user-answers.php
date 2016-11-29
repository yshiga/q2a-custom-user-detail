<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // 回答
    $answers_sel =qa_db_user_recent_a_qs_selectspec($loginuserid, $identifier, qa_opt_if_loaded('page_size_activity'), 0);
    $answers_sel['columns']['content'] = '^posts.content ';
    $answers = qa_db_select_with_pending($answers_sel);
    
    $pagesize = qa_opt('page_size_qs');
    $answers = array_slice($answers, 0, $pagesize);
    $usershtml = qa_userids_handles_html($answers, false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('Q');
    $htmldefaults['whoview'] = false;
    $htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($answers as $question) {
        $values[] = qa_any_to_q_html_fields($question, $loginuserid, qa_cookie_get(),
            $usershtml, null, array('voteview' => false) + qa_post_html_options($question, $htmldefaults));
    }
    
    return $values;
