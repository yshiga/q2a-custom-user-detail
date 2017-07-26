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
            $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="'.$url.'">'.qa_lang_html('cud_lang/edit_profile').'</a>';
        } else {
            if($favorite['favorite'] === 1) {
                $follow = qa_lang_html('cud_lang/unfollow');
            } else {
                $follow = qa_lang_html('cud_lang/follow');
            }
            if (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
                $url = '?state=edit';
                $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="'.$url.'" style="margin-bottom:6px;">';
                $buttons .= qa_lang_html('cud_lang/edit_profile').'</a>';
            }
            $buttons .= '<div style="margin-bottom:6px;" '.$favorite['favorite_id'].' '.$favorite['code'].'>';
            $buttons .= '<a class=" mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect" '.$favorite['favorite_tags'].'>';
            $buttons .= $follow.'</a>';
            $buttons .= '</div>';
            $buttons .= '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect" href="'.qa_path_html('message/'.$handle).'">';
            $buttons .= qa_lang_html('cud_lang/send_message').'</a>';
        }
        return $buttons;
    }

    public static function crate_tab_header($active_tab, $postcounts)
    {
        $html = '';
        $html .= '<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">'.PHP_EOL;
        $html .= '    <div class="mdl-tabs__tab-bar mdl-color--white margin--16px margin--bottom-0">'.PHP_EOL;
        // $html .= '  <a class="mdl-tabs__tab '. $active_tab['activities'] .'" href="#activities"  id="tab-activities">'.qa_lang_html('cud_lang/activities').'</a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['questions'] .'" href="#questions"  id="tab-questions"><span class="mdl-badge" data-badge="'.$postcounts['questions'].'">'.qa_lang_html('cud_lang/questions').'</span></a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['answers'] .'" href="#answers"  id="tab-answers"><span class="mdl-badge" data-badge="'.$postcounts['answers'].'">'.qa_lang_html('cud_lang/answers').'</span></a>'.PHP_EOL;
        $html .= '  <a class="mdl-tabs__tab '. $active_tab['blogs'] .'" href="#blogs"  id="tab-blogs"><span class="mdl-badge" data-badge="'.$postcounts['blogs'].'">'.qa_lang_html('cud_lang/blogs').'</span></a>'.PHP_EOL;
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

    public static function create_no_item_list($list_type, $is_my_profile)
    {
      if($is_my_profile) {
        return self::create_no_item_list_mine($list_type);
      } else {
        return self::create_no_item_list_other($list_type);
      }
    }

    public static function create_spinner()
    {

        $html = '<div class="ias-spinner" style="align:center;"><span class="mdl-spinner mdl-js-spinner is-active" style="height:20px;width:20px;"></span></div>';
        return $html;
    }

    private static function create_no_item_list_mine($list_type)
    {
      $template_path = CUD_DIR . '/html/no_item_list_mine.html';
      $template = file_get_contents($template_path);

      $action_link = '';
      if($list_type == 'questions'){
        $action_link = qa_opt('site_url') . 'ask';
      } elseif($list_type == 'answers') {
        $action_link = qa_opt('site_url') . 'how-to-use#answerask';
      } elseif($list_type == 'blogs') {
        $action_link = qa_opt('site_url') . 'blog/new';
      }

      $image_dir = qa_opt('site_url') . 'qa-plugin/'. CUD_FOLDER . '/image/';
      $params = array(
        '^title' => qa_lang('cud_lang/no_item_list_mine_title_' . $list_type),
        '^message' => qa_lang('cud_lang/no_item_list_mine_message_' . $list_type),
        '^image' => $image_dir. 'no_item_list_mine_' . $list_type . '.svg',
        '^action_btn' => qa_lang('cud_lang/no_item_list_mine_action_btn_' . $list_type),
        '^action_link' => $action_link
      );
      $template = file_get_contents($template_path);
      $html = strtr($template, $params);

      return $html;
    }

    private static function create_no_item_list_other($list_type)
    {
      $path = CUD_DIR . '/html/no_item_list_other.html';
      $template = file_get_contents($path);

      $list_name = qa_lang_html('cud_lang/'.$list_type);
      $params = array(
        '^site_url' => qa_opt(site_url),
        '^message' => qa_lang_html_sub('cud_lang/no_posts', $list_name),
        '^image' => qa_opt('site_url') . 'qa-plugin/'. CUD_FOLDER . '/image/no_item_icon.svg'
      );
      $html = strtr($template, $params);
      return $html;
    }
}
