<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

class cud_html_builder
{
    const ABOUT_MAX_LENGTH = 68;

    public static function create_favorite_button($label, $tags, $is_follow){

      $html = '<a class="mdl-button mdl-js-button border';
      if($is_follow) {
        $class = " mdl-button--colored";
        $button_label = qa_lang_html('cud_lang/following_label');
      } else {
        $class = "";
        $button_label = qa_lang_html('cud_lang/follow');
      }
      $html .= $class .'" title="' . $label . '"';
      $html .= $tags;
      $html .= ' >';
      $html .= ' <i class="material-icons">favorite</i>'.$button_label;
      $html .= '</a>';
      return $html;
    }

    public static function create_second_section($content, $handle){
      $template_path = CUD_DIR . '/html/second_section.html';
      $template = file_get_contents($template_path);

      $site_url = qa_opt('site_url');
      $col_num = 4;
      if (isset($content['counts']['badge'])) {
        $badge_count = self::create_badge_count($content['counts']['badge'], $handle);
      } else {
        $badge_count = '';
        $col_num = 6;
      }
      $params = array(
        '^follow_count_label' => qa_lang_html('cud_lang/follow_count'),
        '^follow_list_page_url' => qa_path('user/'.$handle.'/following', null, $site_url),
        '^follow_count' => $content['counts']['follows'],
        '^follower_count_label' => qa_lang_html('cud_lang/follower_count'),
        '^follower_list_page_url' => qa_path('user/'.$handle.'/followers', null, $site_url),
        '^follower_count' => $content['counts']['followers'],
        '^badge_count' => $badge_count,
        '^col_num' => $col_num,
      );

      return strtr($template, $params);
    }

    public static function create_tab_header($active_tab, $postcounts)
    {
        $template_path = CUD_DIR . '/html/main_tab_header.html';
        $template = file_get_contents($template_path);
        if(qa_opt('cud_opt_hide_blog')) {
          $blogs_tab = '';
          $blog_favorite_tab = '';
        } else {
          $blogs_active = $active_tab['blogs'];
          $blogs_count = $postcounts['blogs'];
          $blogs_label = qa_lang_html('cud_lang/blogs');
          $blogs_tab =<<<EOS
    <a class="mdl-tabs__tab $blogs_active" href="#blogs"  id="tab-blogs">
        <span class="mdl-badge" data-badge="$blogs_count">$blogs_label</span>
    </a>
EOS;
          $blog_favorites_active = $active_tab['blog-favorites'];
          $blog_favorites_count = $postcounts['blog_favorites'];
          $blog_favorites_label = qa_lang('cud_lang/blog_favorites_label');
          $blog_favorites_tab =<<<EOS2
    <a class="mdl-tabs__tab $blog_favorites_active" href="#blog-favorites" id="tab-blog-favorites">
        <span class="mdl-badge" data-badge="$blog_favorites_count">$blog_favorites_label</span>
    </a>
EOS2;
        }
        $params = array(
          '^questions_active' => $active_tab['questions'],
          '^questions_count' => $postcounts['questions'],
          '^questions_label' => qa_lang_html('cud_lang/questions'),
          '^answers_active' => $active_tab['answers'],
          '^answers_count' => $postcounts['answers'],
          '^answers_label' => qa_lang_html('cud_lang/answers'),
          '^favorites_active' => $active_tab['favorites'],
          '^favorites_count' => $postcounts['favorites'],
          '^favorites_label' => qa_lang('cud_lang/favorites_label'),
          '^blogs_tab' => $blogs_tab,
          '^blog_favorites_tab' => $blog_favorites_tab,
        );

        return strtr($template, $params);
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

    public static function create_confirm_dialog()
    {
      $template_path = CUD_DIR . '/html/favorite_confirm_dialog.html';
      $html = file_get_contents($template_path);
      return $html;
    }

    public static function create_mute_confirm_dialog()
    {
      $template_path = CUD_DIR . '/html/mute_confirm_dialog.html';
      $html = file_get_contents($template_path);
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
        $action_link = qa_opt('site_url') . 'how-to-use#answer';
      } elseif($list_type == 'blogs') {
        $action_link = qa_opt('site_url') . 'blog/new';
      } elseif($list_type == 'favorites') {
        $action_link = qa_opt('site_url') . 'help-general-browsing#bookmark';
      } elseif($list_type == 'blog-favorites') {
        $action_link = qa_opt('site_url') . 'help-general-browsing#bookmark';
      }

      $image_dir = qa_opt('site_url') . 'qa-plugin/'. CUD_FOLDER . '/image/';
      $params = array(
        '^content' => qa_opt('cud_opt_no_post_'.$list_type),
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
        '^site_url' => qa_opt('site_url'),
        '^message' => qa_lang_html_sub('cud_lang/no_posts', $list_name),
        '^image' => qa_opt('cud_opt_no_post_others_image')
      );
      $html = strtr($template, $params);
      return $html;
    }

