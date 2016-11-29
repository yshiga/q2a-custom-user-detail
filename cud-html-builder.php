<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

class cud_html_builder
{
    
    private static function create_buttons($userid)
    {
        if($userid === qa_get_logged_in_userid()) {
            $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="/account">プロフィール編集</a>';
        } else {
            $buttons = '<a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect">フォローする</a><a class="mdl-button mdl-button__block mdl-js-button mdl-button--raised mdl-button--primary mdl-color-text--white mdl-js-ripple-effect">メッセージ送信</a>';
        }
        return $buttons;
    }
    
    private static function create_q_list($q_items, $type)
    {
        // print_r($q_items);
        $html = '';
        if (count($q_items) > 0) {
            foreach ($q_items as $q_item) {
                $html .= self::q_list_item($q_item); 
            }
        } else {
            $html = '<section><div class="qa-a-list-item"><div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">';
            $html .= '<div class="mdl-card__supporting-text">';
            $html .= '<div class="qa-q-item-main">';
            $html .= qa_lang_html_sub('profile/no_posts_by_x', $type);
            $html .= "</div></div></div></div></section>";
        }
        $html .= '<div class="ias-spinner" style="align:center;"><span class="mdl-spinner mdl-js-spinner is-active" style="height:20px;width:20px;"></span></div>';
        return $html;
    }
    
    static function q_list_item($q_item)
    {
        $html = '<section>'.PHP_EOL;
        $html .= '<div class="qa-q-list-item'.rtrim(' '.@$q_item['classes']).'" '.@$q_item['tags'].'>'.PHP_EOL;
        $html .= '<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">'.PHP_EOL;
        if(self::get_thumbnail($q_item['raw']['postid'])) {
            $html .= self::q_item_thumbnail($q_item);
        }
        $html .= '<div class="mdl-card__supporting-text">'.PHP_EOL;
        $html .= self::q_item_main($q_item);
        $html .= self::q_item_clear();

        $html .= '</div> <!-- END mdl-grid -->'.PHP_EOL;
        $html .= '</div> <!-- END mdl-card -->'.PHP_EOL;
        $html .= '</div> <!-- END qa-q-list-item -->'.PHP_EOL;
        $html .= '</section>'.PHP_EOL;
        
        return $html;
    }
    
    private static function q_item_main($q_item)
    {
        $html = '<div class="qa-q-item-main">'.PHP_EOL;
        $html .= self::q_item_stats($q_item);
        $html .= self::q_item_title($q_item);
        $html .= '<div class="qa-q-item-tags-view">'.PHP_EOL;
        $html .= self::post_tags($q_item, 'qa-q-item');
        $html .= '</div><!-- END qa-q-item-tags-view -->'.PHP_EOL;
        $html .= self::post_avatar_meta($q_item, 'qa-q-item');
        // $html .= self::q_item_buttons($q_item);
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }
    
    private static function q_item_clear()
    {
        $html = '<div class="qa-q-item-clear">'.PHP_EOL;
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }
    
    static function q_item_stats($q_item)
    {
        $selected_class = @$q_item['answer_selected'] ? 'qa-a-count-selected' : (@$q_item['answers_raw'] ? null : 'qa-a-count-zero');
        $html = '<div class="qa-q-item-stats '.$selected_class.'">'.PHP_EOL;
        $html .= self::a_count($q_item);
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }
    
    static function q_item_title($q_item)
    {
        $search = '/.*>(.*)<.*/';
        $replace = '$1';
        if (isset($q_item['title'])) {
            $q_item_title = preg_replace($search, $replace, $q_item['title']);
        } else {
            $q_item_title = '';
        }
        $len = mb_strlen($q_item_title, 'UTF-8');
        if ($len > 60) {
            $q_item_title = mb_substr($q_item_title, 0, 60 - 1, 'UTF-8') . '…';
        }
        
        $html = self::get_thumbnail($q_item['raw']['postid']) ? '<div class="mdl-card__title">' : '<div class="mdl-card__title no-thumbnail">';
        $html .= '<h1 class="mdl-card__title-text qa-q-item-title">'.PHP_EOL;
        $html .= '<a href="'.$q_item['url'].'">'.$q_item_title.'</a>'.PHP_EOL;
        // add closed note in title
        $html .= empty($q_item['closed']['state']) ? '' : ' ['.$q_item['closed']['state'].']';
        $html .= '</h1>'.PHP_EOL;
        $html .= '</div>'.PHP_EOL;

        $blockwordspreg = qa_get_block_words_preg();
        if (isset($q_item['raw']['content'])) {
            $text = qa_viewer_text($q_item['raw']['content'], 'html', array('blockwordspreg' => $blockwordspreg));
        } else {
            $text = '';
        }
        $q_item_content = preg_replace($search, $replace, $text);
        $q_item_content = mb_strimwidth($q_item_content, 0, 150, "...", "utf-8");
        $html .= '<div class="qa-item-content">'.PHP_EOL;
        $html .= $q_item_content.PHP_EOL;
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }
    
