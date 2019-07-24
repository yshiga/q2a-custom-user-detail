<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // ブログのお気に入り
    $blog_favorites_start = ($action === 'blog-favorites') ? $start : 0;
    $blog_favorites_sel = cud_user_blog_favorites_selectspec( $userid, $identifier, $pagesize, $blog_favorites_start);
    $blog_favorites = qa_db_select_with_pending($blog_favorites_sel);
    $blog_favorites_count = get_total_blog_favorites_count($userid);
    
    $usershtml = qa_userids_handles_html($blog_favorites, false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('B');
    $htmldefaults['whoview'] = false;
    $htmldefaults['avatarsize'] = 0;
    $htmldefaults['contentview'] = true;

    foreach ($blog_favorites as $post) {
        $fields = qas_blog_post_html_fields(
            $post, 
            $loginuserid, qa_cookie_get(),
            $usershtml, null, qas_blog_post_html_options( $post, $htmldefaults )
        );
        $fields['answers_raw'] = $post['ccount'];
        $fields['answers'] = array(
            'prefix' => '返信',
            'data' => $post['ccount'],
        );
        if (function_exists('qme_remove_anchor')) {
            $fields['content'] = qme_remove_anchor($fields['content']);
        }
        $values[] = $fields;
    }
    
    return $values;

    function get_total_blog_favorites_count($userid)
    {
        $sql = 'SELECT count(*)';
        $sql.= ' FROM ^blogs';
        $sql.= " WHERE ^blogs.type ='B'";
        $sql.= ' AND ^blogs.postid IN (';
        $sql.= '   SELECT entityid FROM ^userfavorites';
        $sql.= '   WHERE entitytype = $';
        $sql.= '   AND userid = #';
        $sql.= ' )';

        return qa_db_read_one_value(qa_db_query_sub($sql, QAS_BLOG_ENTITY_POST, $userid), true);
    }

    function cud_user_blog_favorites_selectspec( $userid, $identifyier, $count = null, $start = 0)
    {
        $count = isset($count) ? min($count, QA_DB_RETRIEVE_QS_AS) : QA_DB_RETRIEVE_QS_AS;

        $selectspec = qas_blog_db_posts_basic_selectspec($userid);
        $selectspec['columns']['content'] = '^blogs.content ';
        $selectspec['columns']['format'] = '^blogs.format ';
        $selectspec['columns']['ccount'] = 'CASE WHEN c_count IS NULL THEN 0 ELSE c_count END';

        $source = ' LEFT JOIN (';
        $source.= '   SELECT count(parentid) AS c_count, parentid';
        $source.= '   FROM ^blogs';
        $source.= "   WHERE type='C'";
        $source.= '   GROUP BY parentid';
        $source.= '   HAVING c_count > 0';
        $source.= ' ) c ON c.parentid = ^blogs.postid';
        $source.= " WHERE ^blogs.type ='B'";
        $source.= ' AND ^blogs.postid IN (';
        $source.= '   SELECT entityid FROM ^userfavorites';
        $source.= '   WHERE entitytype = $';
        $source.= '   AND userid = #';
        $source.= ' )';
        $source.= 'ORDER BY ^blogs.created DESC LIMIT #, #';
        $selectspec['source'].= $source;
        array_push($selectspec['arguments'], QAS_BLOG_ENTITY_POST, $userid, $start, $count );
        $selectspec['sortdesc'] = 'created';

        return $selectspec;
    }