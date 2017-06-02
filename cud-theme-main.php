<?php 

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

require_once CUD_DIR.'/cud-html-builder.php';
require_once CUD_DIR.'/cud-utils.php';

class cud_theme_main
{
    protected $context = array();
    
    public static function main($theme_obj)
    {
        $content = $theme_obj->content;
        $theme_obj->output('<main class="mdl-layout__content">');
        $theme_obj->output('<section>');
        $theme_obj->output('<div class="qa-main'.(@$theme_obj->content['hidden'] ? ' qa-main-hidden' : '').'">');
        //横幅を640pxに収める
        if($theme_obj->template !== 'question'&&$theme_obj->template !== 'login')
        $theme_obj->output('<div class="centering__width-640">');

        $theme_obj->widgets('main', 'top');

        if (qa_is_logged_in()) {
            $request = qa_request_parts();
            $handle = isset($request[1]) ? $request[1] : '';
            self::output_user_detail($theme_obj);
            self::output_q_list_tab_header($theme_obj);
            self::output_q_list_panels($theme_obj);
        } else {
            self::output_not_logged_in($theme_obj);
        }
        $theme_obj->output('</div>');

        $theme_obj->widgets('main', 'high');

        if($theme_obj->template !== 'question'&&$theme_obj->template !== 'login')
        $theme_obj->output('</div><!-- END centering__width-640 -->');

        $theme_obj->widgets('main', 'low');
        $theme_obj->page_links();
        $theme_obj->suggest_next();
        $theme_obj->widgets('main', 'bottom');
        
        $theme_obj->output('</div> <!-- END qa-main -->', '');
        $theme_obj->output('</section>');
        $theme_obj->output('</div> <!-- END mdl-layout__content -->', '');
    }
    
    private static function output_user_detail($theme_obj) {
        $path = CUD_DIR . '/html/main_user_detail.html';
        $html = file_get_contents($path);
        $buttons = '';
        $params = self::create_params($theme_obj->content);
        $theme_obj->output( strtr($html, $params) );
    }
    
    private static function create_params($content)
    {
        $raw = $content['raw'];
        $points = $raw['points']['points'];
        $points = $points ? number_format($points) : 0;
        $favorite = isset($content['favorite']) ? $content['favorite'] : '';
        $buttons = cud_html_builder::create_buttons($raw['account']['userid'], $raw['account']['handle'], $favorite);
        return array(
            '^site_url' => qa_opt('site_url'),
            '^blobid' => $raw['account']['avatarblobid'],
            '^handle' => $raw['account']['handle'],
            '^location' => $raw['profile']['location'],
            '^groups' => $raw['profile']['飼-育-群-数'],
            '^years' => $raw['profile']['ニホンミツバチ-飼-育-歴'],
            '^hivetype' => $raw['profile']['使-用-している-巣-箱'],
            '^about' => $raw['profile']['about'],
            '^points' => qa_lang_html_sub('cud_lang/points',$points),
            '^ranking' => qa_lang_html_sub('cud_lang/ranking',$raw['rank']),
            '^buttons' => $buttons,
            '^location_label' => qa_lang_html('cud_lang/location'),
            '^groups_label' => qa_lang_html('cud_lang/number_gropus'),
            '^rearing_history' => qa_lang_html('cud_lang/rearing_history'),
            '^using_hive' => qa_lang_html('cud_lang/using_hive'),
        );
    }
    
    private static function output_q_list_tab_header($theme_obj)
    {
        $active_tab = self::set_active_tab($theme_obj);
        $html = cud_html_builder::crate_tab_header($active_tab);
        $theme_obj->output($html);
    }
    
    private static function output_q_list_tab_footer($theme_obj)
    {
        $theme_obj->output('</div>');
    }
    
    private static function output_q_list_panels($theme_obj)
    {
        $active_tab = self::set_active_tab($theme_obj);
        // self::output_q_list($theme_obj, 'activities', $active_tab['activities']);
        self::output_q_list($theme_obj, 'questions', $active_tab['questions']);
        self::output_q_list($theme_obj, 'answers', $active_tab['answers']);
        self::output_q_list($theme_obj, 'blogs', $active_tab['blogs']);
    }
    
    private static function output_q_list($theme_obj, $list_type, $active_tab)
    {
        $html = cud_html_builder::create_tab_panel($list_type, $active_tab);
        $theme_obj->output($html);
        if (isset($theme_obj->content['q_list'][$list_type])) {
            $q_items = $theme_obj->content['q_list'][$list_type];
            $theme_obj->q_list_items($q_items);
            if (isset($theme_obj->content['page_links_'.$list_type])) {
                self::page_links($theme_obj, $list_type);
            }
            $html = cud_html_builder::create_spinner();
            $theme_obj->output($html);
        } else {
            $list_name = qa_lang_html('cud_lang/'.$list_type);
            $html = cud_html_builder::create_no_item_list($list_name);
            $theme_obj->output($html);
        }
        
        $theme_obj->output('</div>','</div>');
    }
    
    private static function page_links($theme_obj, $list_type)
    {
        $page_links = @$theme_obj->content['page_links_'.$list_type];
        if (!empty($page_links)) {
            $theme_obj->output('<div class="qa-page-links-'.$list_type.'">');
            $theme_obj->page_links_list(@$page_links['items']);
            $theme_obj->page_links_clear();
            $theme_obj->output('</div>');
        }
    }
    
    private static function set_active_tab($theme_obj)
    {
        $action = isset($theme_obj->content['raw']['action']) ? $theme_obj->content['raw']['action'] : 'questions';
        $active_tab = array(
            // 'activities' => '',
            'questions' => '',
            'answers' => '',
            'blogs' => ''
        );
        $active_tab[$action] = 'is-active';
        return $active_tab;
    }
    
    private static function output_not_logged_in($theme_obj)
    {
        $path = CUD_DIR . '/html/not_logged_in.html';
        $html = file_get_contents($path);
        $params = self::get_links_url_params();
        $html = strtr($html, $params);
        $theme_obj->output($html);
    }
    
    // private static function output_not_post_answer($theme_obj)
    // {
    //     $path = CUD_DIR . '/html/not_post_answer.html';
    //     $html = file_get_contents($path);
    //     $subs = array(
    //       'msg_no_post' => qa_lang('cud_lang/msg_no_post'),
    //       'msg_do_post' => qa_lang('cud_lang/msg_do_post'),
    //     );
    //     $theme_obj->output(strtr($html, $subs));
    // }
    
    private static function get_links_url_params()
    {
        $request = qa_request();
        $register_url =qa_html(qa_path('register', array('to' => $request), qa_path_to_root()));
        $login_url =qa_html(qa_path('login', array('to' => $request), qa_path_to_root()));
        return array(
            '^msg_register' => qa_lang_sub('cud_lang/msg_register', $register_url),
            '^msg_login' => qa_lang_sub('cud_lang/msg_login', $login_url),
        );
    }
}
