<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    // お気に入り質問
    $favorites_start = ($action === 'favorites') ? $start : 0;
    $favorites_sel = qa_db_user_favorite_qs_selectspec($userid, $pagesize, $favorites_start);
    $favorites_sel['columns']['content'] = '^posts.content ';
    $favorites_sel['columns']['format'] = '^posts.format ';
    $favorites = qa_db_select_with_pending($favorites_sel);
    $favoritecount = count($favorites);
    $favorites = array_slice($favorites, $start, $pagesize);
    $usershtml = qa_userids_handles_html($favorites, false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('Q');
    $htmldefaults['whoview'] = false;
    $htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($favorites as $post) {
        $values[] = qa_post_html_fields($post, $loginuserid, qa_cookie_get(),
            $usershtml, null, qa_post_html_options($post, $htmldefaults));
    }
    
    return $values;
