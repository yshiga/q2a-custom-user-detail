<?php

class qa_cud_admin
{
    public function init_queries($tableslc)
    {
        return;
    }
    
    public function option_default($option)
    {
        switch ($option) {
            default:
                return;
        }
    }

    public function allow_template($template)
    {
        return $template !== 'admin';
    }

    public function admin_form(&$qa_content)
    {
        // process the admin form if admin hit Save-Changes-button
        $ok = null;
        if (qa_clicked('qa_cud_option_save')) {
            qa_opt('cud_opt_no_post_blogs', qa_post_text('cud_opt_no_post_blogs'));
            qa_opt('cud_opt_no_post_answers', qa_post_text('cud_opt_no_post_answers'));
            qa_opt('cud_opt_no_post_questions', qa_post_text('cud_opt_no_post_questions'));
            qa_opt('cud_opt_no_post_favorites', qa_post_text('cud_opt_no_post_favorites'));
            qa_opt('cud_opt_no_post_others_image', qa_post_text('cud_opt_no_post_others_image'));
            $ok = qa_lang('admin/options_saved');
        }

        // form fields to display frontend for admin
        $fields = array();

        $fields[] = array(
            'label' => qa_lang('cud_lang/no_post_blogs'),
            'tags' => 'NAME="cud_opt_no_post_blogs"',
            'value' => qa_opt('cud_opt_no_post_blogs'),
            'type' => 'textarea',
            'rows' => 5,
        );

        $fields[] = array(
            'label' => qa_lang('cud_lang/no_post_answers'),
            'tags' => 'NAME="cud_opt_no_post_answers"',
            'value' => qa_opt('cud_opt_no_post_answers'),
            'type' => 'textarea',
            'rows' => 5,
        );

        $fields[] = array(
            'label' => qa_lang('cud_lang/no_post_questions'),
            'tags' => 'NAME="cud_opt_no_post_questions"',
            'value' => qa_opt('cud_opt_no_post_questions'),
            'type' => 'textarea',
            'rows' => 5,
        );

        $fields[] = array(
            'label' => qa_lang('cud_lang/no_post_favorites'),
            'tags' => 'NAME="cud_opt_no_post_favorites"',
            'value' => qa_opt('cud_opt_no_post_favorites'),
            'type' => 'textarea',
            'rows' => 5,
        );

        $fields[] = array(
            'type' => 'blank',
        );

        $fields[] = array(
            'label' => qa_lang('cud_lang/no_post_others_image'),
            'tags' => 'NAME="cud_opt_no_post_others_image"',
            'value' => qa_opt('cud_opt_no_post_others_image'),
            'type' => 'text',
        );

        return array(
            'ok' => ($ok && !isset($error)) ? $ok : null,
            'fields' => $fields,
            'buttons' => array(
                array(
                    'label' => qa_lang_html('main/save_button'),
                    'tags' => 'name="qa_cud_option_save"',
                ),
            ),
        );
    }
}
