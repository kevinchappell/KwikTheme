(function() {

  tinymce.create('tinymce.plugins.addPullQuote', {
    init: function(ed, url) {
      ed.addCommand('addPullQuoteCallout', function() {
        ed.windowManager.open({
          file: ajaxurl + '?action=add_pull_quote_function_callback',
          width: 350 + parseInt(ed.getLang('mytest.delta_width', 0)),
          height: 250 + parseInt(ed.getLang('mytest.delta_height', 0)),
          inline: 1
        }, {
          plugin_url: url
        });
      });
      ed.addButton('addPullQuote', {
        title: 'Add a pull-quote',
        cmd: 'addPullQuoteCallout',
        image: url + '/../images/icons/pull_quote.gif'
      });
    },
  });
  tinymce.PluginManager.add('addPullQuote', tinymce.plugins.addPullQuote);
})();
