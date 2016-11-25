<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

class qa_custom_user_detail_process
{
    public function init_page()
    {
        $page = qa_request_part(0);
        $action = qa_request_part(2);
        if ($page !== 'user' || !empty($action)) {
            return;
        }
        
        if (qa_opt('site_theme') !== CUD_TARGET_THEME_NAME) {
            return;
        }
        require_once QA_INCLUDE_DIR.'app/cookies.php';
        require_once QA_INCLUDE_DIR.'app/format.php';
        require_once QA_INCLUDE_DIR.'app/users.php';
        require_once QA_INCLUDE_DIR.'app/options.php';
        require_once QA_INCLUDE_DIR.'db/selects.php';

        global $qa_usage;

        qa_report_process_stage('init_page');
        qa_db_connect('qa_page_db_fail_handler');

        qa_page_queue_pending();
        qa_load_state();
        qa_check_login_modules();

        if (QA_DEBUG_PERFORMANCE)
            $qa_usage->mark('setup');

        qa_check_page_clicks();
        //    Determine the identify of the user
        
        qa_set_template('user');
        $qa_content = include CUD_DIR.'/user-detail.php';
        
        if (is_array($qa_content)) {
            if (QA_DEBUG_PERFORMANCE)
                $qa_usage->mark('view');

            qa_output_content($qa_content);

            if (QA_DEBUG_PERFORMANCE)
                $qa_usage->mark('theme');

            if (qa_do_content_stats($qa_content) && QA_DEBUG_PERFORMANCE)
                $qa_usage->mark('stats');

            if (QA_DEBUG_PERFORMANCE)
                $qa_usage->output();
        }

        qa_db_disconnect();
    }
}
