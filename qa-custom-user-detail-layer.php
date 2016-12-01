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
    
    function head_script()
    {
        qa_html_theme_base::head_script();
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
            $action = isset($this->content['raw']['action']) ? $this->content['raw']['action'] : 'activities';
            $this->output('<SCRIPT TYPE="text/javascript" SRC="'.$this->infscr->pluginjsurl.'jquery-ias.min.js"></SCRIPT>');
            $this->output('<SCRIPT TYPE="text/javascript">var action = "'.$action.'";</SCRIPT>');
            $this->output('<SCRIPT TYPE="text/javascript" SRC="'. QA_HTML_THEME_LAYER_URLTOROOT.'js/ias-user.js"></SCRIPT>');
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
        if (qa_opt('site_theme') === CUD_TARGET_THEME_NAME && $this->template === 'user') {
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
}
