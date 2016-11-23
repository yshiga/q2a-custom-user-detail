<?php
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    require_once QA_INCLUDE_DIR.'db/selects.php';
    require_once QA_INCLUDE_DIR.'app/format.php';
    
    $qa_content = qa_content_prepare(true);
    return $qa_content;
