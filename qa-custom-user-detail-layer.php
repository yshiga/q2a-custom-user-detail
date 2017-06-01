<?php

require_once CUD_DIR.'/cud-theme-main.php';

class qa_html_theme_layer extends qa_html_theme_base
{
    private $infscr = null;
    
    function qa_html_theme_layer($template, $content, $rooturl, $request)
    {
        qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
        $this->infscr = new qa_infinite_scroll();
    }
    
    function body_footer()
    {
        qa_html_theme_base::body_footer();
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
            $action = isset($this->content['raw']['action']) ? $this->content['raw']['action'] : 'questions';
            $this->output('<SCRIPT TYPE="text/javascript" SRC="'.$this->infscr->pluginjsurl.'jquery-ias.min.js"></SCRIPT>');
            $cud_lang_json = json_encode (array(
              'read_next' => qa_lang_html('cud_lang/read_next'),
              'read_previous' => qa_lang_html('cud_lang/read_previous'),
            ));
            $this->output(
              '<SCRIPT TYPE="text/javascript">',
              'var action = "'.$action.'";',
              "var cud_lang = '".$cud_lang_json."';",
              '</SCRIPT>'
            );
            $this->output('<SCRIPT TYPE="text/javascript" SRC="'. QA_HTML_THEME_LAYER_URLTOROOT.'js/ias-user.js"></SCRIPT>');
            $this->output('<SCRIPT TYPE="text/javascript" SRC="'. QA_HTML_THEME_LAYER_URLTOROOT.'js/cud-favorite.js"></SCRIPT>');
        }
    }

    function head_css()
    {
        qa_html_theme_base::head_css();
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
            $this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->infscr->plugincssurl.'jquery.ias.css"/>');
        }
    }
    
    public function body_content()
    {
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
            // もともと入っているサブナビゲーションは使用しない
            unset($this->content['navigation']['sub']);
        }
        qa_html_theme_base::body_content();
    }
    
    public function main()
    {
        $editing = (qa_get_state() === 'edit' && qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN);
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME 
            && $this->template === 'user'
            && !$editing) {
            cud_theme_main::main($this);
        } else {
            qa_html_theme_base::main();
        }
    }
    
    public function q_item_title($q_item)
    {
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
        cud_theme_main::q_item_title($this, $q_item);
        } else {
            qa_html_theme_base::q_item_title($q_item);
        }
    }
    
    public function post_avatar_meta($post, $class, $avatarprefix=null, $metaprefix=null, $metaseparator='<br/>')
    {
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
            $this->output('<span class="'.$class.'-avatar-meta">');
            $this->avatar($post, $class, $avatarprefix);
            $this->post_meta($post, $class, $metaprefix, $metaseparator);
            $this->output('</span>');
        } else {
            qa_html_theme_base::post_avatar_meta($post, $class, $avatarprefix, $metaprefix, $metaseparator);
        }
    }
    
    public function favorite_button($tags, $class)
    {
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME) {
    		if (isset($tags)) {
                $label = $this->get_follow_label($tags);
                $new_tags = $this->replace_tags($tags);
                $html = '<a class=" mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect" '.$new_tags.'>'.$label.'</a>';
                $this->output($html);
            }
        } else {
            qa_html_theme_base::favorite_button($tags, $class);
        }
    }
    
    private function get_follow_label($tags)
    {
        $label = '';
        $pat = '/title\s*=\s*["\']([^"\']+)["\']/i';
        $matchcount = preg_match($pat, $tags, $match);
        if ($matchcount > 0) {
            $label = $match[1];
        }
        return $label;
    }
    
    private function replace_tags($tags)
    {
        $pat = '/onclick\s*=\s*["\']([^"\']+)["\']/i';
        $tags2 = preg_replace($pat, '', $tags);
        return $tags2;
    }
}
