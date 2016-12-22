<?php 

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

class cud_utils
{
    public static function get_answer_count_days($userid=null, $days=30)
    {
        if (!isset($userid)) {
            $userid = qa_get_logged_in_userid();
        }
        
        if (empty($userid)) {
            return 0;
        }
        
        $sql = "SELECT count(*)";
        $sql .= " FROM ^posts";
        $sql .= " WHERE type = 'A'";
        $sql .= " AND userid = $";
        $sql .= " AND created > DATE_SUB(NOW(), INTERVAL # DAY)";
        
        return qa_db_read_one_value(qa_db_query_sub($sql, $userid, $days));
    }
}
