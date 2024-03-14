<?php
defined( 'ABSPATH' ) || exit;

/* @var $widget WP_Widget */
/* @var $instance array */
/* @var $cards array */

$title       = ! empty( $instance['wqm_title'] ) ? $instance['wqm_title'] : '';
$as_link     = ! empty( $instance['wqm_as_link'] ) ? $instance['wqm_as_link'] : 'none';
$wqm_card_id = ! empty( $instance['wqm_card_id'] ) ? $instance['wqm_card_id'] : '';
$wqm_card_id = WQM_Common::clear_digits( $wqm_card_id );
?>
<p>
    <label for="<?php esc_attr_e( $widget->get_field_id( 'wqm_title' ) ); ?>"><?php _e( 'Title', 'wp-qrcode-me-v-card' ); ?>:</label>
    <input class="widefat" id="<?php esc_attr_e( $widget->get_field_id( 'wqm_title' ) ); ?>"
           name="<?php esc_attr_e( $widget->get_field_name( 'wqm_title' ) ); ?>" type="text"
           value="<?php esc_attr_e( $title ); ?>">
</p>
<p>
    <label for="<?php esc_attr_e( $widget->get_field_id( 'wqm_card_id' ) ); ?>">
		<?php _e( 'Card', 'wp-qrcode-me-v-card' ) ?>:
    </label>
    <select class="widefat" name="<?php esc_attr_e( $widget->get_field_name( 'wqm_card_id' ) ); ?>"
            id="<?php esc_attr_e( $widget->get_field_id( 'wqm_card_id' ) ); ?>">

        <option value=""><?php _e( 'Select card', 'wp-qrcode-me-v-card' ); ?></option>
		<?php foreach ( $cards as $id => $name ): ?>
            <option value="<?php echo $id ?>" <?php echo selected( $wqm_card_id, $id ) ?>><?php esc_attr_e( ! empty( $name ) ? $name : __( '(without title)', 'wp-qrcode-me-v-card' ) ) ?></option>
		<?php endforeach; ?>

    </select>
    <span class="description"><?php _e( 'Please select MeCard/vCard QR code card', 'wp-qrcode-me-v-card' ) ?></span>
</p>
<p>
    <input class="widefat" id="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-none"
           name="<?php esc_attr_e( $widget->get_field_name( 'wqm_as_link' ) ); ?>" type="radio"
           value="none" <?php checked( 'none', $as_link ) ?>>
    <label for="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-none">
		<?php _e( 'Just show QR Code', 'wp-qrcode-me-v-card' ); ?>
    </label>
    <br>
    <input class="widefat" id="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-img"
           name="<?php esc_attr_e( $widget->get_field_name( 'wqm_as_link' ) ); ?>" type="radio"
           value="img" <?php checked( 'img', $as_link ) ?>>
    <label for="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-img">
		<?php _e( 'Allow save QR Code on click', 'wp-qrcode-me-v-card' ); ?>
    </label>
    <br>
    <input class="widefat" id="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-vcf"
           name="<?php esc_attr_e( $widget->get_field_name( 'wqm_as_link' ) ); ?>" type="radio"
           value="vcf" <?php checked( 'vcf', $as_link ) ?>>
    <label for="<?php esc_attr_e( $widget->get_field_id( 'wqm_as_link' ) ); ?>-vcf">
		<?php _e( 'Allow open vCard on click', 'wp-qrcode-me-v-card' ); ?>
    </label>
</p>
