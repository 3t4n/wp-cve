<?php

namespace XproElementorAddons\Modules\Swatches;

defined('ABSPATH') || exit;

class Admin_Product {

	private static $instance = null;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		add_action('woocommerce_product_option_terms', [$this, 'get_option_terms_product'], 10, 2);

		add_action('wp_ajax_xpro_add_new_attribute', [$this, 'add_attribute_by_ajax']);

		add_action('admin_footer', [$this, 'xpro_term_template']);
	}


	public function get_option_terms_product($taxonomy, $index) {

		$types = Swatches::instance()->get_available_types();

		if(!array_key_exists($taxonomy->attribute_type, $types)) {
			return;
		}

		$taxonomy_name = wc_attribute_taxonomy_name($taxonomy->attribute_name);

		global $thepostid;

		$product_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : $thepostid; ?>

        <select multiple="multiple" data-placeholder="<?php esc_attr_e('Select terms', 'xpro-elementor-addons'); ?>"
                class="multiselect attribute_values wc-enhanced-select"
                name="attribute_values[<?php echo esc_attr( $index ); ?>][]">
			<?php

			$all_terms = get_terms($taxonomy_name, apply_filters('woocommerce_product_attribute_terms', ['orderby' => 'name', 'hide_empty' => false]));

			if($all_terms) {
				foreach($all_terms as $term) {
					echo '<option value="' . esc_attr($term->term_id) . '" ' . selected(has_term(absint($term->term_id), $taxonomy_name, $product_id), true, false) . '>' . esc_html(apply_filters('woocommerce_product_attribute_term_name', $term->name, $term)) . '</option>';
				}
			}
			?>
        </select>
        <button class="button plus select_all_attributes"><?php esc_html_e('Select all', 'xpro-elementor-addons'); ?></button>
        <button class="button minus select_no_attributes"><?php esc_html_e('Select none', 'xpro-elementor-addons'); ?></button>
        <button class="button fr plus xpro_assign_new_attribute" data-type="<?php echo esc_attr( $taxonomy->attribute_type ) ?>">
			<?php esc_html_e('Add new', 'xpro-elementor-addons'); ?>
        </button>

		<?php
	}


	public function add_attribute_by_ajax() {

		$nonce  = isset($_POST['nonce']) ? sanitize_key($_POST['nonce']) : '';
		$tax    = isset($_POST['taxonomy']) ? sanitize_key($_POST['taxonomy']) : '';
		$type   = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';
		$name   = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
		$slug   = isset($_POST['slug']) ? sanitize_key($_POST['slug']) : '';
		$swatch = isset($_POST['swatch']) ? sanitize_text_field($_POST['swatch']) : '';

		if(!wp_verify_nonce($nonce, 'xpro_nonce_add_attribute')) {
			wp_send_json_error(esc_html__('Request denied', 'xpro-elementor-addons'));
		}

		if(empty($name) || empty($swatch) || empty($tax) || empty($type)) {
			wp_send_json_error(esc_html__('Insufficient data', 'xpro-elementor-addons'));
		}

		if(!taxonomy_exists($tax)) {
			wp_send_json_error(esc_html__('Taxonomy is not exists', 'xpro-elementor-addons'));
		}

		if(term_exists($name, $tax)) {
			wp_send_json_error(esc_html__('Term already exists', 'xpro-elementor-addons'));
		}

		$term = wp_insert_term($name, $tax, ['slug' => $slug]);

		if(is_wp_error($term)) {

			wp_send_json_error($term->get_error_message());

		} else {
			$term = get_term_by('id', $term['term_id'], $tax);
			update_term_meta($term->term_id, $type, $swatch);
		}

		wp_send_json_success(
			[
				'msg'  => esc_html__('Successfully added', 'xpro-elementor-addons'),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			]
		);
	}


	public function xpro_term_template() {

		global $pagenow, $post;

		if($pagenow != 'post.php' || (isset($post) && get_post_type($post->ID) != 'product')) {
			return;
		}
		?>

        <div id="xpro_tpl_modal" class="xpro_modal_container">
            <div class="xpro_modal">
                <button type="button" class="button-link media-modal-close xpro_modal__close">
                    <span class="media-modal-icon"></span></button>
                <div class="xpro_modal__header"><h2><?php esc_html_e('Add new term', 'xpro-elementor-addons') ?></h2></div>
                <div class="xpro_modal__content">
                    <p class="xpro_term__name">
                        <label>
							<?php esc_html_e('Name', 'xpro-elementor-addons') ?>
                            <input type="text" class="xpro__input" name="name">
                        </label>
                    </p>
                    <p class="xpro_term__slug">
                        <label>
							<?php esc_html_e('Slug', 'xpro-elementor-addons') ?>
                            <input type="text" class="xpro__input" name="slug">
                        </label>
                    </p>
                    <div class="xpro_term__swatch">

                    </div>
                    <div class="hidden xpro_term__tax"></div>

                    <input type="hidden" class="xpro__input" name="nonce"
                           value="<?php echo wp_create_nonce('xpro_nonce_add_attribute') ?>">
                </div>
                <div class="xpro_modal__footer">
                    <button class="button button-secondary xpro_modal__close"><?php esc_html_e('Cancel', 'xpro-elementor-addons') ?></button>
                    <button class="button button-primary xpro_add_attribute_submit"><?php esc_html_e('Add New', 'xpro-elementor-addons') ?></button>
                    <span class="message"></span>
                    <span class="spinner"></span>
                </div>
            </div>
            <div class="xpro_modal__backdrop media-modal-backdrop"></div>
        </div>

        <script type="text/template" id="tmpl-xpro__tpl_input__color">

            <label><?php esc_html_e('Color', 'xpro-elementor-addons') ?></label><br>
            <input type="text" class="xpro__input xpro_input__color" name="swatch">

        </script>

        <script type="text/template" id="tmpl-xpro__tpl_input__image">

            <label><?php esc_html_e('Image', 'xpro-elementor-addons') ?></label><br>

            <div class="xpro_term_img_thumbnail" style="float:left;margin-right:10px;">
                <img src="<?php echo esc_url(Swatches::get_dummy()) ?>" width="60px" height="60px"/>
            </div>

            <div style="line-height:60px;">
                <input type="hidden" class="xpro__input xpro_input__image xpro_term_img" name="swatch" value=""/>

                <button type="button" class="xpro_upload_img_button button">
					<?php esc_html_e('Upload/Add image', 'xpro-elementor-addons'); ?>
                </button>

                <button type="button" class="xpro_remove_img_btn button hidden">
					<?php esc_html_e('Remove image', 'xpro-elementor-addons'); ?>
                </button>
            </div>

        </script>

        <script type="text/template" id="tmpl-xpro__tpl_input__label">

            <label>
				<?php esc_html_e('Label', 'xpro-elementor-addons') ?>
                <input type="text" class="xpro__input xpro_input__label" name="swatch">
            </label>

        </script>

        <script type="text/template" id="tmpl-xpro__tpl_input__tax">

            <input type="hidden" class="xpro__input" name="taxonomy" value="{{data.tax}}">
            <input type="hidden" class="xpro__input" name="type" value="{{data.type}}">

        </script>
		<?php
	}
}

