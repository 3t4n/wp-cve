<?php
/**
 * Outputs the Gallery Type Tab Selector and Panels
 *
 * @since   1.5.0
 *
 * @var array $data Array of data to pass to the view.
 *
 * @package Envira_Gallery
 * @author  Envira Team
 */

?>
<h2 id="envira-types-nav" class="nav-tab-wrapper envira-tabs-nav" data-container="#envira-types" data-update-hashbang="0">
	<label class="nav-tab nav-tab-native-envira-gallery<?php echo ( ( $data['instance']->get_config( 'type', $data['instance']->get_config_default( 'type' ) ) === 'default' ) ? ' envira-active' : '' ); ?>" for="envira-gallery-type-default" data-tab="#envira-gallery-native">
		<input id="envira-gallery-type-default" type="radio" name="_envira_gallery[type]" value="default" <?php checked( $data['instance']->get_config( 'type', $data['instance']->get_config_default( 'type' ) ), 'default' ); ?> />
		<span><?php esc_html_e( 'Native Envira Gallery', 'envira-gallery-lite' ); ?></span>
	</label>

	<a href="#envira-gallery-external" title="<?php esc_attr_e( 'External Gallery', 'envira-gallery-lite' ); ?>" class="nav-tab nav-tab-external-gallery<?php echo ( ( $data['instance']->get_config( 'type', $data['instance']->get_config_default( 'type' ) ) !== 'default' ) ? ' envira-active' : '' ); ?>">
		<span><?php esc_html_e( 'External Gallery', 'envira-gallery-lite' ); ?></span>
	</a>
</h2>

<!-- Types -->
<div id="envira-types" data-navigation="#envira-types-nav">
	<!-- Native Envira Gallery - Drag and Drop Uploader -->
	<div id="envira-gallery-native" class="envira-tab envira-clear<?php echo ( ( $data['instance']->get_config( 'type', $data['instance']->get_config_default( 'type' ) ) === 'default' ) ? ' envira-active' : '' ); ?>">
		<!-- Errors -->
		<div id="envira-gallery-upload-error"></div>

		<!-- WP Media Upload Form -->
		<?php
		media_upload_form();
		?>
		<script type="text/javascript">
			var post_id = <?php echo intval( $data['post']->ID ); ?>, shortform = 3;
		</script>
		<input type="hidden" name="post_id" id="post_id" value="<?php echo intval( $data['post']->ID ); ?>" />
	</div>

	<!-- External Gallery -->
	<div id="envira-gallery-external" class="envira-tab envira-clear<?php echo ( ( $data['instance']->get_config( 'type', $data['instance']->get_config_default( 'type' ) ) !== 'default' ) ? ' envira-active' : '' ); ?>">

		<?php $upgrade_link = Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'adminpage', 'externalgalleryinstagram' ); ?>
		<p class="envira-intro"><?php esc_html_e( 'Create Dynamic Galleries with Envira', 'envira-gallery-lite' ); ?></p>
		<ul id="envira-gallery-types-nav">
			<li id="envira-gallery-type-instagram">
				<a href="<?php echo esc_url( $upgrade_link ); ?>" title="<?php esc_attr_e( 'Build Galleries from Instagram images.', 'envira-gallery-lite' ); ?>" target="_blank">
					<div class="icon"></div>
					<div class="title"><?php esc_html_e( 'Instagram', 'envira-gallery-lite' ); ?></div>
				</a>
			</li>
		</ul>
		<p>
			<?php esc_html_e( 'Envira Pro allows you to build galleries from Instagram photos, images from your posts, and more.', 'envira-gallery-lite' ); ?>
		</p>
		<?php $upgrade_link = Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'adminpage', 'externalgalleryclickheretoupgradebutton' ); ?>
		<p>
			<a href="<?php echo esc_url( $upgrade_link ); ?>" class="button button-primary button-x-large" title="<?php esc_attr_e( 'Click Here to Upgrade', 'envira-gallery-lite' ); ?>" target="_blank">
				<?php esc_html_e( 'Click Here to Upgrade', 'envira-gallery-lite' ); ?>
			</a>
		</p>

	</div>
</div>
<div class="clear"></div>