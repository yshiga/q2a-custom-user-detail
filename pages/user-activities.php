<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // アクティビティ
    $activities_sel = qa_db_user_recent_qs_selectspec($loginuserid, $identifier, null);
    $answers_sel = qa_db_user_recent_a_qs_selectspec($loginuserid, $identifier);
    $comments_sel = qa_db_user_recent_c_qs_selectspec($loginuserid, $identifier);
    $edit_sel = qa_db_user_recent_edit_qs_selectspec($loginuserid, $identifier);
    $activities_sel['columns']['content'] = '^posts.content';
    $activities_sel['columns']['format'] = '^posts.format';
    $answers_sel['columns']['content'] = '^posts.content';
    $answers_sel['columns']['format'] = '^posts.format';
    $comments_sel['columns']['content'] = '^posts.content';
    $comments_sel['columns']['format'] = '^posts.format';
    $edit_sel['columns']['content'] = '^posts.content';
    $edit_sel['columns']['format'] = '^posts.format';
	$edit_sel['source'] .= " AND editposts.updatetype NOT IN ('A', 'T')";
	
    list($activities, $answerqs, $commentqs, $editqs) = qa_db_select_with_pending(
        $activities_sel,
        $answers_sel,
        $comments_sel,
        $edit_sel
    );
    $activities = qa_any_sort_and_dedupe(array_merge($activities, $answerqs, $commentqs, $editqs));
    $activitiescount = count($activities);
    $activities = array_slice($activities, $start, $pagesize);
    $usershtml = qa_userids_handles_html(qa_any_get_userids_handles($activities), false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('Q');
    $htmldefaults['whoview'] = false;
    $htmldefaults['voteview'] = false;
    $htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($activities as $question) {
        $values[] = qa_any_to_q_html_fields($question, $loginuserid, qa_cookie_get(),
            $usershtml, null, array('voteview' => false) + qa_post_html_options($question, $htmldefaults));
    }
    
    return $values;
