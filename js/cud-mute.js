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
    let f_flag = parseInt(ens[3]);
    let title, action;
    if (f_flag == 1) { //これからミュートする
      title = cud_lang.mute_title;
      action = cud_lang.mute_action;
      dialog.querySelector('#mute-confirm-title').innerHTML = title;
      dialog.querySelector('#mute-action').innerHTML = action;
      
      dialog.showModal();
    } else {
      post_mute(ens, code);
    }
  });

  function post_mute(ens, code) {
      var JSONdata = {
          entitytype: ens[1],
          entityid: ens[2],
          mute: ens[3],
          code: code,
      }; 
      $.ajax({
          url: '/user-mute',
          type: 'POST',
          dataType: 'json',
          cache : false,
          data: JSON.stringify(JSONdata)
      })
      .done(function(res) {
        console.log(res);
        location.reload();
      })
      .fail(function(res) {
          console.log(res);
      });
  }
});
