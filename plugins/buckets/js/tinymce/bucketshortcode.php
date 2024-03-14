<?php
require_once('../../../../../wp-load.php'); //load Wordpress

if (is_user_logged_in()):

	wp_enqueue_style('buttons');
	wp_enqueue_style('wp-admin');
	wp_enqueue_style('editor-buttons');
	wp_enqueue_script('tiny_mce_popup', site_url() . '/wp-includes/js/tinymce/tiny_mce_popup.js');

?>
<!doctype html>
<html>
<head>
	<title>Bucket Shortcode</title>

	<?php wp_head(); ?>

	<style>
		#wp-link-wrap.search-panel-visible { box-shadow: none; margin: 0px; width: 100%; height: 100%; top: -20px !important; left: 0; }
		#wp-link .submitbox { padding-top: 21px; height: 21px; }
		#wp-link li.selected { color: #fff; background: #2ea2cc; }
		.button-primary { font-weight: bold; }
	</style>

</head>


<body>

	<div id="wp-link-wrap" class="wp-core-ui search-panel-visible" style="display: block;">

		<form id="wp-link" tabindex="-1" _lpchecked="1" class="filterform" >
			<input type="hidden" id="_ajax_linking_nonce" name="_ajax_linking_nonce" value="0cdc6f2711">
			<div id="link-selector">
				<div id="search-panel">
					<div class="link-search-wrapper">
						<label>
							<span class="search-label">Search</span>
							<input type="search" id="search-field" class="link-search-field" autocomplete="off">
							<span class="spinner" style="display: none;"></span>
						</label>
					</div>
					<div id="search-results" class="query-results" tabindex="0" style="display: none;">
						<div class="river-waiting" style="display: none;">
							<span class="spinner"></span>
						</div>
					</div>
					<div id="most-recent-results" class="query-results" tabindex="0" style="display: block;">
						<div class="query-notice" id="query-notice-message">
							<em class="query-notice-default" style="display: block;">Select a Bucket from the list and click Insert Bucket to insert the shortcode into your content.</em>
						</div>

						<ul class="list">
						<?php $posts = get_posts(array('numberposts' => -1, 'post_type' => 'buckets', 'orderby' => 'title', 'order' => 'ASC')); ?>
						<?php foreach($posts as $post) : ?>
							<li data-id="<?php echo $post->ID; ?>"><span class="item-title"><?php echo $post->post_title; ?></span><span class="item-info"><?php echo get_bucket_type($post->ID); ?></span></li>
						<?php endforeach; ?>
						</ul>

						<div class="river-waiting" style="display: none;">
							<span class="spinner"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="submitbox">
				<div id="wp-link-cancel">
					<a class="submitdelete deletion" href="#">Cancel</a>
				</div>
				<div id="wp-link-update">
					<input type="submit" value="Insert Bucket" class="button button-primary" id="wp-link-submit" name="wp-link-submit">
				</div>
			</div>
		</form>
</div>

<?php wp_footer(); ?>



	<script>


		jQuery(function($){

			// Filters the list of Buckets
			jQuery.expr[':'].Contains = function(a,i,m){
			  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
			};

			function listFilter(header, list) {
				var input = $("#search-field");

				$(input)
				  .change( function () {
				    var filter = $(this).val();
				    if(filter) {
				      $(list).find(".item-title:not(:Contains(" + filter + "))").parent().fadeOut();
				      $(list).find(".item-title:Contains(" + filter + ")").parent().fadeIn();
				    } else {
				      $(list).find("li").fadeIn();
				    }
				    return false;
				  })
				.keyup( function () {
				    $(this).change();
				});
			}
			listFilter($("#search-results"), $(".list"));

			// Highlights Selected
			$('#most-recent-results li').click(function(){
				$('.selected').removeClass('selected');
				$(this).addClass('selected');
			});

			// Close Popup
			$('#wp-link-cancel').click(function(){
				tinyMCEPopup.close();
				return false;
			});

			// Inserts Shortcode into WYSIWYG and closes popup
			$('#wp-link-submit').click(function(){
				var id = $('.selected').data('id');
				var title = $('.selected .item-title').html();
				console.log('[bucket id="' + id + '" title="' + title + '"]');
				tinyMCE.execCommand('mceInsertContent',false,'[bucket id="' + id + '" title="' + title + '"]');
				tinyMCEPopup.close();
				return false;
			});


		});
	</script>

</body>
</html>


<?php endif; ?>


