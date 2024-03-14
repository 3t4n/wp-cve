/*
 Football Pool WordPress plugin

 @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 @link https://wordpress.org/plugins/football-pool/
 @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
*/
tinymce.PluginManager.add("footballpool",function(b,c){function d(a,e){a*=.8;return a>e?e:a}b.addButton("footballpool",{icon:!1,image:c+"/footballpool-tinymce-16.png",tooltip:FootballPoolTinyMCE.tooltip,onclick:function(){b.windowManager.open({title:FootballPoolTinyMCE.text,url:c+"/tinymce-dialog.php",width:d(jQuery(window).width(),600),height:d(jQuery(window).height(),350),buttons:[{text:FootballPoolTinyMCE.button_text,classes:"primary",onclick:function(a){a=tinymce.activeEditor.get_shortcode();
tinymce.activeEditor.execCommand("mceInsertContent",!1,a);top.tinymce.activeEditor.windowManager.close()}},{text:FootballPoolAjax.cancel_button,onclick:"close"}]})}});return{getMetadata:function(){return{title:"Football Pool shortcodes",url:"https://wordpress.org/plugins/football-pool/"}}}});
