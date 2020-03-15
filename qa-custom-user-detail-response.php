<?php

require_once QA_INCLUDE_DIR.'db/metas.php';

class qa_custom_user_detail_response_page {
    
    function match_request($request) {
        $parts = explode ( '/', $request );
        
        return $parts [0] == 'user-mute';
    }
    
    function process_request($request) {
        require_once QA_INCLUDE_DIR.'app/users.php';
        require_once QA_INCLUDE_DIR.'app/cookies.php';
        require_once QA_INCLUDE_DIR.'app/favorites.php';
        require_once QA_INCLUDE_DIR.'app/format.php';

        $JSONdata = file_get_contents('php://input');
        $json_data = json_decode($JSONdata, true);

        $entitytype=$json_data['entitytype'];
        $entityid=$json_data['entityid'];
        $setmute=$json_data['mute'];
        $code = $json_data['code'];
        $userid=qa_get_logged_in_userid();

        header ( 'Content-Type: application/json' );
        if (!qa_check_form_security_code('favorite-'.$entitytype.'-'.$entityid, $code)) {
            $ret_val = array(
                'message' => qa_lang('misc/form_security_reload')
            );
            echo json_encode($ret_val, JSON_PRETTY_PRINT);
        } elseif (isset($userid)) {
            // mute or unmute user
            $this->cud_user_mute_set($userid, $entitytype, $entityid, $setmute);
            if ($setmute) {
                $message = 'user muted';
            } else {
                $message = 'user unmuted';
            }

            $ret_val = array(
                'message' => $message,
            );
            http_response_code(200);
            echo json_encode($ret_val, JSON_PRETTY_PRINT);
        }
    }

    private function cud_user_mute_set($userid, $entitytype, $entityid, $mute)
    {
        require_once QA_INCLUDE_DIR.'db/favorites.php';

        // Make sure the user is not favoriting themselves
        if ($userid == $entityid) {
            return;
        }

        if ($mute) {
            qa_db_favorite_create($userid, $entitytype, $entityid);
        } else {
            qa_db_favorite_delete($userid, $entitytype, $entityid);
        }
    }
}