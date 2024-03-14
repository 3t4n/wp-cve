<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if (!empty($page_id)):
	wp_enqueue_script('stm_hb-theme-modal');
	$page = get_post($page_id);
	$content = $page->post_content;

	if (empty($modal_width)) {
		$modal_width = 600;
	}
	$modal_id = 'iconBoxModal_' . $page_id;

	$modal_width = $modal_width . 'px';

	$modal_style = array(
		'width' => $modal_width,
	);

	$modal_style = stm_hb_array_to_style_string($modal_style);

	?>

    <div class="modal"
         id="<?php echo esc_attr($modal_id) ?>"
         tabindex="-1"
         role="dialog"
         aria-labelledby="headerModal<?php echo esc_attr($page_id); ?>">
        <div class="modal-dialog" style="<?php echo esc_attr($modal_style) ?>" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="stm-content">
                        <div class="row">
							<?php echo do_shortcode($content); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php endif; ?>