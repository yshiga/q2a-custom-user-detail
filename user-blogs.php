<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // ブログ
    $blogs_sel = qas_blog_db_user_recent_posts_selectspec( $loginuserid, $identifier, qa_opt_if_loaded( 'qas_blog_page_size_ps' ), 0 );
    $blogs_sel['columns']['content'] = '^blogs.content ';
    $blogs = qa_db_select_with_pending($blogs_sel);
    
    $pagesize = qa_opt('page_size_qs');
    $blogs = array_slice($blogs, 0, $pagesize);
    $usershtml = qa_userids_handles_html($blogs, false);
    
    $values = array();
	$htmldefaults = qa_post_html_defaults('B');
	$htmldefaults['whoview'] = false;
	$htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($blogs as $post) {
        $values[] = qas_blog_post_html_fields( $post, $loginuserid, qa_cookie_get(),
            $usershtml, null, qas_blog_post_html_options( $post, $htmldefaults ) );
    }
    
    return $values;
