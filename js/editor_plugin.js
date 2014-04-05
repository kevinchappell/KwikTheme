(function() {

    tinymce.create('tinymce.plugins.addservicebox', {

        init : function(ed, url) {

            ed.addCommand('addServiceBoxCallout', function() {
                ed.windowManager.open({
                    file : ajaxurl + '?action=add_service_box_function_callback',
                    width : 350 + parseInt(ed.getLang('mytest.delta_width', 0)),
                    height : 250 + parseInt(ed.getLang('mytest.delta_height', 0)),
                    inline : 1
                }, {

                    plugin_url : url

                });

            });

            ed.addButton('addservicebox', {
                title : 'Add a box like on the Services page...',
                cmd : 'addServiceBoxCallout',
                image : url+'/../images/icons/service_box.gif'
            });



        },

    });

    tinymce.PluginManager.add('addservicebox', tinymce.plugins.addservicebox);
	
	
})();