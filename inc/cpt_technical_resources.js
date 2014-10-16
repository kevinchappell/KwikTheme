jQuery(document).ready(function ($) {

  function client_autocomplete() {
    $('#tr_meta #client_link_title').autocomplete({
      delay: 333,
      source: $('#wp-admin-bar-site-name a').attr('href') + "wp-content/themes/KwikTheme/utils/get_posts.php?type=clients",
      select: function (event, ui) {
        var element = $(this);
        element.siblings('#client_link_id').val(ui.item.id);
        element.siblings('label.client_thumb').html($('<img/>', {'src':ui.item.thumbnail[0]}));
      },
      minLength: 3,
      messages: {
        noResults: null,
        results: function () {}
      }
    });
  }

  $('.remove_client_link').click(function(evt){
    $(this).siblings('input').val('');
    $(this).siblings('.client_thumb').empty();

  });

  client_autocomplete();

});