    public static function create_follows_header($title)
    {
      $path = CUD_DIR . '/html/follows_main_header.html';
      $template = file_get_contents($path);
      $params = array(
        '^title' => $title
      );
      $html = strtr($template, $params);
      return $html;
    }

    public static function create_follows_footer()
    {
      $path = CUD_DIR . '/html/follows_main_footer.html';
      $template = file_get_contents($path);
      return $template;
    }

    public static function create_follows_tab($content)
    {
      $path = CUD_DIR . '/html/follows_main_tab.html';
      $template = file_get_contents($path);
      $following_active = '';
      $followers_active = '';
      if ($content['type'] === 'following') {
        $following_active = 'is-active qa-sub-nav-selected';
      } else {
        $followers_active = 'is-active qa-sub-nav-selected';
      }
      $params = array(
        '^following_count' => $content['users']['following_count'],
        '^followers_count' => $content['users']['followers_count'],
        '^following_text' => qa_lang_html('cud_lang/following'),
        '^followers_text' => qa_lang_html('cud_lang/followers'),
        '^following_active' => $following_active,
        '^followers_active' => $followers_active,
        '^users_list' => self::create_follows_list($content['users'], $content['type'])
      );
      $html = strtr($template, $params);
      return $html;
    }

    public static function create_follows_list($users, $type)
    {
        if (count($users['items']) <= 0) {
            return self::create_no_follows($type);
        }
        $path = CUD_DIR . '/html/follows_user_item.html';
        $template = file_get_contents($path);
        $html = '';
        foreach ($users['items'] as $user) {
            $params = self::create_follows_params($user);
            $html .= strtr($template, $params);
            $html .= PHP_EOL;
        }
        return $html;
    }

    public static function create_follows_params($user)
    {
        if (mb_strlen($user['about'], 'UTF-8') > self::ABOUT_MAX_LENGTH) {
            $about = mb_substr($user['about'], 0, self::ABOUT_MAX_LENGTH - 1, 'UTF-8');
            $about .= '<a href="'.$user['url'].'">...</a>';
        } else {
            $about = $user['about'];
        }
        return array(
            '^blobid' => $user['raw']['avatarblobid'],
            '^label' => $user['label'],
            '^location' => $user['location'],
            '^points' => $user['score'],
            '^about' =>  $about,
            '^url' => $user['url'],
            '^location_label' => qa_lang_html('cud_lang/location_label'),
        );
    }

    private static function create_no_follows($type)
    {
      $path = CUD_DIR . '/html/no_follows.html';
      $template = file_get_contents($path);

      $params = array(
        '^message' => qa_lang_html('cud_lang/no_'.$type),
        '^image' => qa_opt('site_url') . 'qa-plugin/'. CUD_FOLDER . '/image/no_item_icon.svg'
      );
      $html = strtr($template, $params);
      return $html;
    }

    private static function create_badge_count($count, $handle)
    {
      $label = qa_lang_html('cud_lang/badge_count');
      $url = qa_path('user/'.$handle.'/badge', null, qa_opt('site_url'));

      $path = CUD_DIR . '/html/second_section_badge.html';
      $template = file_get_contents($path);
      $params = array(
        '^label' => $label,
        '^url' => $url,
        '^count' => $count,
      );

      $html = strtr($template, $params);
      return $html;
    }
}
