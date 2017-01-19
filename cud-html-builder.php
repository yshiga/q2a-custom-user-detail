<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

class cud_html_builder
{
    
    public static function create_buttons($userid, $handle, $favorite)
    {
        $buttons = '';
        if($userid === qa_get_logged_in_userid()) {
            $url = '/account';
            $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="'.$url.'">プロフィール編集</a>';
        } else {
            if($favorite['favorite'] === 1) {
                $follow = 'フォローをやめる';
            } else {
                $follow = 'フォローする';
            }
            if (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
                $url = '?state=edit';
                $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="'.$url.'" style="margin-bottom:6px;">プロフィール編集</a>';
            }
            $buttons .= '<div style="margin-bottom:6px;" '.$favorite['favorite_id'].' '.$favorite['code'].'>';
            $buttons .= '<a class=" mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect" '.$favorite['favorite_tags'].'>';
            $buttons .= $follow.'</a>';
            $buttons .= '</div>';
            $buttons .= '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect" href="'.qa_path_html('message/'.$handle).'">メッセージ送信</a>';
        }
        return $buttons;
    }
    
    public static function crate_tab_header($active_tab)
    {
        $html = '';
        $html .= '<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">'.PHP_EOL;
        $html .= '    <div class="mdl-tabs__tab-bar mdl-color--white margin--16px margin--bottom-0">'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['activities'] .'" href="#activities"  id="tab-activities">'.qa_lang_html('cud_lang/activities').'</a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['questions'] .'" href="#questions"  id="tab-questions">'.qa_lang_html('cud_lang/questions').'</a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['answers'] .'" href="#answers"  id="tab-answers">'.qa_lang_html('cud_lang/answers').'</a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['blogs'] .'" href="#blogs"  id="tab-blogs">'.qa_lang_html('cud_lang/blogs').'</a>'.PHP_EOL;
        $html .= '</div>';
        
        return $html;
    }
    
    public static function create_tab_panel($list_type, $active)
    {
        $html = '';
        $html .= '<div class="mdl-tabs__panel '.$active.'" id="'.$list_type.'">'.PHP_EOL;
        $html .= '  <div class="qa-q-list q-list-'.$list_type.'">'.PHP_EOL;
        
        return $html;
    }
    
    public static function create_no_item_list($list_name)
    {
        $html = '';
        $html = '<section><div class="qa-a-list-item"><div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">';
        $html .= '<div class="mdl-card__supporting-text">';
        $html .= '<div class="qa-q-item-main">';
        $html .= qa_lang_html_sub('profile/no_posts_by_x', $list_name);
        $html .= "</div></div></div></div></section>";
        
        return $html;
    }
    
    public static function create_spinner()
    {
        $html = '<div class="ias-spinner" style="align:center;"><span class="mdl-spinner mdl-js-spinner is-active" style="height:20px;width:20px;"></span></div>';
        return $html;
    }
}
