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

//Define global constants
@define( 'CUD_DIR', dirname( __FILE__ ) );
@define( 'CUD_FOLDER', basename( dirname( __FILE__ ) ) );
@define( 'CUD_TARGET_THEME_NAME', 'q2a-material-lite');
@define( 'CUD_RELATIVE_PATH', '../qa-plugin/'.CUD_FOLDER.'/');
@define( 'QA_ENTITY_MUTE', 'M');

// language file
qa_register_plugin_phrases('qa-custom-user-detail-lang-*.php', 'cud_lang');
// process
// qa_register_plugin_module('process', 'qa-custom-user-detail-process.php', 'qa_custom_user_detail_process', 'Custom User Detail Process');
// layer
qa_register_plugin_layer('qa-custom-user-detail-layer.php','Custom User Detail Layer');
qa_register_plugin_overrides('qa-custom-user-detail-overrides.php');
// admin
qa_register_plugin_module('module', 'qa-custom-user-detail-admin.php','qa_cud_admin', 'q2a custom user detail');
// page
qa_register_plugin_module('page', 'qa-custom-user-detail-response.php', 'qa_custom_user_detail_response_page', 'cutom user detail ajax');
