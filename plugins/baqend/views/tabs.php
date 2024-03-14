<?php $file = \Baqend\WordPress\Info::get_plugin_file(); ?>
<?php $link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file, 'upgrade-plugin_' . $file ); ?>
<div id="note-update-plugin" class="update-nag" style="display: none">
  <p data-tmpl="<?php echo __('Your Baqend WordPress Plugin version %our% is outdated. Update to version %remote% now!', 'baqend') ?>"></p>
  <a class="button button-primary" href="<?php echo $link; ?>"><?php _e('Download Latest Plugin', 'baqend') ?></a>
  <br class="clear">
</div>

<?php $this->tabs(); ?>
