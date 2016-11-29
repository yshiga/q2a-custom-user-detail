<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    require_once QA_INCLUDE_DIR.'db/selects.php';
    require_once QA_INCLUDE_DIR.'app/format.php';
    require_once QA_INCLUDE_DIR.'app/limits.php';
    require_once QA_INCLUDE_DIR.'app/updates.php';
    
    $handle = qa_request_part(1);
    $start = qa_get_start();
    $pagesize = qa_opt('page_size_qs');
    if (!strlen($handle)) {
        $handle = qa_get_logged_in_handle();
        qa_redirect(!empty($handle) ? 'user/'.$handle : 'users');
    }
    $loginuserid = qa_get_logged_in_userid();
    
    $identifier = QA_FINAL_EXTERNAL_USERS ? $userid : $handle;

    list($useraccount, $userprofile, $userfields, $usermessages, $userpoints, $userlevels, $navcategories, $userrank) =
        qa_db_select_with_pending(
            QA_FINAL_EXTERNAL_USERS ? null : qa_db_user_account_selectspec($handle, false),
            QA_FINAL_EXTERNAL_USERS ? null : qa_db_user_profile_selectspec($handle, false),
            QA_FINAL_EXTERNAL_USERS ? null : qa_db_userfields_selectspec(),
            QA_FINAL_EXTERNAL_USERS ? null : qa_db_recent_messages_selectspec(null, null, $handle, false, qa_opt_if_loaded('page_size_wall')),
            qa_db_user_points_selectspec($identifier),
            qa_db_user_levels_selectspec($identifier, QA_FINAL_EXTERNAL_USERS, true),
            qa_db_category_nav_selectspec(null, true),
            qa_db_user_rank_selectspec($identifier)
        );
        
    if (!QA_FINAL_EXTERNAL_USERS && !is_array($useraccount)) // check the user exists
        return include QA_INCLUDE_DIR.'qa-page-not-found.php';
        
    if (!QA_FINAL_EXTERNAL_USERS) {
        foreach ($userfields as $index => $userfield) {
            if ( isset($userfield['permit']) && qa_permit_value_error($userfield['permit'], $loginuserid, qa_get_logged_in_level(), qa_get_logged_in_flags()) )
                unset($userfields[$index]); // don't pay attention to user fields we're not allowed to view
        }
    }
    //	Get information on user references
    $errors = array();
    
    $qa_content = qa_content_prepare(true);
    
    $userhtml = qa_html($handle);
    
    $qa_content['title'] = qa_lang_html_sub('profile/user_x', $userhtml);
    $qa_content['error'] = @$errors['page'];
    
    $qa_content['raw']['account'] = $useraccount; // for plugin layers to access
    $qa_content['raw']['profile'] = $userprofile;
    $qa_content['raw']['userid'] = $userid;
    $qa_content['raw']['points'] = $userpoints;
    $qa_content['raw']['rank'] = $userrank;
    
    $qa_content['q_list']['activities'] = include CUD_DIR.'/user-activities.php';
    $qa_content['q_list']['questions'] = include CUD_DIR.'/user-questions.php';
    $qa_content['q_list']['answers'] = include CUD_DIR.'/user-answers.php';
    $qa_content['q_list']['blogs'] = include CUD_DIR.'/user-blogs.php';
    
    $count = $activitiescount;
    if (isset($activitiescount) && isset($pagesize)) {
        $qa_content['page_links_activities'] = qa_html_page_links(qa_request(), $start, $pagesize, $activitiescount, qa_opt('pages_prev_next'), null);
    }
    
    return $qa_content;
