/*
Plugin Name: Yummly Rich Recipes
Plugin URI: http://plugin.yummly.com
Description: A plugin that adds all the necessary microdata to your recipes, so they will show up in Google's Recipe Search
Version: 4.2
Author: Yummly
Author URI: http://www.yummly.com/
License: GPLv3 or later

Copyright 2009-2016 Yummly
This code is derived from the 1.3.1 build of RecipeSEO released by codeswan: http://sushiday.com/recipe-seo-plugin/
*/

/*
    This file is part of Yummly Rich Recipes.

    Yummly Rich Recipes is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Yummly Rich Recipes is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Yummly Rich Recipes. If not, see <http://www.gnu.org/licenses/>.
*/

(function() {

	tinymce.create('tinymce.plugins.amdEditYRecipe', {
		init: function( editor, url ) {
			var t = this;
			t.url = url;

			//replace shortcode before editor content set
			editor.onBeforeSetContent.add(function(ed, o) {
				o.content = t.y_convert_codes_to_imgs(o.content);
			});

			/* FIXME
			editor.on('BeforeSetcontent', function(event){
				//console.log(event);
				event.content = t.y_convert_codes_to_imgs(event.content);
				//console.log('post');
			});
			*/

			//replace shortcode as its inserted into editor (which uses the exec command)
			editor.onExecCommand.add(function(ed, cmd) {
				if (cmd ==='mceInsertContent') {
					var bm = tinyMCE.activeEditor.selection.getBookmark();
					tinyMCE.activeEditor.setContent( t.y_convert_codes_to_imgs(tinyMCE.activeEditor.getContent()) );
					tinyMCE.activeEditor.selection.moveToBookmark(bm);
				}
			});

			/* FIXME
			editor.on('ExecCommand', function(e) {
				console.log('ExecCommand event', e);
				something happens
			});
			*/

			//replace the image back to shortcode on save
			editor.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = t.y_convert_imgs_to_codes(o.content);
			});

			editor.addButton( 'amdyrecipe', {
				title: 'Yummly Recipe',
				image: yummly_plugindir + '/images/yrecipe.png',
				onclick: function() {
					var recipe_id = null;
					if (recipe = editor.dom.select('img.amd-yrecipe-recipe')[0]) {
						editor.selection.select(recipe);
						recipe_id = /amd-yrecipe-recipe-([0-9]+)/i.exec(editor.selection.getNode().id);
					}
					var iframe_url = baseurl + '/wp-admin/media-upload.php?post_id=' + ((recipe_id) ? '1-' + recipe_id[1] : post_id) + '&type=amd_yrecipe&tab=amd_yrecipe&TB_iframe=true&width=640&height=555';
					editor.windowManager.open( {
						title: 'Yummly Rich Recipes',
						url: iframe_url,
						width: 653,
						height: 632,
						scrollbars : "yes",
						inline : 1,
						onsubmit: function( e ) {
							editor.insertContent( '<h3>' + e.data.title + '</h3>');
						}
					});
				}
			});
    	},

		y_convert_codes_to_imgs : function(co) {
            return co.replace(/\[amd-yrecipe-recipe:([0-9]+)\]/g, function(a, b) {
								return '<img id="amd-yrecipe-recipe-'+b+'" class="amd-yrecipe-recipe" src="' + yummly_plugindir + '/images/yrecipe-placeholder.png" alt="" />';
            });
		},

		y_convert_imgs_to_codes : function(co) {
			return co.replace(/\<img[^>]*?\sid="amd-yrecipe-recipe-([0-9]+)[^>]*?\>/g, function(a, b){
                return '[amd-yrecipe-recipe:'+b+']';
            });
		},

		getInfo : function() {
            return {
                longname : "Yummly Rich Recipes",
                author : 'Yummly',
                authorurl : 'http://www.yummly.com/',
                infourl : 'http://plugin.yummly.com',
                version : "4.2"
            };
        }
	});

	tinymce.PluginManager.add('amdyrecipe', tinymce.plugins.amdEditYRecipe);

})();
