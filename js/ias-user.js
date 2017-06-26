$(function(){
  var ias_list = {};
  
  if (!action) {
    action = 'activities';
  }
  ias_list[action] = bind_ias(action);
  
  $('#tab-activities').click(function(){
    destroy_ias();
    if (ias_list['activities']) {
      var ias = ias_list['activities'];
      ias.bind();
    } else {
      ias_list['activities'] = bind_ias('activities');
    }
  });
  $('#tab-questions').click(function(){
    destroy_ias();
    if (ias_list['questions']) {
      var ias = ias_list['questions'];
      ias.bind();
    } else {
      ias_list['questions'] = bind_ias('questions');
    }
  });
  $('#tab-answers').click(function(){
    destroy_ias();
    if (ias_list['answers']) {
      var ias = ias_list['answers'];
      ias.bind();
    } else {
      ias_list['answers'] = bind_ias('answers');
    }
  });
  $('#tab-blogs').click(function(){
    destroy_ias();
    if (ias_list['blogs']) {
    } else {
      ias_list['blogs'] = bind_ias('blogs');
    }
  });
  
  function bind_ias(list_type) {
    $('.ias-spinner').hide();
    if($("#"+list_type).length && $(".qa-page-links-"+list_type).length) {
      var ias = $(".mdl-layout__content").ias({
        container: "#"+list_type
        ,item: ".qa-q-list-item"
        ,pagination: ".qa-page-links-"+list_type
        ,next: ".qa-page-links-"+list_type+" .qa-page-next"
        ,delay: 600
      });
      ias.extension(new IASTriggerExtension({
          text: cud_lang.read_next,
          textPrev: cud_lang.read_previous,
          offset: 100,
      }));
      ias.extension(new IASNoneLeftExtension({
        html: '', // optionally
      }));
      ias.on('load', function() {
        $('.q-list-'+list_type+' .ias-spinner').show();
      });
      ias.on('noneLeft', function() {
        $('.q-list-'+list_type+' .ias-spinner').hide();
      });
      return ias;
    }
    return null;
  }
  
  function destroy_ias() {
    for (type in ias_list) {
      var ias = ias_list[type];
      if(ias) {
          ias.destroy();
      }
    }
    $('.ias-spinner').hide();
  }
});
