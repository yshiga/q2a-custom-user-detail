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

    qa_set_template('user-followers');
    $qa_content = qa_content_prepare(true);

    $userhtml = qa_html($handle);

    $qa_content['title'] = 'User Followers Page';

    return $qa_content;