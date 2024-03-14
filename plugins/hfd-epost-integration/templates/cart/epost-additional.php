<?php
/**
 * Created by PhpStorm.
 * Date: 6/5/18
 * Time: 4:18 PM
 */

if( !defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly.
}
?>
<div id="israelpost-additional">
    <?php if ($layout == 'map') : ?>
        <div class="spot-detail">
            <?php if ($spotInfo) : ?>
                <input type="hidden" id="israelpost-spot-id" value="<?php echo esc_attr( $spotInfo['n_code'] ); ?>" />
                <strong><?php echo esc_html( __( 'Branch name', 'hfd-integration' ) ); ?>:</strong> <?php echo esc_html( $spotInfo['name'] ); ?> <br />
                <strong><?php echo esc_html( __( 'Branch address', 'hfd-integration' ) ); ?>:</strong> <?php echo esc_html( $spotInfo['street'] ); ?> <?php echo esc_html( $spotInfo['house'] ); ?>, <?php echo esc_html( $spotInfo['city'] ); ?> <br />
                <strong><?php echo esc_html( __( 'Operating hours', 'hfd-integration' ) ); ?>:</strong> <?php echo esc_html( $spotInfo['remarks'] ); ?> <br />
            <?php endif ?>
        </div>
        <p>
            <a href="javascript:void(0);" class="spot-picker">
                <?php echo !$spotInfo ? esc_html( __( 'Choose pickup branch', 'hfd-integration' ) ) : esc_html( __('Change pickup branch', 'hfd-integration' ) ); ?>
            </a>
        </p>
    <?php else:
		$helper = \Hfd\Woocommerce\Container::get( 'Hfd\Woocommerce\Helper\Spot' );
	?>
        <div class="spot-list-container">
            <div class="field">
                <select id="city-list" <?php if ($spotInfo) : ?>data-selected="<?php echo esc_attr( $spotInfo['city'] ); ?>" <?php endif; ?>>
                    <option value=""><?php echo esc_html( __( 'Select city', 'hfd-integration' ) ); ?></option>
                </select>
            </div>
            <div class="field">
                <select id="spot-list" <?php if ($spotInfo) : ?>data-selected="<?php echo esc_html( $spotInfo['n_code'] ); ?>" <?php endif; ?>>
                    <option value=""><?php echo esc_html( __('Select pickup point', 'hfd-integration' ) ); ?></option>
                </select>
            </div>
            <div class="spot-message"><?php echo esc_html( __( 'Please choose pickup branch', 'hfd-integration' ) ); ?></div>
        </div>
    <?php endif; ?>
</div>