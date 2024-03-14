<?php
namespace mbSocial;
defined('ABSPATH') or die('No direct access permitted');

?>
<script language='javascript'>
jQuery(document).ready(function($) {

$('.maxsocial-notice [data-action]').on('click', mb_review_init_ajax);

function mb_review_init_ajax (e)
{
	e.preventDefault;

	var new_status = $(e.target).data('action');
	mb_review_ajax(new_status);
}

function mb_review_ajax(new_status)
{
	var url = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
	var data = {
				 action: 'maxajax',
		 		 plugin_action: 'mbsocial_review_notice_status',
				 status : new_status,
				 nonce: '<?php echo wp_create_nonce('maxajax') ?>',
			 };

	$.ajax({
	  method: "POST",
	  url: url,
	  data: data,
	  success: function (res)
	  {
		 mb_review_done();
 	  },

	});

}

function mb_review_done()
{
	$('.maxsocial-notice').fadeOut();

}

}); /* END OF JQUERY */
</script>

<style>
	.maxsocial-notice { height: 180px;  clear:both; }
	.maxsocial-notice .mb-notice { height: auto; display: inline-block; }
</style>

  <div class="updated notice maxsocial-notice maxbuttons-notice">
      <div class='review-logo'><img src="<?php echo mbSocial()->get_plugin_url() ?>images/mbsocial-icon-128.png"></div>
      <div class='mb-notice'>
        <p class='title'><?php _e("We need your rating!","maxbuttons"); ?></p>
      <p><?php _e("Your rating is the simplest way to support MaxButtons Social Buttons. We really appreciate it!","mbsocial"); ?></p>

			<p><strong><?php _e('Missing Something?', 'mbsocial'); ?></strong> <?php printf(__('Write on the %ssupport forum%s, we love feedback!', 'mbsocial'), '<a href="https://wordpress.org/support/plugin/share-button" target="_blank">','</a>');  ?></p>

      <ul class="review-notice-links">
        <li> <span class="dashicons dashicons-yes"></span><a data-action='off' href="javascript:void(0)"><?php _e("I've already left a review","mbsocial"); ?></a></li>
        <li><span class="dashicons dashicons-calendar-alt"></span><a data-action='later' href="javascript:void(0)"><?php _e("Maybe Later","mbsocial"); ?></a></li>
        <li><span class="dashicons dashicons-external"></span><a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/share-button?filter=5#postform"><?php _e("Sure! I'd love to!","mbsocial"); ?></a></li>
      </ul>
      </div>
      <a class="dashicons dashicons-dismiss close-mb-notice" href="javascript:void(0)" data-action='off'></a>

  </div>
