/**
 * Blog Designer Block
 */
(function(blocks,editor,components,i18n,element){
	var el = element.createElement;
	blocks.registerBlockType('blog-designer/blog-designer-block',{title:i18n.__('Blog Designer'),description:i18n.__('Display Blog Designer Posts'),icon:'universal-access-alt',category:'layout',edit:function(){return el('p',{},'[wp_blog_designer]');},save:function(props){return null}},)
}(window.wp.blocks,window.wp.editor,window.wp.components,window.wp.i18n,window.wp.element));
