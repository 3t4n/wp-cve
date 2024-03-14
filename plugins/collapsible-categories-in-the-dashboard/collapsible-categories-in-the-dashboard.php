<?php
/*
Plugin Name: Collapsible Categories in the Dashboard
Version: 1.1
Description: Collapses sub categories into hidden submenus that can be expanded and collapsed.  Keeps selected categories visible.
Author: newvibe
Author URI: http://newvibewebdesign.com
Plugin URI: https://www.newvibewebdesign.com/collapsible-categories-in-admin-for-wordpress/
*/

class Category_Checklist {

	static function init() {
		add_filter( 'wp_terms_checklist_args', array( __CLASS__, 'checklist_args' ) );
	}

	static function checklist_args( $args ) {
		add_action( 'admin_footer', array( __CLASS__, 'script_css' ) );

		$args['checked_ontop'] = false;

		return $args;
	}

	// Scrolls to first checked category and collapses subcategories into a hidden submenu
	static function script_css() {
?>
<script type="text/javascript">
	(function($){
		$('[id$="-all"] > ul.categorychecklist').each(function() {
			var $list = $(this);
			var $firstChecked = $list.find(':checkbox:checked').first();

			if ( !$firstChecked.length )
				return;

			var pos_first = $list.find(':checkbox').position().top;
			var pos_checked = $firstChecked.position().top;

			$list.closest('.tabs-panel').scrollTop(pos_checked - pos_first + 5);
		});
		
		$('body #category-all:nth-child(2)').remove();
		$('.categorychecklist li').each(function(){
			if($(this).children('.children').length!=0){
				if($(this).find('input:checked').length==0){
					$(this).children('.children').slideToggle('fast',function(){});
					$(this).children('label').before(' <a class="show">[+]</a><a class="collapse" style="display:none;">[-]</a>');
				} else {
					$(this).children('label').before(' <a style="display:none;" class="show">[+]</a><a class="collapse" >[-]</a>');
				}
			}
		});
		$('.categorychecklist li a.collapse').click(function(){
			$(this).parent().children('.children').slideToggle('fast',function(){
				$(this).parent().children('.show').css("display","inline");
				$(this).parent().children('.collapse').css("display","none");
			});
		});
		$('.categorychecklist li a.show').click(function(){
			$(this).parent().children('.children').slideToggle('fast',function(){
				$(this).parent().children('.collapse').css("display","inline");
				$(this).parent().children('.show').css("display","none");
			});
		});
	})(jQuery);
</script>
<style>
	#categorydiv div.tabs-panel, #product_catdiv div.tabs-panel { 
		height:auto;
		max-height:none; 
	}
	a.show, a.collapse {
		display:inline-block;
		margin-right:4px;
		cursor:pointer;	
	}
</style>
<?php
	}
}

Category_Checklist::init();

