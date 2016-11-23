<?php

/*
    Plugin Name: Custom User Detail Plugin
    Plugin URI:
    Plugin Description: Provide a new user profile page
    Plugin Version: 1.0
    Plugin Date: 2016-11-23
    Plugin Author: 38qa.net
    Plugin Author URI: http://38qa.net/
    Plugin License: GPLv2
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

// process
qa_register_plugin_module('process', 'qa-custom-user-detail-process.php', 'qa_custom_user_detail_process', 'Custom User Detail Process');
