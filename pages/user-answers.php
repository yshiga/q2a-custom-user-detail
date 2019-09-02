<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }
    // 回答
    $answers_start = ($action === 'answers') ? $start : 0;
    $answers_sel = cud_db_user_recent_a_qs_selectspec($loginuserid, $answers_start, null, $pagesize, $userid);
    $answers_sel['columns']['content'] = '^posts.content ';
    $answers_sel['columns']['format'] = '^posts.format ';
    $answers = qa_db_select_with_pending($answers_sel);
    // $answers = array_slice($answers, $start, $pagesize);

    $usershtml = qa_userids_handles_html($answers, false);
    
    $values = array();
    $htmldefaults = qa_post_html_defaults('Q');
    $htmldefaults['contentview'] = true;
    $htmldefaults['voteview'] = false;

    foreach ($answers as $question) {
        $options = qa_post_html_options($question, $htmldefaults);
        $fields = qa_post_html_fields($question, $loginuserid, qa_cookie_get(),
            $usershtml, null, $options);
        
        if (function_exists('qme_remove_anchor')) {
            $fields['content'] = qme_remove_anchor($fields['content']);
        }
        $values[] = $fields;
    }
    
    return $values;


    function cud_db_user_recent_a_qs_selectspec($voteuserid, $start, $categoryslugs=null, $count=null, $userid)
    {
        $count=isset($count) ? min($count, QA_DB_RETRIEVE_QS_AS) : QA_DB_RETRIEVE_QS_AS;

        $selectspec=qa_db_posts_basic_selectspec($voteuserid);

        qa_db_add_selectspec_opost($selectspec, 'aposts', false, false);
        qa_db_add_selectspec_ousers($selectspec, 'ausers', 'auserpoints');

        $selectspec['source'].=" JOIN ^posts AS aposts ON ^posts.postid=aposts.parentid".
            (QA_FINAL_EXTERNAL_USERS ? "" : " LEFT JOIN ^users AS ausers ON aposts.userid=ausers.userid").
            " LEFT JOIN ^userpoints AS auserpoints ON aposts.userid=auserpoints.userid".
            " WHERE ".
            qa_db_categoryslugs_sql_args($categoryslugs, $selectspec['arguments']).
            " ^posts.type='Q'".
            " AND aposts.userid=#".
            " ORDER BY ^posts.created DESC LIMIT #,#";

        array_push($selectspec['arguments'], $userid, $start, $count);

        $selectspec['sortdesc']='otime';
        return $selectspec;

    }