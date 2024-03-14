function tinyplugin() {
return "[doublerainbow-plugin]";
}
(function() {
tinymce.create('tinymce.plugins.kentotestimonial_button_plugin', {
init : function(ed, url){
ed.addButton('kentotestimonial_button_plugin', {
title : 'Add Kento Testimonial Slider',
onclick : function() {
var ed = tinyMCE.activeEditor;
ed.focus();
var sel = ed.selection;
var content = sel.getContent();
content='[KentoTestimonial]';
sel.setContent(content);
},
image: url + "/testimonial.png"
});
},

});
tinymce.PluginManager.add('kentotestimonial_button_plugin', tinymce.plugins.kentotestimonial_button_plugin);
})();

