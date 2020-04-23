<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    
    // ブログのお気に入り
    $values = array();
    $blog_favorites_count = 0;

    $blog_favorites_start = ($action === 'blog-favorites') ? $start : 0;
    $postids = cud_user_blog_favorites_postids($userid);
    if (count($postids) > 0) {
        $blog_favorites_sel = cud_user_blog_favorites_selectspec( $userid, $postids, $pagesize, $blog_favorites_start);
        $blog_favorites = qa_db_select_with_pending($blog_favorites_sel);
        $blog_favorites_count = get_total_blog_favorites_count($postids);
        $blog_comments = qas_blog_get_post_comments($postids);

        $usershtml = qa_userids_handles_html($blog_favorites, false);
        $htmldefaults = qa_post_html_defaults('B');
        $htmldefaults['contentview'] = true;

        foreach ($blog_favorites as $post) {
            $fields = qas_blog_post_html_fields(
                $post, 
                $loginuserid, qa_cookie_get(),
                $usershtml, null, qas_blog_post_html_options( $post, $htmldefaults )
            );
            $fields['answers_raw'] = @$blog_comments[$post['postid']];
            $fields['answers'] = array(
                'prefix' => '返信',
                'data' => @$blog_comments[$post['postid']],
            );
            if (function_exists('qme_remove_anchor')) {
                $fields['content'] = qme_remove_anchor($fields['content']);
            }
            $values[] = $fields;
        }
    }
    
    return $values;

    function get_total_blog_favorites_count($postids)
    {
        $sql = 'SELECT count(*)';
        $sql.= ' FROM ^blogs';
        $sql.= " WHERE ^blogs.type ='B'";
        $sql.= ' AND ^blogs.postid IN ( $ )';

        return qa_db_read_one_value(qa_db_query_sub($sql, $postids), true);
    }

    function cud_user_blog_favorites_selectspec( $userid, $postids, $count = null, $start = 0)
    {
        $count = isset($count) ? min($count, QA_DB_RETRIEVE_QS_AS) : QA_DB_RETRIEVE_QS_AS;

        $selectspec = qas_blog_db_posts_basic_selectspec($userid);
        $selectspec['columns']['content'] = '^blogs.content ';
        $selectspec['columns']['format'] = '^blogs.format ';

        $source = '';
        $source.= " WHERE ^blogs.type ='B'";
        $source.= ' AND ^blogs.postid IN ( $ )';
        $source.= ' ORDER BY ^blogs.created DESC LIMIT #, #';
        $selectspec['source'].= $source;
        array_push($selectspec['arguments'], $postids, $start, $count);
        $selectspec['sortdesc'] = 'created';

        return $selectspec;
    }

    function cud_user_blog_favorites_postids($userid)
    {
        $sql = '';
        $sql.= '   SELECT entityid FROM ^userfavorites';
        $sql.= '   WHERE entitytype = $';
        $sql.= '   AND userid = #';

        return qa_db_read_all_values(qa_db_query_sub($sql, QAS_BLOG_ENTITY_POST, $userid));
    }
