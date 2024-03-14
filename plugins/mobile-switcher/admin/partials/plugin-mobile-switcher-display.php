<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/admin/partials
 */
?>

<h2>Mobile Switcher settings</h2>
<form method="POST" action="">
    <div class="ms-form-container">
        <?php _e( 'Switching enable', 'mobile-switcher' ); ?>
        <?php if ( $enabled ): ?>
            <input type="checkbox" name="enabled" checked="checked" class="ms-input">
        <?php else: ?>
            <input type="checkbox" name="enabled"  class="ms-input">
        <?php endif; ?>        
        <br /><br />
        <span><?php _e( 'Desktop theme', 'mobile-switcher' ); ?></span>
        <select class="ms-input" name="desktop">
            <?php foreach ( $themes as $theme => $object ): ?>
                <?php if ( $theme == $desktop ): ?>
                    <option value="<?php echo $theme; ?>" selected="selected"><?php echo $theme; ?></option>
                <?php else: ?>
                    <option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <span><?php _e( 'Mobile theme', 'mobile-switcher' ); ?></span>
        <select  class="ms-input" name="mobile">
            <?php foreach ( $themes as $theme => $object ): ?>
                <?php if ( $theme == $mobile ): ?>
                    <option value="<?php echo $theme; ?>" selected="selected"><?php echo $theme; ?></option>
                <?php else: ?>
                    <option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <span><?php _e( 'Tablet theme', 'mobile-switcher' ); ?></span>
        <select  class="ms-input" name="tablet">
            <?php foreach ( $themes as $theme => $object ): ?>
                <?php if ( $theme == $tablet ): ?>
                    <option value="<?php echo $theme; ?>" selected="selected"><?php echo $theme; ?></option>
                <?php else: ?>
                    <option value="<?php echo $theme; ?>"><?php echo $theme; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <br /><br />
        <button type="submit"><?php _e( 'Save', 'mobile-switcher' ); ?></button>
    </div>
</form>