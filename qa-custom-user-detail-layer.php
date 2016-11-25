<?php

require_once CUD_DIR.'/cud-theme-main.php';

class qa_html_theme_layer extends qa_html_theme_base
{
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
    
}
