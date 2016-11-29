<?php 

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

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

        self::output_user_detail($theme_obj);

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
        $buttons = self::create_buttons($raw['account']['userid']);
        return array(
            '^site_url' => qa_opt('site_url'),
            '^blobid' => $raw['account']['avatarblobid'],
            '^handle' => $raw['account']['handle'],
            '^location' => $raw['profile']['location'],
            '^groups' => $raw['profile']['飼-育-群-数'],
            '^years' => $raw['profile']['ニホンミツバチ-飼-育-歴'],
            '^hivetype' => $raw['profile']['使-用-している-巣-箱'],
            '^about' => $raw['profile']['about'],
            '^points' => $points,
            '^ranking' => $raw['rank'],
            '^buttons' => $buttons,
        );
    }
}
