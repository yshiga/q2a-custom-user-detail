$(document).ready(function(){
  $("#favoriting a").click(function(){
    var ens = $(this).data('name').split('_');
    var code = $(this).data('code');
    
    qa_ajax_post('favorite', {entitytype:ens[1], entityid:ens[2], favorite:parseInt(ens[3]), code:code},
      function (lines) {
        if (lines[0]=='1') {
          fav = parseInt(ens[3]);
          console.log(fav);
          if (fav == 1) {
            new_name = ens[0]+'_'+ens[1]+'_'+ens[2]+'_0';
          } else {
            new_name = ens[0]+'_'+ens[1]+'_'+ens[2]+'_1';
          }
          $(this).data('name', new_name);
          console.log($(this).data('name'));
        } else if (lines[0]=='0') {
          console.log(lines[1]);
        } else
          qa_ajax_error();
      }
    );
  });
});