    public static function post_tags($post, $class)
    {
        $html = '';
        if (!empty($post['q_tags'])) {
            $html = '<div class="'.$class.'-tags">'.PHP_EOL;
            $html .= self::post_tag_list($post, $class);
            $html .= '</div>'.PHP_EOL;
        }
        
        return $html;
    }
    
    public static function post_tag_list($post, $class)
    {
        $html = '<div class="'.$class.'-tag-list">'.PHP_EOL;
        foreach ($post['q_tags'] as $taghtml) {
            $search = '/(<a.*class=")(.*)(".*>)(.*?)(<\/a>)/';
            $replace = '$1$2 mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--raised$3$4$5';
            $taghtml = preg_replace($search, $replace, $taghtml);
            $html .= self::post_tag_item($taghtml, $class);
        }
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }

    public static function post_tag_item($taghtml, $class)
    {
        return '<span class="'.$class.'-tag-item">'.$taghtml.'</span>'.PHP_EOL;
    }
    
    public static function post_avatar_meta($post, $class,$post_meta_show = true, $avatarprefix = null, $metaprefix = null, $metaseparator = '<br/>')
    {
        $html = '';
        //コメントリストの場合はflex-styleで囲む
        $c_item_class = ($class === "qa-c-item") ? 'flex-style' :  '' ;
        //アバター画像がある時だけタグ生成
        if (isset($post['avatar'])) {
            $html = '<span class="'.$class.'-avatar-meta '.$c_item_class.'">'.PHP_EOL;
        }

        $html .= self::avatar($post, $class, $avatarprefix);

        if($post_meta_show) {
            $html .= self::post_meta($post, $class, $metaprefix, $metaseparator);
        }
        

        //アバター画像がある時だけタグ生成
        if (isset($post['avatar'])) {
            $html .= '</span>'.PHP_EOL;
        }
        
        return $html;
    }
    
    public static function avatar($item, $class, $prefix=null)
    {
        $html = '';
        if (isset($item['avatar'])) {
            if (isset($prefix)) {
                $html = $prefix.PHP_EOL;
            }
            //アバターのサイズを取得して$qa_size[]に格納
            preg_match('/qa_size=([0-9][0-9])/is', $item['avatar'], $qa_size);
            //アバター画像を丸の中に収めるようにサイズを小さく
            $img_width_height = $qa_size[1]*0.7;
            $html .= '<div class="'.$class.'-avatar" style="background:url(./?qa=image&qa_blobid='.$item['raw']['avatarblobid'].'&qa_size='.$qa_size[1].') no-repeat center center;height: '.$img_width_height.'px;width: '.$img_width_height.'px;display:inline-block;border-radius: 50%;background-color: #757575;margin-right:6px;">'.PHP_EOL;

            $html .= '</div>'.PHP_EOL;
        }
        
        return $html;
    }
    
    private static function a_count($post)
    {
        return self::output_split(@$post['answers'], 'qa-a-count', 'div', 'div',
        @$post['answer_selected'] ? 'qa-a-count-selected' : (@$post['answers_raw'] ? null : 'qa-a-count-zero'));
    }
    
