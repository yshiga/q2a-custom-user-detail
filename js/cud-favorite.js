$(document).ready(function(){
  var ens, code;
  var dialog = document.querySelector('#favorite-confirm-dialog');
  if (!dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
  }
  dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
  });
  dialog.querySelector('#favorite-action').addEventListener('click', function() {
    dialog.close();
    post_favorite(ens, code);
  });
  $(document).on('click', "#favoriting a", function(){
    ens = $(this).attr('name').split('_');
    code = $(this).parent().data('code');
    var f_flag = parseInt(ens[3]);
    var title, content, label;
    if (f_flag == 1) { //これからフォローする
      title = cud_lang.follow_title;
      content = cud_lang.follow_content;
      action = cud_lang.follow_action;
    } else {
      title = cud_lang.unfollow_title;
      content = cud_lang.unfollow_content;
      action = cud_lang.unfollow_action;
    }
    dialog.querySelector('#favorite-confirm-title').innerHTML = title;
    dialog.querySelector('#favorite-confirm-content').innerHTML = content;
    dialog.querySelector('#favorite-action').innerHTML = action;
    
    dialog.showModal();
  });
  function post_favorite(ens, code) {
    qa_ajax_post('favorite', {entitytype:ens[1], entityid:ens[2], favorite:parseInt(ens[3]), code:code},
      function (lines) {
        if (lines[0]=='1') {
          qa_set_inner_html(document.getElementById('favoriting'), 'favoriting', lines.slice(1).join("\n"));
          componentHandler.upgradeDom();
        } else if (lines[0]=='0') {
          console.log(lines[1]);
        } else
          qa_ajax_error();
      }
    );

  }
});
