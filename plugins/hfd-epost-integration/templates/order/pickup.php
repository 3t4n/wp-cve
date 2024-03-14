<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 6:12 PM
 */
?>
<?php if ($spotInfo) : ?>
<div class="spot-detail">
    <strong><?php esc_html_e( 'Branch name', 'hfd-integration' ); ?>:</strong> <?php echo esc_html( $spotInfo['name'] ); ?> <br />
    <strong><?php esc_html_e( 'Branch address', 'hfd-integration' ); ?>:</strong> <?php echo esc_html( $spotInfo['street'] ); ?> <?php echo esc_html( $spotInfo['house'] ); ?>, <?php echo esc_html( $spotInfo['city'] ); ?> <br />
    <strong><?php esc_html_e( 'Operating hours', 'hfd-integration' ); ?>:</strong> <?php echo esc_html( $spotInfo['remarks'] ); ?> <br />
</div>
<?php endif;