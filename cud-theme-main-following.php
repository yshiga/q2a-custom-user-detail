<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

require_once CUD_DIR.'/cud-html-builder.php';
require_once CUD_DIR.'/cud-utils.php';

class cud_theme_main_following
{
    protected $context = array();

    public static function main($theme_obj)
    {
        $html =<<<EOF
        <div class="centering__width-640">
        <h2 class="mdl-typography--subhead">○○○○さんのフォロー・フォロワー一覧</h2>
        <div class="mdl-tabs is-upgraded mdl-js-tabs mdl-js-ripple-effect margin--top-16px">
          <div class="mdl-tabs__tab-bar mdl-color--white">
            <a href="#my-favorire" class="mdl-tabs__tab is-active"><span class="mdl-badge" data-badge="3">お気に入り</span></a>
            <a href="#in-favorite" class="mdl-tabs__tab"><span class="mdl-badge" data-badge="3">お気に入られ</span></a>
          </div>
          <div class="mdl-tabs__panel is-active" id="my-favorite">
            <div class="users-list">
              <div class="mdl-cell mdl-cell--12-col user-item">
                <div class="qa-part-ranking centering__width-640">
                  <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
                    <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E" style="background: url(./?qa=image&amp;qa_blobid=13760747793282943206&amp;qa_size=130) no-repeat center center #000"></a>
                    <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                      <span class="mdl-list__item-primary-content">
                <div><a href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E" class="qa-user-link">ハッチ＠宮崎</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 宮崎県南部
                      </span>
                      <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 142,780
                  </span>
                    </div>
                    <span class="mdl-list__item-text-body">昭和５９年１０月１２日、人家の壁内に営巣していた日本みつばち群をラングストロス（巣枠入り）巣箱に収容して以来、飼育を継続しています。翌<a href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E">...</a>
                </span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
      
            <div class="mdl-cell mdl-cell--12-col user-item">
              <div class="qa-part-ranking centering__width-640">
                <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
                  <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA" style="background: url(./?qa=image&amp;qa_blobid=14655409908109240942&amp;qa_size=130) no-repeat center center #000"></a>
                  <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <span class="mdl-list__item-primary-content">
                <div><a href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA" class="qa-user-link">管理人</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 京都
                    </span>
                    <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 56,640
                  </span>
                  </div>
                  <span class="mdl-list__item-text-body">このホームページの管理用アカウントです。約10年にわたり初心者のサポートをしています。
      このサイトの運営・システム開発の他、ニホンミツ<a href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA">...</a>
                </span>
                  </span>
                </div>
              </div>
            </div>
          </div>
      
          <div class="mdl-cell mdl-cell--12-col user-item">
            <div class="qa-part-ranking centering__width-640">
              <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
                <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/onigawara" style="background: url(./?qa=image&amp;qa_blobid=6792723924238261380&amp;qa_size=130) no-repeat center center #000"></a>
                <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                  <span class="mdl-list__item-primary-content">
                <div><a href="./user/onigawara" class="qa-user-link">onigawara</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 福岡県朝倉市
                  </span>
                  <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 52,150
                  </span>
                </div>
                <span class="mdl-list__item-text-body">日本ミツバチは森林組合の友人から2012年巣蜜をいただいたのと山荘の薪小屋に日本ミツバチがたくさん来ていたので何か自分でも知らないうち<a href="./user/onigawara">...</a>
                </span>
                </span>
              </div>
            </div>
          </div>
        </div>
      
        <div class="mdl-tabs__panel " id="in-favorite">
          <div class="users-list">
            <div class="mdl-cell mdl-cell--12-col user-item">
              <div class="qa-part-ranking centering__width-640">
                <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
                  <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E" style="background: url(./?qa=image&amp;qa_blobid=13760747793282943206&amp;qa_size=130) no-repeat center center #000"></a>
                  <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <span class="mdl-list__item-primary-content">
                <div><a href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E" class="qa-user-link">ハッチ＠宮崎</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 宮崎県南部
                    </span>
                    <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 142,780
                  </span>
                  </div>
                  <span class="mdl-list__item-text-body">昭和５９年１０月１２日、人家の壁内に営巣していた日本みつばち群をラングストロス（巣枠入り）巣箱に収容して以来、飼育を継続しています。翌<a href="./user/%E3%83%8F%E3%83%83%E3%83%81%EF%BC%A0%E5%AE%AE%E5%B4%8E">...</a>
                </span>
                  </span>
                </div>
              </div>
            </div>
          </div>
      
          <div class="mdl-cell mdl-cell--12-col user-item">
            <div class="qa-part-ranking centering__width-640">
              <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
                <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA" style="background: url(./?qa=image&amp;qa_blobid=14655409908109240942&amp;qa_size=130) no-repeat center center #000"></a>
                <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                  <span class="mdl-list__item-primary-content">
                <div><a href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA" class="qa-user-link">管理人</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 京都
                  </span>
                  <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 56,640
                  </span>
                </div>
                <span class="mdl-list__item-text-body">このホームページの管理用アカウントです。約10年にわたり初心者のサポートをしています。
      このサイトの運営・システム開発の他、ニホンミツ<a href="./user/%E7%AE%A1%E7%90%86%E4%BA%BA">...</a>
                </span>
                </span>
              </div>
            </div>
          </div>
        </div>
      
        <div class="mdl-cell mdl-cell--12-col user-item">
          <div class="qa-part-ranking centering__width-640">
            <div class="mdl-grid mdl-grid--no-spacing mdl-color--white mdl-shadow--2dp margin--top-16px" style="position:relative">
              <a class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone" href="./user/onigawara" style="background: url(./?qa=image&amp;qa_blobid=6792723924238261380&amp;qa_size=130) no-repeat center center #000"></a>
              <div class="mdl-list__item mdl-list__item--three-line mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                <span class="mdl-list__item-primary-content">
                <div><a href="./user/onigawara" class="qa-user-link">onigawara</a>
                  <span class="mdl-chip__text">
                    <span class="mdl-typography--font-bold">　活動場所</span> 福岡県朝倉市
                </span>
                <span class="mdl-color-text--primary" style="float:right;margin-right:12px">
                    <i class="fa fa-trophy"></i> 52,150
                  </span>
              </div>
              <span class="mdl-list__item-text-body">日本ミツバチは森林組合の友人から2012年巣蜜をいただいたのと山荘の薪小屋に日本ミツバチがたくさん来ていたので何か自分でも知らないうち<a href="./user/onigawara">...</a>
                </span>
              </span>
            </div>
          </div>
        </div>
      </div>
      </div>
      </div>
      
      <div class="qa-page-links margin--top-16px">
        <nav class="qa-page-links-list">
          <a class="qa-page-selected is-activemdl-js-ripple-effect mdl-button mdl-js-button mdl-button--raised  mdl-button--colored mdl-color-text--white" data-upgraded=",MaterialButton">1</a>
          <a href="./tags?start=30" class="qa-page-link mdl-js-ripple-effect mdl-button mdl-js-button" data-upgraded=",MaterialButton,MaterialRipple">2<span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></a>
      
          <a href="./tags?start=30" class="qa-page-next mdl-js-ripple-effect mdl-button mdl-js-button" data-upgraded=",MaterialButton,MaterialRipple"><i class="material-icons">chevron_right</i><span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></a>
        </nav>
        <div class="qa-page-links-clear">
        </div>
      </div>
      
      </div>
      </div>
      </div>
      </div>
EOF;
        $theme_obj->output($html);
    }

}
