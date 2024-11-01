(function () {
    tinymce.create('tinymce.plugins.wpfaq', {

        init:function (ed, url) {
            ed.addButton('wpfaq', {
                title: 'wpfaq',
                //image: url + '/../images/tinymce-icon-inactive.png',
                onclick: function () {
                    ed.execCommand('mceInsertContent', false, '[wpfaq]');
                }
            });
        },

        createControl:function (n, cm) {
            return null;
        },

        getInfo:function () {
            return {
                longname: 'WP-FAQ',
                author: 'Convert To Wordpress',
                authorurl: 'http://converttowordpress.com/',
                infourl: 'http://converttowordpress.com/',
                version:"1.0"
            };
        }

    });
    tinymce.PluginManager.add('wpfaq', tinymce.plugins.wpfaq);
})();