<?php
/**
 * Page Links Options page
 *
 * @package Page_Links
 * @subpackage Options
 * @version $Id$
 */
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die(__('You are not allowed to call this page directly.', SH_PAGE_LINKS_DOMAIN));

    $sections_array = $this->get_sections();
    $sections = array_keys($sections_array);

    $messages    = get_settings_errors();
    $current_tab = '';

    if (!empty($messages[0]['message'])) {
        $current_tab = $messages[0]['message'];
	}
	
?>
<style type="text/css">
    #icon-sh-page-links-options {
        background-image: url('<?php echo SH_PAGE_LINKS_URL ?>/images/logo-32x32.png');
    }
</style>
<script type="text/javascript">
	 
    jQuery(function($){

    	$('#toplevel_page_sh-page-links-options ul li.wp-first-item a').attr('href', $('#toplevel_page_sh-page-links-options ul li.wp-first-item a').attr('href')+'#single_view');

        $('.tabs').tabs({
            select : function(event, ui) {
                var $panel = $(ui.panel),
                    $input = $('#input-current-tab'),
                    currentPanel = $panel.attr('id');

                $input.val(currentPanel);
            }
        });
		
		$(window).bind('hashchange', function () { //detect hash change
			var hash = window.location.hash.slice(1); //hash to string (= "myanchor")
			$('.nav-tab-wrapper li[aria-controls="'+hash+'"] a').click();
		});
    })

	jQuery(document).ready(function($) {

		<?php
		if ($current_tab != "") {
			echo '$("#tab-'. $current_tab .'").click();';
			echo '$("#input-current-tab").val("'. $current_tab .'");';
		} else {
			?>
			if(window.location.hash) {
				str = window.location.hash;
				str = str.replace("#","");
				$("#input-current-tab").val(str);
			}
			<?php
		}
		?>

		$("#break-type").change( function() {
			if ($(this).val()==0)
				$("#paragraph-count").val('3');
			else if ($(this).val()==1)
				$("#paragraph-count").val('2');
			else if ($(this).val()==2)
				$("#paragraph-count").val('50');
		});

		$(".ui-tabs-nav .nav-tab a").click( function() {
			$("#input-current-tab").val($("li.ui-tabs-active").attr('aria-controls'));
		});

		$("#sh_pagelinks_options_form").submit( function(e) {

			if ($("#break-type").length>0) {

				if (  $("#break-type").val()==0 && $("#paragraph-count").val() < 3)
					$("#paragraph-count").val('3');
				else if ($("#break-type").val()==1 && $("#paragraph-count").val() < 2)
					$("#paragraph-count").val('2');
				else if ($("#break-type").val()==2 && $("#paragraph-count").val() < 50)
					$("#paragraph-count").val('50');
			}
			if ($("#use-ajax").val()!="0") {
				if ($("#wrapper-tag").val()=="")
					$("#wrapper-tag").val('div');
				if ($("#wrapper-id").val()=="")
					$("#wrapper-id").val('post-pagination');
			}

			return true;

		});

  		$("#restorevalue").click(function() {
			currentPanel = $(".ui-tabs-nav li.ui-tabs-active a").attr("href");
			if (currentPanel == "#single_view") {
				$("#text-single-link").val("<?php _e("Single Page", SH_PAGE_LINKS_DOMAIN); ?>");
				$('.enabled_posts').each( function() {
					if ( $(this).val()=="post" | $(this).val()=="page")
						$(this).attr("checked",true);
					else
						$(this).attr("checked",false);
				});
			} else if (currentPanel == "#pagination_styles") {
				$("#use-ajax").val('0');
				$('#before-content').val('<p>Pages:');
				$('#after-content').val('</p>');
				$('#link-before').val('');
				$('#link-after').val('');
				$('#pagelink').val('%page%');
				$('#echo-tag').val('1');
				$('#seperator').val('|');
				$('#wrapper-tag').val('div');
				$('#wrapper-id').val('post-pagination');
				$('#wrapper-class').val('page-link');
				$("#link-wrapper").val('span');
				$("#link-wrapper-class").val('');
			} else if (currentPanel == "#auto_pagination") {
				$("#break-type").val(0);
				$("#paragraph-count").val(3);
				$("#inline-nextpage").attr('checked', false);
			} else if (currentPanel == "#scrolling_pagination") {
				$("#pages-to-scroll-count").val(3);
				$("#nextpagelink").val('<?php _e("Next", SH_PAGE_LINKS_DOMAIN); ?>');
				$("#previouspagelink").val('<?php _e("Previous", SH_PAGE_LINKS_DOMAIN); ?>');
				$("#firstpage").val('<?php _e("First", SH_PAGE_LINKS_DOMAIN); ?>');
				$("#lastpage").val('<?php _e("Last", SH_PAGE_LINKS_DOMAIN); ?>');
				$("#elipsis").val('. . .');
			}
		});

		var before = jQuery("#before-content").val();
		var wraptag = jQuery("#wrapper-tag").val();
		var wrapid = jQuery("#wrapper-id").val();
		//var wrapid = wrapid.replace(",", " ");
		var wrapclass = jQuery("#wrapper-class").val();
		var wrapclass = wrapclass.replace(",", " ");
		var linkwrap = jQuery("#link-wrapper").val();
		var linkwrapclass = jQuery("#link-wrapper-class").val();
		var nxtpaglink = jQuery("#nextpagelink").val();
		var elipsis = jQuery("#elipsis").val();
		var prevpagelink = jQuery("#previouspagelink").val();
		var firstpage = jQuery("#firstpage").val();
		var lastpage = jQuery("#lastpage").val();
		var sepval = jQuery("#seperator").val();
		
		//jQuery("#before-content-1").html(before);
		jQuery("#wrapper-tag-1").html(wraptag);
		jQuery("#wrapper-tag-2").html(wraptag);
		jQuery("#wrapper-id-1").html(wrapid);
		jQuery("#wrapper-class-1").html(wrapclass);
		jQuery("#link-wrapper-1").html(linkwrap);
		jQuery("#link-wrapper-2").html(linkwrap);
		jQuery("#link-wrapper-3").html(linkwrap);
		jQuery("#link-wrapper-4").html(linkwrap);
		jQuery("#link-wrapper-5").html(linkwrap);
		jQuery("#link-wrapper-6").html(linkwrap);
		jQuery("#link-wrapper-7").html(linkwrap);
		jQuery("#link-wrapper-8").html(linkwrap);
		jQuery("#link-wrapper-9").html(linkwrap);
		jQuery("#link-wrapper-10").html(linkwrap);
		jQuery("#link-wrapper-11").html(linkwrap);
		jQuery("#link-wrapper-12").html(linkwrap);
		jQuery("#link-wrapper-13").html(linkwrap);
		jQuery("#link-wrapper-14").html(linkwrap);
		/* =============== link wrapp class ================ */
		jQuery("#link-wrapper-class-1").html(linkwrapclass);
		jQuery("#link-wrapper-class-2").html(linkwrapclass);
		jQuery("#link-wrapper-class-3").html(linkwrapclass);
		jQuery("#link-wrapper-class-4").html(linkwrapclass);
		jQuery("#link-wrapper-class-5").html(linkwrapclass);
		jQuery("#link-wrapper-class-6").html(linkwrapclass);
		jQuery("#link-wrapper-class-7").html(linkwrapclass);
		/* =============== End of link wrapp class ================ */
		jQuery("#nextpagelink-1").html(nxtpaglink);
		jQuery("#nextpagelink-2").html(nxtpaglink);
		jQuery("#elipsis-1").html(elipsis);
		jQuery("#elipsis-2").html(elipsis);
		jQuery("#previouspagelink-1").html(prevpagelink);
		jQuery("#previouspagelink-2").html(prevpagelink);
		jQuery("#firstpage-1").html(firstpage);
		jQuery("#firstpage-2").html(firstpage);
		jQuery("#lastpage-1").html(lastpage);
		jQuery("#lastpage-2").html(lastpage);
		/* =============== Seperator  ================ */
		jQuery("#sepval-1").html(sepval);
		jQuery("#sepval-2").html(sepval);
		jQuery("#sepval-3").html(sepval);
		jQuery("#sepval-4").html(sepval);
		jQuery("#sepval-5").html(sepval);
		jQuery("#sepval-6").html(sepval);
		jQuery("#sepval-7").html(sepval);
		jQuery("#sepval-8").html(sepval);
		
	});
	jQuery(function (){
		jQuery('#link-wrapper').change(function (){
			var linkwrap = jQuery("#link-wrapper").val();
			jQuery("#link-wrapper-1").html(linkwrap);
			jQuery("#link-wrapper-2").html(linkwrap);
			jQuery("#link-wrapper-3").html(linkwrap);
			jQuery("#link-wrapper-4").html(linkwrap);
			jQuery("#link-wrapper-5").html(linkwrap);
			jQuery("#link-wrapper-6").html(linkwrap);
			jQuery("#link-wrapper-7").html(linkwrap);
			jQuery("#link-wrapper-8").html(linkwrap);
			jQuery("#link-wrapper-9").html(linkwrap);
			jQuery("#link-wrapper-10").html(linkwrap);
			jQuery("#link-wrapper-11").html(linkwrap);
			jQuery("#link-wrapper-12").html(linkwrap);
			jQuery("#link-wrapper-13").html(linkwrap);
			jQuery("#link-wrapper-14").html(linkwrap);
		 });       
	});
	jQuery(function (){
		jQuery('#wrapper-tag').change(function (){
			var wraptag = jQuery("#wrapper-tag").val();
			jQuery("#wrapper-tag-1").html(wraptag);
			jQuery("#wrapper-tag-2").html(wraptag);
		 });       
	});
	jQuery(function (){
		jQuery('#wrapper-id').change(function (){
			var wrapid = jQuery("#wrapper-id").val();
			//var wrapid = wrapid.replace(",", " ");
			jQuery("#wrapper-id-1").html(wrapid);
		});       
	})
	jQuery(function (){
		jQuery('#wrapper-class').change(function (){
			var wrapclass = jQuery("#wrapper-class").val();
			var wrapclass = wrapclass.replace(",", " ");
			jQuery("#wrapper-class-1").html(wrapclass);
		});       
	});
	jQuery(function (){
		jQuery('#nextpagelink').change(function (){
			var nxtpaglink = jQuery("#nextpagelink").val();
			jQuery("#nextpagelink-1").html(nxtpaglink);
			jQuery("#nextpagelink-2").html(nxtpaglink);
		});       
	});
	jQuery(function (){
		jQuery('#previouspagelink').change(function (){
			var prevpagelink = jQuery("#previouspagelink").val();
			jQuery("#previouspagelink-1").html(prevpagelink);
			jQuery("#previouspagelink-2").html(prevpagelink);
		});       
	});
	jQuery(function (){
		jQuery('#firstpage').change(function (){
			var firstpage = jQuery("#firstpage").val();
			jQuery("#firstpage-1").html(firstpage);
			jQuery("#firstpage-2").html(firstpage);
		});       
	});
	jQuery(function (){
		jQuery('#lastpage').change(function (){
			var lastpage = jQuery("#lastpage").val();
			jQuery("#lastpage-1").html(lastpage);
			jQuery("#lastpage-2").html(lastpage);
		});       
	});
	jQuery(function (){
		jQuery('#elipsis').change(function (){
			var elipsis = jQuery("#elipsis").val();
			jQuery("#elipsis-1").html(elipsis);
			jQuery("#elipsis-2").html(elipsis);
		});       
	});
	jQuery(function (){
		jQuery('#link-wrapper-class').change(function (){
			var linkwrapclass = jQuery("#link-wrapper-class").val();
			jQuery("#link-wrapper-class-1").html(linkwrapclass);
			jQuery("#link-wrapper-class-2").html(linkwrapclass);
			jQuery("#link-wrapper-class-3").html(linkwrapclass);
			jQuery("#link-wrapper-class-4").html(linkwrapclass);
			jQuery("#link-wrapper-class-5").html(linkwrapclass);
			jQuery("#link-wrapper-class-6").html(linkwrapclass);
			jQuery("#link-wrapper-class-7").html(linkwrapclass);
		});       
	});
	
