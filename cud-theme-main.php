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

        $request = qa_request_parts();
        $handle = isset($request[1]) ? $request[1] : '';
        self::output_user_detail($theme_obj);

	// フォロー、フォロワーなどを表示
        $html = cud_html_builder::create_second_section($theme_obj->content, $handle);
        $theme_obj->output($html);

	// 投稿リスト
//        self::output_q_list_tab_header($theme_obj);
//        self::output_q_list_panels($theme_obj);


	$listUrl = 'https://preview.38qa.net/user/' . $handle  . '/posts';
	$params = $_GET;
	$query = http_build_query($params); 
	$iframeHtml =<<<EOS
<iframe
id="parentframe"
    width="100%"
    height="1000px"
    scrolling="yes"
    src="{$listUrl}?{$query}">
</iframe>
<script>
function setIFrameHeight(newHeight) {
var iframe = document.getElementById("parentframe");
iframe.style.height = newHeight + "px";
}
  window.addEventListener(
    "message",
    function (event) {
      var height = event.data.height;
      var url = event.data.url;
      var scroll = event.data.scroll;
      console.log("message received:" + height + " url:" + url);
      if(height){
      	setIFrameHeight(height + 300);
      }
      if (scroll) {
        window.scroll({
          top: 100,
          left: 0,
          behavior: "smooth",
        });
      }
      if(url){
        let pathname = window.location.pathname.replace('/posts', '');
	url = url.replace('/posts', '')

	let path = url.split("?")[0];  
	path = path.replace('/posts', ''); // preview.38qa.net側ではpostsが付いているので削除


// 	console.log('pathname' + pathname + ', request path ' + path);

	if(path == pathname){
		history.pushState({}, null, url);
		history.replaceState({}, null, url);
	}else{
		window.location.href = url;
	}
      }
    },
    false
  );
</script>
EOS;
	$theme_obj->output($iframeHtml);


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

         // 非公開日誌を表示する
                if(self::is_my_profile($theme_obj->content)) {
                    $loginuserid = qa_get_logged_in_userid();
                    $blogs_sel = qas_blog_db_posts_basic_selectspec( $loginuserid );

                    $blogs_sel['source'] .= " WHERE ^blogs.userid=" . $loginuserid;
                    $type = "type = 'D'";
                    $blogs_sel['source'] .= " AND ".$type;

                    $blogs = qa_db_select_with_pending($blogs_sel);

                    if(count($blogs) > 0) {

                        $theme_obj->output('<br/>以下の日誌は非公開となっています。サイト上に残す場合は公開に変更してください。非公開のままにする場合、24年1月以降に閲覧できなくなりますので、必要なものはWordなどにコピーしてください。<br>');
                        foreach($blogs as $blog) {
                            $theme_obj->output('・<a href="/blog/' .$blog["postid"] .  '">' . $blog["title"] . '</a><br>');
                        }

                    }

               }

        $content = $theme_obj->content;
        $raw = $content['raw'];
        $points = $raw['points']['points'];
        $points = $points ? number_format($points) : 0;
        $favorite = isset($content['favorite']) ? $content['favorite'] : null;
        $mute = isset($content['mute']) ? $content['mute'] : null;

        $site_url = qa_opt('site_url');
        $account_url = qa_path('account', null, $site_url);
        $blobid = $raw['account']['avatarblobid'];
        $handle = $raw['account']['handle'];
        $userid = $raw['account']['userid'];
        $login_userid = qa_get_logged_in_userid();
        $profile = $raw['newprofile'];
        $about = $raw['userabout']['value'];
        if (!empty($raw['about_post'])) {
            $show_detail_button = true;
            $about_post_url = qa_path($raw['about_post'], null, qa_opt('site_url'));
        } else {
            $show_detail_button = false;
            $about_post_url = '';
        }
        $points = qa_lang_html_sub('cud_lang/points',$points);
        $ranking = qa_lang_html_sub('cud_lang/ranking',$raw['rank']);
        $message_label = qa_lang_html('cud_lang/send_message');
        $message_url = qa_path_html('message/'.$handle);
        $usersource = $raw['usersource'];
        if (array_key_exists($userid, $theme_obj->ex_users)) {
            $ex_name = $theme_obj->ex_users[$userid];
            $ex_label = qa_lang_sub('cud_lang/ex_user_label', $ex_name);
            $ex_user =<<<EOS
<span class="mdl-typography--subhead mdl-color-text--primary ex-user">
  <i class="material-icons mr1">school</i>{$ex_label}
</span>
EOS;
        } else {
            $ex_user = '';
        }

        if(isset($favorite) && $favorite['favorite'] === 1) {
            $follow_message = qa_lang_html('cud_lang/unfollow');
        } else {
            $follow_message = qa_lang_html('cud_lang/follow');
        }
        $muting_users = $theme_obj->muting_users;
        $muting_users = qa_db_user_get_userid_handles($theme_obj->muting_users);
        $editurl = qa_path(qa_request(), array('state' => 'edit'), qa_opt('site_url'));
        include $path;
    }

    private static function output_q_list_tab_header($theme_obj)
    {
        $active_tab = self::set_active_tab($theme_obj);
        $counts = $theme_obj->content['counts'];
        $html = cud_html_builder::create_tab_header($active_tab, $counts);
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
        
        if(!qa_opt('cud_opt_hide_blog')) {
            self::output_q_list($theme_obj, 'blogs', $active_tab['blogs']);
        }
        self::output_q_list($theme_obj, 'answers', $active_tab['answers']);
        self::output_q_list($theme_obj, 'questions', $active_tab['questions']);
        self::output_q_list($theme_obj, 'favorites', $active_tab['favorites']);
        if(!qa_opt('cud_opt_hide_blog')) {
            self::output_q_list($theme_obj, 'blog-favorites', $active_tab['blog-favorites']);
        }
    }

    private static function output_q_list($theme_obj, $list_type, $active_tab)
    {
        $html = cud_html_builder::create_tab_panel($list_type, $active_tab);
        $theme_obj->output($html);
                // 投稿がある場合
        if (count($theme_obj->content['q_list'][$list_type]) > 0) {
            $q_items = $theme_obj->content['q_list'][$list_type];
            $theme_obj->q_list_items($q_items);
            if (isset($theme_obj->content['page_links_'.$list_type])) {
                self::page_links($theme_obj, $list_type);
            }

                        // 投稿がない場合
        } else {
                        // 自分のプロフィール
              $html = cud_html_builder::create_no_item_list($list_type, self::is_my_profile($theme_obj->content));
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
        $default = qa_opt('cud_opt_hide_blog') ? 'answers' : 'blogs';
        $action = isset($theme_obj->content['raw']['action']) ? $theme_obj->content['raw']['action'] : $default;
        $active_tab = array(
            // 'activities' => '',
            'questions' => '',
            'answers' => '',
            'blogs' => '',
            'favorites' => '',
            'blog-favorites' => '',
        );
        $active_tab[$action] = 'is-active';
        return $active_tab;
    }

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

        private static function is_my_profile($content) {
            return (qa_get_logged_in_userid() == $content['raw']['account']['userid']);
        }
}
