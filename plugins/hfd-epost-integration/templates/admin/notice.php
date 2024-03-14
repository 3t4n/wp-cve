<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 7:22 PM
 */
if (!$syncData) {
    return;
}
?>
<?php if (!empty($syncData['count'])) : ?>
<div class="updated fade">
    <p><?php echo esc_html( sprintf( __( '%s order(s) sent to HFD.', 'hfd-integration' ), $syncData['count'] ) ); ?></p>
</div>
<?php endif; ?>

<?php if ($syncData['errors']) : ?>
<div class="updated fade">
    <?php foreach ($syncData['errors'] as $error) : ?>
        <p><?php echo esc_html( $error ); ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>