    private static function post_meta($post, $class, $prefix=null, $separator='<br/>')
    {
        $html = '<span class="'.$class.'-meta">'.PHP_EOL;

        if (isset($prefix))
            $html .= $prefix;

        $order = explode('^', @$post['meta_order']);

        foreach ($order as $element) {
            switch ($element) {
                case 'what':
                    $html .= self::post_meta_what($post, $class);
                    break;

                case 'when':
                    $html .= self::post_meta_when($post, $class);
                    break;

                case 'where':
                    $html .= self::post_meta_where($post, $class);
                    break;

                case 'who':
                    $html .= self::post_meta_who($post, $class);
                    break;
            }
        }

        $html .= self::post_meta_flags($post, $class);

        if (!empty($post['what_2'])) {
            $html .= $separator;

            foreach ($order as $element) {
                switch ($element) {
                    case 'what':
                        $html .= '<span class="'.$class.'-what">'.$post['what_2'].'</span>';
                        break;

                    case 'when':
                        $html .= self::output_split(@$post['when_2'], $class.'-when');
                        break;

                    case 'who':
                        $html .= self::output_split(@$post['who_2'], $class.'-who');
                        break;
                }
            }
        }

        $html .= '</span>'.PHP_EOL;
        
        return $html;
    }
    
    private static function post_meta_when($post, $class)
    {
        $html = '<span class="qa-q-item-when-what">'.PHP_EOL;

        $html .= self::output_split(@$post['when'], $class.'-when','span');
        
        return $html;
    }
    
    private static function post_meta_what($post, $class)
    {
        $html = '';
        if (isset($post['what'])) {
            $classes = $class.'-what';
            if (@$post['what_your']) {
                $classes .= ' '.$class.'-what-your';
            }

            if (isset($post['what_url'])) {
                $html .= '<a href="'.$post['what_url'].'" class="'.$classes.'">'.$post['what'].'</a>'.PHP_EOL;
            } else {
                $html .= '<span class="'.$classes.'">に'.$post['what'].'</span>'.PHP_EOL;
            }
        }
        $html .= '</span>'.PHP_EOL;
        
        return $html;
    }
    
    private static function post_meta_where($post, $class)
    {
        return '';
    }
    
    private static function post_meta_who($post, $class)
    {
        $html = '';
        if (isset($post['who'])) {
            $html .= '<span class="'.$class.'-who">';

            if (strlen(@$post['who']['prefix'])) {
                $html .= '<span class="'.$class.'-who-pad">'.$post['who']['prefix'].'</span>';
            }

            if (isset($post['who']['data'])) {
                $html .= '<span class="'.$class.'-who-data">'.$post['who']['data'].'</span>';
            }

            if (isset($post['who']['title'])) {
                $html .= '<span class="'.$class.'-who-title">'.$post['who']['title'].'</span>';
            }

            if (isset($post['who']['points'])) {
                $post['who']['points']['prefix'] = '('.$post['who']['points']['prefix'];
                $post['who']['points']['suffix'] .= ')';
                $html .= self::output_split($post['who']['points'], $class.'-who-points');
            }

            if (strlen(@$post['who']['suffix'])) {
                $html .= '<span class="'.$class.'-who-pad">'.$post['who']['suffix'].'</span>';
            }

            $html .= '</span>';
        }
        
        return $html;
    }

    private static function post_meta_flags($post, $class)
    {
        return self::output_split(@$post['flags'], $class.'-flags');
    }
    
    private static function output_split($parts, $class, $outertag='span', $innertag='span', $extraclass=null)
    {
        if (empty($parts) && strtolower($outertag) != 'td')
            return;

        $html = '<'.$outertag.' class="'.$class.(isset($extraclass) ? (' '.$extraclass) : '').'">';
        $html .= (strlen(@$parts['prefix']) ? ('<'.$innertag.' class="'.$class.'-pad">'.$parts['prefix'].'</'.$innertag.'>') : '').
            (strlen(@$parts['data']) ? ('<'.$innertag.' class="'.$class.'-data">'.$parts['data'].'</'.$innertag.'>') : '').
            (strlen(@$parts['suffix']) ? ('<'.$innertag.' class="'.$class.'-pad">'.$parts['suffix'].'</'.$innertag.'>') : '');
        $html .= '</'.$outertag.'>';
        
        return $html;
    }
    
    static function page_links($page_links)
    {
        $html = '';
        if (!empty($page_links)) {
            $html .= '<div class="qa-page-links">'.PHP_EOL;
            $html .= self::page_links_list(@$page_links['items']);
            $html .= self::page_links_clear();
            $html .= '</div>';
        }
        return $html;
    }

