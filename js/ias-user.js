$(function(){
  var ias_list = {};
  
  ias_list['activities'] = bind_ias('activities');
  console.log(ias_list);
  $('#tab-activities').click(function(){
    destroy_ias();
    if (ias_list['activities']) {
      var ias = ias_list['activities'];
      ias.bind();
    } else {
      ias_list['activities'] = bind_ias('activities');
    }
    console.log(ias_list);
  });
  $('#tab-questions').click(function(){
    destroy_ias();
    if (ias_list['questions']) {
      var ias = ias_list['questions'];
      ias.bind();
    } else {
      ias_list['questions'] = bind_ias('questions');
    }
    console.log(ias_list);
  });
  $('#tab-answers').click(function(){
    destroy_ias();
    if (ias_list['answers']) {
      var ias = ias_list['answers'];
      ias.bind();
    } else {
      ias_list['answers'] = bind_ias('answers');
    }
    console.log(ias_list);
  });
  $('#tab-blogs').click(function(){
    destroy_ias();
    if (ias_list['blogs']) {
    } else {
      ias_list['blogs'] = bind_ias('blogs');
    }
    console.log(ias_list);
  });
  
  function bind_ias(list_type) {
    if($("#"+list_type).length && $(".qa-page-links-"+list_type).length) {
      $('.ias-spinner').hide();
      var ias = $(".mdl-layout__content").ias({
        container: "#"+list_type
        ,item: ".qa-q-list-item"
        ,pagination: ".qa-page-links-"+list_type
        ,next: ".qa-page-links-"+list_type+" .qa-page-next"
        ,delay: 600
      });
      ias.extension(new IASTriggerExtension({
          text: "続きを読む",
          textPrev: "前を読む",
          offset: 100,
      }));
      ias.extension(new IASNoneLeftExtension({
        html: '', // optionally
      }));
      ias.on('load', function() {
        $('.ias-spinner').show();
      });
      ias.on('noneLeft', function() {
        $('.ias-spinner').hide();
      });
      return ias;
    }
    return null;
  }
  
  function destroy_ias() {
    for (type in ias_list) {
      var ias = ias_list[type];
      ias.destroy();
    }
    $('.ias-spinner').hide();
  }
});
