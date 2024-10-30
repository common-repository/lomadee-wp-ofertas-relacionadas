// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins
(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('lomadeewpro');
	 
	tinymce.create('tinymce.plugins.lomadeewpro', {
                
		init : function(ed, url) {
                
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('lomadeewpro', function() {
				ed.windowManager.open({
					file   : url + '../../../lomadee-wp-ofertas-relacionadas-window.php',
					width  : 460,
					height : 210,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('lomadeewpro', {
				title : 'Lomadee WP - Ofertas Relacionadas',
				cmd : 'lomadeewpro',
				image : url + '../../images/lomadee-icon-editor.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('lomadeewpro', n.nodeName == 'IMG');
			});
                        
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
                                    longname  : 'Lomadee WP - Produtos Relacionados',
                                    author    : 'Apiki WordPress',
                                    authorurl : 'http://www.apiki.com',
                                    infourl   : 'http://www.apiki.com',
                                    version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add( 'lomadeewpro', tinymce.plugins.lomadeewpro );
})();