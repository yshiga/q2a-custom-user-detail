<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    require_once QA_INCLUDE_DIR.'db/users.php';
    require_once QA_INCLUDE_DIR.'db/selects.php';
    require_once QA_INCLUDE_DIR.'app/format.php';
    require_once CUD_DIR.'/cud-utils.php';

    $handle = qa_request_part(1);
    $start = qa_get_start();
    $pagesize = qa_opt('page_size_users');
    if (!strlen($handle)) {
        $handle = qa_get_logged_in_handle();
        qa_redirect(!empty($handle) ? 'user/'.$handle : 'users');
    }
    $userid = qa_db_user_find_by_handle($handle);

    //    Get list of favorites users
    $selectspec = qa_db_top_users_selectspec($start, qa_opt_if_loaded('page_size_users'));
    $query .= " ^users JOIN ^userpoints ON ^users.userid=^userpoints.userid";
    $query .= " LEFT JOIN ( SELECT userid, CASE WHEN title = 'about' THEN content ELSE '' END AS about";
    $query .= " FROM qa_userprofile WHERE title like 'about' ) a ON qa_users.userid = a.userid";
    $query .= " LEFT JOIN ( SELECT userid, CASE WHEN title = 'location' THEN content ELSE '' END AS location";
    $query .= " FROM qa_userprofile WHERE title like 'location' ) l ON qa_users.userid = l.userid";
    $query .= " WHERE ^users.userid IN ( ";
    $query .= " SELECT entityid ";
    $query .= " FROM ^userfavorites";
    $query .= " WHERE entitytype = 'U'";
    $query .= " AND userid = $ )";
    $query .= " LIMIT #,#";
    $selectspec['arguments'] = array();
    $selectspec['arguments'][] = $userid;
    $selectspec['arguments'][] = $start;
    $selectspec['arguments'][] = $pagesize;
    $selectspec['columns']['about'] = 'a.about';
    $selectspec['columns']['location'] = 'l.location';
    $selectspec['source'] = $query;

    $users = qa_db_select_with_pending($selectspec);

    $users = array_slice($users, 0, $pagesize);
    $usershtml = qa_userids_handles_html($users);

//    Prepare content for theme
    qa_set_template('user-following');
    $qa_content = qa_content_prepare(true);

    $userhtml = qa_html($handle);

    $qa_content['title'] = qa_lang_html_sub('cud_lang/follows_title', $handle);

    $count = cud_utils::get_follows_count($userid);

    $qa_content['users'] = array(
        'items' => array(),
        'rows' => 1,
        'type' => 'users',
        'following_count' => $count['following'],
        'followers_count' => $count['followers']
    );

    $qa_content['type'] = 'following';
    
    if (count($users)) {
        foreach ($users as $userid => $user) {
            if (QA_FINAL_EXTERNAL_USERS)
                $avatarhtml = qa_get_external_avatar_html($user['userid'], qa_opt('avatar_users_size'), true);
            else {
                $avatarhtml = qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'],
                    $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_users_size'), true);
            }

            // avatar and handle now listed separately for use in themes
            $qa_content['users']['items'][] = array(
                'avatar' => $avatarhtml,
                'label' => $usershtml[$user['userid']],
                'url' => qa_path_html('user/'.$user['handle']),
                'score' => qa_html(number_format($user['points'])),
                'about' => $user['about'],
                'location' => $user['location'],
                'raw' => $user,
            );
        }
    }
    $qa_content['page_links'] = qa_html_page_links(qa_request(), $start, $pagesize, $count['following'], qa_opt('pages_prev_next'));

    return $qa_content;