    static function page_links_list($page_items)
    {
        $html = '';
        if (!empty($page_items)) {
            $html .= '<nav class="qa-page-links-list">';
            $index = 0;
            foreach ($page_items as $page_link) {
                // self::set_context('page_index', $index++);
                $html .= self::page_link_content($page_link);
                if ($page_link['ellipsis']) {
                    $html .= self::page_links_item(array('type' => 'ellipsis'));
                }
            }
            // self::clear_context('page_index');
            $html .= '</nav>';
        }
        return $html;
    }

    static function page_links_item($page_link)
    {
        return self::page_link_content($page_link);
    }

    static function page_link_content($page_link)
    {
        $label = @$page_link['label'];
        $url = @$page_link['url'];
        $html = '';
        switch ($page_link['type']) {
            case 'this':
                $html .= '<a class="qa-page-selected is-activemdl-js-ripple-effect mdl-button mdl-js-button mdl-button--raised  mdl-button--colored mdl-color-text--white">'.$label.'</a>'.PHP_EOL;
                break;
            case 'prev':
                $html .= '<a href="'.$url.'" class="qa-page-prev mdl-js-ripple-effect mdl-button mdl-js-button"><i class="material-icons">chevron_left</i></a>'.PHP_EOL;
                break;
            case 'next':
                $html .= '<a href="'.$url.'" class="qa-page-next mdl-js-ripple-effect mdl-button mdl-js-button"><i class="material-icons">chevron_right</i></a>'.PHP_EOL;
                break;
            case 'ellipsis':
                $html .= '<span class="qa-page-ellipsis">...</span>'.PHP_EOL;
                break;
            default:
                $html .= '<a href="'.$url.'" class="qa-page-link mdl-js-ripple-effect mdl-button mdl-js-button">'.$label.'</a>'.PHP_EOL;
                break;
        }
        return $html;
    }
    
    static function page_links_clear()
    {
        return '<div class="qa-page-links-clear"></div>';
    }
    
    
    private static function q_item_thumbnail($q_item)
    {
        $thumbnail = self::get_thumbnail($q_item['raw']['postid']);
        $html = '';
        if (!empty($thumbnail)) {
            $thumbnail = preg_replace('/alt="image"/', '', $thumbnail);
            $thumbnail = preg_replace('/src="(.*)".*/', '$1', $thumbnail);
            // $search = '/.*>(.*)<.*/';
            // $replace = '$1';
            // $q_item_title = preg_replace($search, $replace, $q_item['title']);
            $html = '<a href="'.$q_item['url'].'" >'.PHP_EOL;
            $html .= '<div style="background:url('.$thumbnail.') center / cover;" class="mdl-card__title qa-q-item-title thumbnail">'.PHP_EOL;
            $html .= '</div>'.PHP_EOL;
            $html .= '</a>'.PHP_EOL;
        }
        return $html;
    }

    private static function get_thumbnail($postid)
    {
        $post = qa_db_single_select(qa_db_full_post_selectspec(null, $postid));
        $ret = preg_match("/<img(.+?)>/", $post['content'], $matches);
        if ($ret === 1) {
            return $matches[1];
        } else {
            return '';
        }
    }
    
    private static function get_activities($content)
    {
        if (!isset($content['q_list']['activities'])) {
            $html = '';
        } else {
            $html = self::create_q_list($content['q_list']['activities'], '活動履歴');
            if (isset($content['page_links_activities'])) {
                $html .= self::page_links($content['page_links_activities']);
            }
        }
        return $html;
    }
    
    private static function get_questions($content)
    {
        if (!isset($content['q_list']['questions'])) {
            $html = '';
        } else {
            $html = self::create_q_list($content['q_list']['questions'], '質問');
            if (isset($content['page_links_questions'])) {
                $html .= self::page_links($content['page_links_questions']);
            }
        }
        return $html;
        
    }
    
    private static function get_answers($content)
    {
        if (!isset($content['q_list']['answers'])) {
            $html = '';
        } else {
            $html = self::create_q_list($content['q_list']['answers'], '回答');
            if (isset($content['page_links_answers'])) {
                $html .= self::page_links($content['page_links_answers']);
            }
        }
        return $html;
        
    }
    
    private static function get_blogs($content)
    {
        if (!isset($content['q_list']['blogs'])) {
            $html = '';
        } else {
            $html = self::create_q_list($content['q_list']['blogs'], '飼育日誌');
            if (isset($content['page_links_blogs'])) {
                $html .= self::page_links($content['page_links_blogs']);
            }
        }
        return $html;
        
    }
}
