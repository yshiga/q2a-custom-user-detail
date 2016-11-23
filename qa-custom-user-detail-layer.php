<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function body_content()
    {
        if ($this->template === 'user') {
            // もともと入っているサブナビゲーションは使用しない
            unset($this->content['navigation']['sub']);
        }
        qa_html_theme_base::body_content();
    }
}