</script>
<div class="wrap">
	
    <h2>Page-Links Plus</h2>
	<div class="border"></div>
	<div id="logo-content">
	    <div id="logo-wrap">
	    	<a href="http://studiohyperset.com" target="_blank"><img src="http://studiohyperset.com/wp-content/uploads/2011/03/logo-lg.png" id="logo" /></a>
	    	<a href="http://studiohyperset.com" target="_blank">studiohyperset.com</a>
	    	<br /><a href="http://pagelinksplus.com" target="_blank">pagelinksplus.com</a>
	    	<br /><a href="https://twitter.com/studiohyperset" target="_blank">@studiohyperset</a>
	    	<br /><a href="https://twitter.com/#!/search/?q=%23pagelinksplus&src=hash" target="_blank">#pagelinksplus</a>
	    </div>

	    <div id="logo-right">
	    
		    <p><a href="http://studiohyperset.com" target="_blank">Studio Hyperset</a> <?php _e("built Page-Links Plus for one reason: to provide the WordPress community with an integrated, comprehensive pagination solution.", SH_PAGE_LINKS_DOMAIN); ?></p>
		    <p><?php _e("Whether you're a WordPress developer, site manager, or lay user, Page-Links Plus can help you set up, customize, and manage your site's pagination quickly and easily.", SH_PAGE_LINKS_DOMAIN); ?></p>
            


 <?php if (!is_plugin_active('pagination-styles/pagination-styles.php') || !is_plugin_active('auto-pagination/auto-pagination.php') || !is_plugin_active('scrolling-pagination/scrolling-pagination.php')) { echo '<div id="plp-in-two" class="rwd-media"><h3>PLP in :02</h3>
<iframe src="//player.vimeo.com/video/109187562" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>'; } else { } ?>		    

		    
	    
	    </div>
	</div>

	<div class="breaker-bottom"></div>
   
    <?php if (isset($_GET['settings-updated'])) : ?>
        <div id="setting-error-settings_updated" class="updated settings-error"><p><strong><?php _e("Settings saved.", SH_PAGE_LINKS_DOMAIN); ?></strong></p></div>
    <?php endif; ?>
    <form action="options.php" method="post" id="sh_pagelinks_options_form">

        <?php settings_fields('sh_page_links_options'); ?>
        
        <div class="tabs">

            <div class="nav-tab-wrapper">
                <ul>
                    <?php foreach ($sections_array as $section => $data):  ?>
                        <li class="nav-tab">
                            <a id="tab-<?php echo esc_attr($section);?>" href="#<?php echo esc_attr($section);?>"><?php echo $data['title']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php foreach ($sections as $section_name): ?>
				
				 <div class="tab" id="<?php echo esc_attr($section_name); ?>">
				 	<?php do_settings_sections("sh_page_links_options-{$section_name}"); ?>
                </div>
            <?php endforeach; ?>

            <input type="hidden" name="current_tab" id="input-current-tab" value="single_view" />
        </div>

		<input name="sh_page_links_option-submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', SH_PAGE_LINKS_DOMAIN); ?>" />
		<input type="button" class="button-primary" id="restorevalue" name="sh_page_links_option-reset" value="<?php esc_attr_e('Restore default', SH_PAGE_LINKS_DOMAIN); ?>"/> 
    </form>
</div>
