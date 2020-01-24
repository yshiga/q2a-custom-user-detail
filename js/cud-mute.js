$(document).ready(function(){
  let ens, code = '';
  const dialog = document.querySelector('#mute-confirm-dialog');
  if (!dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
  }
  dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
  });
  dialog.querySelector('#mute-action').addEventListener('click', function() {
    dialog.close();
    post_mute(ens, code);
  });
  $(document).on('click', "#mute-button", function(){
    ens = $(this).attr('name').split('_');
    code = $(this).data('code');
    console.info(ens);
    console.info(code);
    let f_flag = parseInt(ens[3]);
    let title, action;
    if (f_flag == 1) { //これからミュートする
      title = cud_lang.mute_title;
      action = cud_lang.mute_action;
    // } else {
    //   title = cud_lang.unfollow_title;
    //   action = cud_lang.unfollow_action;
    }
    dialog.querySelector('#mute-confirm-title').innerHTML = title;
    dialog.querySelector('#mute-action').innerHTML = action;
    
    dialog.showModal();
  });

  function post_mute(ens, code) {
    qa_ajax_post('favorite', {entitytype:ens[1], entityid:ens[2], favorite:parseInt(ens[3]), code:code},
      function (lines) {
        if (lines[0]=='1') {
          console.info(lines.slice(1));
          // qa_set_inner_html(document.getElementById('favoriting'), 'favoriting', lines.slice(1).join("\n"));
          // componentHandler.upgradeDom();
        } else if (lines[0]=='0') {
          console.log(lines[1]);
        } else
          qa_ajax_error();
      }
    );
  }
});
