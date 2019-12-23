$(document).ready(function(){
  $('#tab-blogs').click(function(){
    addAction('blogs');
  });
  $('#tab-answers').click(function(){
    addAction('answers');
  })
  $('#tab-questions').click(function(){
    addAction('questions');
  })
  $('#tab-favorites').click(function(){
    addAction('favorites');
  })
  $('#tab-blog-favorites').click(function(){
    addAction('blog-favorites');
  })
});

const addAction = (action) => {
  history.replaceState('','','?action='+action)
}
