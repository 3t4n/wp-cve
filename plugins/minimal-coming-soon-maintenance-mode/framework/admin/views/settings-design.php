<?php

/**
 * Design settings view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

?>

<div class="signals-tile" id="design">
  <div class="signals-tile-body">
    <div class="signals-tile-title"><?php esc_attr_e( 'DESIGN', 'minimal-coming-soon-maintenance-mode' ); ?></div>
    <p>
      <?php esc_attr_e( 'Design settings for the plugin. You have the option to modify every aspect of the maintenance page design as per your requirements.', 'minimal-coming-soon-maintenance-mode' ); ?>
    </p>

    <div class="signals-section-content">
      <div class="signals-upload-group signals-clearfix">
        <div class="signals-form-group border-fix">
          <div class="signals-upload-element">
            <label class="signals-strong"><?php esc_attr_e( 'Logo', 'minimal-coming-soon-maintenance-mode' ); ?></label>

            <?php if ( ! empty( $signals_csmm_options['logo'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
            <span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['logo'] ); ?>" /></span>
            <?php else : ?>
            <span class="signals-preview-area"><?php esc_attr_e( 'Select an image or upload a new one', 'minimal-coming-soon-maintenance-mode' ); ?></span>
            <?php endif; ?>

            <input type="hidden" name="signals_csmm_logo" id="signals_csmm_logo" value="<?php esc_attr_e( $signals_csmm_options['logo'] ); ?>">
            <button type="button" name="signals_logo_upload" id="signals_logo_upload" class="signals-btn signals-upload"
              style="margin-top: 4px"><?php esc_attr_e( 'Select', 'minimal-coming-soon-maintenance-mode' ); ?></button>

            <span class="signals-upload-append">
              <?php if ( ! empty( $signals_csmm_options['logo'] ) ) : ?>
              &nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php esc_attr_e( 'Remove', 'minimal-coming-soon-maintenance-mode' ); ?></a>
              <?php endif; ?>
            </span>
          </div>
        </div>

        <div class="signals-form-group border-fix">
          <div class="signals-upload-element">
            <label class="signals-strong"><?php esc_attr_e( 'Favicon', 'minimal-coming-soon-maintenance-mode' ); ?></label>

            <?php if ( ! empty( $signals_csmm_options['favicon'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
            <span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['favicon'] ); ?>" /></span>
            <?php else : ?>
            <span class="signals-preview-area"><?php esc_attr_e( 'Select an image or upload a new one', 'minimal-coming-soon-maintenance-mode' ); ?></span>
            <?php endif; ?>

            <input type="hidden" name="signals_csmm_favicon" id="signals_csmm_favicon"
              value="<?php esc_attr_e( $signals_csmm_options['favicon'] ); ?>">
            <button type="button" name="signals_favicon_upload" id="signals_favicon_upload" class="signals-btn signals-upload"
              style="margin-top: 4px"><?php esc_attr_e( 'Select', 'minimal-coming-soon-maintenance-mode' ); ?></button>

            <span class="signals-upload-append">
              <?php if ( ! empty( $signals_csmm_options['favicon'] ) ) : ?>
              &nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php esc_attr_e( 'Remove', 'minimal-coming-soon-maintenance-mode' ); ?></a>
              <?php endif; ?>
            </span>
          </div>
        </div>

        <div class="signals-form-group border-fix" id="background-preview">
          <div class="signals-upload-element">
            <label class="signals-strong"><?php esc_attr_e( 'Background Cover Image', 'minimal-coming-soon-maintenance-mode' ); ?></label>

            <?php if ( ! empty( $signals_csmm_options['bg_cover'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
            <span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['bg_cover'] ); ?>" /></span>
            <?php else : ?>
            <span class="signals-preview-area"><?php esc_attr_e( 'Select an image', 'minimal-coming-soon-maintenance-mode' ); ?></span>
            <?php endif; ?>

            <?php
						$bgupload = false;
						$meta = get_option('signals_csmm_meta', array());
						if (version_compare($meta['first_version'], '1.75', '<')) {
						  $bgupload = true;
						}
						?>

            <input type="hidden" name="signals_csmm_bg" id="signals_csmm_bg" value="<?php esc_attr_e( $signals_csmm_options['bg_cover'] ); ?>">
            <button type="button" name="signals_bg_upload" id="signals_bg_upload"
              class="signals-btn signals-upload <?php echo $bgupload?'':'signals-upload-bg'; ?>"
              style="margin-top: 4px"><?php esc_attr_e( 'Select', 'minimal-coming-soon-maintenance-mode' ); ?></button>

            <span class="signals-upload-append">
              <?php if ( ! empty( $signals_csmm_options['bg_cover'] ) ) : ?>
              &nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php esc_attr_e( 'Remove', 'minimal-coming-soon-maintenance-mode' ); ?></a>
              <?php endif; ?>
            </span>
          </div>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_overlay" class="signals-strong"><?php esc_attr_e( 'Content Overlay', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input type="checkbox" class="signals-form-ios" name="signals_csmm_overlay" value="1"
            <?php checked( '1', $signals_csmm_options['content_overlay'] ); ?>>
          <p class="signals-form-help-block">
            <?php esc_attr_e( 'If enabled, applies transparent background to the content section of the maintenance page.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="background_image_filter" class="signals-strong">Background Image Filter<sup>PRO</sup></label>
          <select name="background_image_filter" class="skip-save" id="background_image_filter">
            <?php
            $filters = array(
              array('val'=> '', 'label' => 'No Filter'),
              array('label' => '1977', 'val' => ' _1977'),
              array('label' => 'Aden', 'val' => ' aden'),
              array('label' => 'Black & White', 'val' => ' blackwhite'),
              array('label' => 'Brannan', 'val' => ' brannan'),
              array('label' => 'Brooklyn', 'val' => ' brooklyn'),
              array('label' => 'Clarendon', 'val' => ' clarendon'),
              array('label' => 'Earlybird', 'val' => ' earlybird'),
              array('label' => 'Gingham', 'val' => ' gingham'),
              array('label' => 'Hudson', 'val' => ' hudson'),
              array('label' => 'Inkwell', 'val' => ' inkwell'),
              array('label' => 'Kelvin', 'val' => ' kelvin'),
              array('label' => 'Lark', 'val' => ' lark'),
              array('label' => 'Lo-Fi', 'val' => ' lofi'),
              array('label' => 'Maven', 'val' => ' maven'),
              array('label' => 'Mayfair', 'val' => ' mayfair'),
              array('label' => 'Moon', 'val' => ' moon'),
              array('label' => 'Nashville', 'val' => ' nashville'),
              array('label' => 'Perpetua', 'val' => ' perpetua'),
              array('label' => 'Reyes', 'val' => ' reyes'),
              array('label' => 'Rise', 'val' => ' rise'),
              array('label' => 'Slumber', 'val' => ' slumber'),
              array('label' => 'Stinson', 'val' => ' stinson'),
              array('label' => 'Toaster', 'val' => ' toaster'),
              array('label' => 'Valencia', 'val' => ' valencia'),
              array('label' => 'Walden', 'val' => ' walden'),
              array('label' => 'Willow', 'val' => ' willow'),
              array('label' => 'X-pro II', 'val' => ' xpro2')
            );
            csmm_create_select_options( $filters, '' );  ?>
          </select>
          <p class="signals-form-help-block">Filters are instantly applied on the background image above for preview. Check out <a target="_blank"
              href="<?php echo esc_url(csmm_generate_web_link('design-filters', 'image-filters')); ?>">the previews</a>. This is a <a href="#pro"
              class="csmm-change-tab">PRO feature</a>.</p>
        </div>

      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_width" class="signals-strong"><?php esc_attr_e( 'Content Width (in px)', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input style="width: 80px;" type="number" name="signals_csmm_width" id="signals_csmm_width"
            value="<?php esc_attr_e( $signals_csmm_options['content_width'] ); ?>"
            placeholder="<?php esc_attr_e( 'Set content width for the page', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

          <p class="signals-form-help-block">
            <?php esc_attr_e( 'Set maximum width of the content (in pixels) for the maintenance page. Provide only numeric value. Example: Entering 400 will set the width of the content to 400px. Defaults to 440px.', 'minimal-coming-soon-maintenance-mode' ); ?>
          </p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_color" class="signals-strong"><?php esc_attr_e( 'Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input type="text" name="signals_csmm_color" id="signals_csmm_color" value="<?php esc_attr_e( $signals_csmm_options['bg_color'] ); ?>"
            placeholder="<?php esc_attr_e( 'Background color for the page', 'minimal-coming-soon-maintenance-mode' ); ?>" class="jscolor signals-form-control color {required:false}">

          <p class="signals-form-help-block">
            <?php esc_attr_e( 'Select background color for the page. If the background cover image is set, this option will be ignored.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_position" class="signals-strong"><?php esc_attr_e( 'Content Position', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <select name="signals_csmm_position" id="signals_csmm_position">
            <option value="left" <?php selected( 'left', $signals_csmm_options['content_position'] ); ?>><?php esc_attr_e( 'Left', 'minimal-coming-soon-maintenance-mode' ); ?></option>
            <option value="center" <?php selected( 'center', $signals_csmm_options['content_position'] ); ?>><?php esc_attr_e( 'Center', 'minimal-coming-soon-maintenance-mode' ); ?>
            </option>
            <option value="right" <?php selected( 'right', $signals_csmm_options['content_position'] ); ?>><?php esc_attr_e( 'Right', 'minimal-coming-soon-maintenance-mode' ); ?></option>
          </select>

          <p class="signals-form-help-block">
            <?php esc_attr_e( 'For the position of the content on the maintenance page. Does not work if the width is set to maximum which is 1170px.', 'minimal-coming-soon-maintenance-mode' ); ?>
          </p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_alignment" class="signals-strong"><?php esc_attr_e( 'Content Alignment', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <select name="signals_csmm_alignment" id="signals_csmm_alignment">
            <option value="left" <?php selected( 'left', $signals_csmm_options['content_alignment'] ); ?>><?php esc_attr_e( 'Left', 'minimal-coming-soon-maintenance-mode' ); ?></option>
            <option value="center" <?php selected( 'center', $signals_csmm_options['content_alignment'] ); ?>><?php esc_attr_e( 'Center', 'minimal-coming-soon-maintenance-mode' ); ?>
            </option>
            <option value="right" <?php selected( 'right', $signals_csmm_options['content_alignment'] ); ?>><?php esc_attr_e( 'Right', 'minimal-coming-soon-maintenance-mode' ); ?>
            </option>
          </select>

          <p class="signals-form-help-block">
            <?php esc_attr_e( 'For the alignment of the text on the maintenance page. Make it left, center, or right.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

      <?php
$animations = array(array('val' => '', 'label' => 'No animation'),
array('val' => '-1', 'disabled' => true, 'label' => 'Attention Seekers'),
array('val' => '-1', 'label' => '&nbsp;bounce'),
array('val' => '-1', 'label' => '&nbsp;flash'),
array('val' => '-1', 'label' => '&nbsp;pulse'),
array('val' => '-1', 'label' => '&nbsp;rubberBand'),
array('val' => '-1', 'label' => '&nbsp;shake'),
array('val' => '-1', 'label' => '&nbsp;swing'),
array('val' => '-1', 'label' => '&nbsp;tada'),
array('val' => '-1', 'label' => '&nbsp;wobble'),
array('val' => '-1', 'label' => '&nbsp;jello'),
array('val' => '-1', 'disabled' => true, 'label' => 'Bouncing Entrances'),
array('val' => '-1', 'label' => '&nbsp;bounceIn'),
array('val' => '-1', 'label' => '&nbsp;bounceInDown'),
array('val' => '-1', 'label' => '&nbsp;bounceInLeft'),
array('val' => '-1', 'label' => '&nbsp;bounceInRight'),
array('val' => '-1', 'label' => '&nbsp;bounceInUp'),
array('val' => '-1', 'disabled' => true, 'label' => 'Fading Entrances'),
array('val' => '-1', 'label' => '&nbsp;fadeIn'),
array('val' => '-1', 'label' => '&nbsp;fadeInDown'),
array('val' => '-1', 'label' => '&nbsp;fadeInDownBig'),
array('val' => '-1', 'label' => '&nbsp;fadeInLeft'),
array('val' => '-1', 'label' => '&nbsp;fadeInLeftBig'),
array('val' => '-1', 'label' => '&nbsp;fadeInRight'),
array('val' => '-1', 'label' => '&nbsp;fadeInRightBig'),
array('val' => '-1', 'label' => '&nbsp;fadeInUp'),
array('val' => '-1', 'label' => '&nbsp;fadeInUpBig'),
array('val' => '-1', 'disabled' => true, 'label' => 'Flippers'),
array('val' => '-1', 'label' => '&nbsp;flip'),
array('val' => '-1', 'label' => '&nbsp;flipInX'),
array('val' => '-1', 'label' => '&nbsp;flipInY'),
array('val' => '-1', 'disabled' => true, 'label' => 'Rotating Entrances'),
array('val' => '-1', 'label' => '&nbsp;rotateIn'),
array('val' => '-1', 'label' => '&nbsp;rotateInDownLeft'),
array('val' => '-1', 'label' => '&nbsp;rotateInDownRight'),
array('val' => '-1', 'label' => '&nbsp;rotateInUpLeft'),
array('val' => '-1', 'label' => '&nbsp;rotateInUpRight'),
array('val' => '-1', 'disabled' => true, 'label' => 'Sliding Entrances'),
array('val' => '-1', 'label' => '&nbsp;slideInUp'),
array('val' => '-1', 'label' => '&nbsp;slideInDown'),
array('val' => '-1', 'label' => '&nbsp;slideInLeft'),
array('val' => '-1', 'label' => '&nbsp;slideInRight'),
array('val' => '-1', 'disabled' => true, 'label' => 'Zoom Entrances'),
array('val' => '-1', 'label' => '&nbsp;zoomIn'),
array('val' => '-1', 'label' => '&nbsp;zoomInDown'),
array('val' => '-1', 'label' => '&nbsp;zoomInLeft'),
array('val' => '-1', 'label' => '&nbsp;zoomInRight'),
array('val' => '-1', 'label' => '&nbsp;zoomInUp'),
array('val' => '-1', 'disabled' => true, 'label' => 'Specials'),
array('val' => '-1', 'label' => '&nbsp;lightSpeedIn'),
array('val' => '-1', 'label' => '&nbsp;hinge'),
array('val' => '-1', 'label' => '&nbsp;jackInTheBox'),
array('val' => '-1', 'label' => '&nbsp;rollIn'));
?>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="animation" class="signals-strong">Content Intro Animation<sup>PRO</sup></label>
          <select name="animation" id="animation" class="skip-save pro-option">
            <?php CSMM::wp_kses_wf(csmm_create_select_options($animations, @$signals_csmm_options['animation'])); ?>
          </select>
          <p class="signals-form-help-block">When the page loads, the content will be animated on to the page with the selected animation. Use the <a
              href="https://comingsoonwp.com/content-animations/" target="_blank">animation previews</a> for easier picking. This is a <a href="#pro"
              class="csmm-change-tab">PRO feature</a>.</p>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_header_font" class="signals-strong"><?php esc_attr_e( 'Header Font', 'minimal-coming-soon-maintenance-mode' ); ?></label>

          <select name="signals_csmm_header_font" id="signals_csmm_header_font" class="signals-bunny-fonts">
            <option disabled>-- local, built-in fonts --</option>
            <option value="Arial" <?php selected( 'Arial', $signals_csmm_options['header_font'] ); ?>>Arial</option>
            <option value="Helvetica" <?php selected( 'Helvetica', $signals_csmm_options['header_font'] ); ?>>Helvetica</option>
            <option value="Georgia" <?php selected( 'Georgia', $signals_csmm_options['header_font'] ); ?>>Georgia</option>
            <option value="Times New Roman" <?php selected( 'Times New Roman', $signals_csmm_options['header_font'] ); ?>>Times New Roman</option>
            <option value="Tahoma" <?php selected( 'Tahoma', $signals_csmm_options['header_font'] ); ?>>Tahoma</option>
            <option value="Verdana" <?php selected( 'Verdana', $signals_csmm_options['header_font'] ); ?>>Verdana</option>
            <option value="Geneva" <?php selected( 'Geneva', $signals_csmm_options['header_font'] ); ?>>Geneva</option>
            <option disabled>-- externally loaded Bunny fonts --</option>
            <?php

							// Listing fonts from the array
							foreach ( $signals_bunny_fonts as $signals_font ) {
								CSMM::wp_kses_wf('<option value="' . $signals_font . '"' . selected( $signals_font, $signals_csmm_options['header_font'] ) . '>' . $signals_font . '</option>' . "\n");
							}

						?>
          </select>

          <h3><?php esc_attr_e( 'This is how the header font is going to look!', 'minimal-coming-soon-maintenance-mode' ); ?></h3>
          <p class="signals-form-help-block"><?php esc_attr_e( 'Font for the header text. Listing a total of 668 Bunny web fonts.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_secondary_font" class="signals-strong"><?php esc_attr_e( 'Content Font', 'minimal-coming-soon-maintenance-mode' ); ?></label>

          <select name="signals_csmm_secondary_font" id="signals_csmm_secondary_font" class="signals-bunny-fonts">
            <option disabled>-- local, built-in fonts --</option>
            <option value="Arial" <?php selected( 'Arial', $signals_csmm_options['secondary_font'] ); ?>>Arial</option>
            <option value="Helvetica" <?php selected( 'Helvetica', $signals_csmm_options['secondary_font'] ); ?>>Helvetica</option>
            <option value="Georgia" <?php selected( 'Georgia', $signals_csmm_options['secondary_font'] ); ?>>Georgia</option>
            <option value="Times New Roman" <?php selected( 'Times New Roman', $signals_csmm_options['secondary_font'] ); ?>>Times New Roman</option>
            <option value="Tahoma" <?php selected( 'Tahoma', $signals_csmm_options['secondary_font'] ); ?>>Tahoma</option>
            <option value="Verdana" <?php selected( 'Verdana', $signals_csmm_options['secondary_font'] ); ?>>Verdana</option>
            <option value="Geneva" <?php selected( 'Geneva', $signals_csmm_options['secondary_font'] ); ?>>Geneva</option>
            <option disabled>-- externally loaded Bunny fonts --</option>
            <?php

							// Listing fonts from the array
							foreach ( $signals_bunny_fonts as $signals_font ) {
								CSMM::wp_kses_wf('<option value="' . $signals_font . '"' . selected( $signals_font, $signals_csmm_options['secondary_font'] ) . '>' . $signals_font . '</option>' . "\n");
							}

						?>
          </select>

          <h3><?php esc_attr_e( 'This is how the content font is going to look!', 'minimal-coming-soon-maintenance-mode' ); ?></h3>
          <p class="signals-form-help-block"><?php esc_attr_e( 'Font for the content text. Listing a total of 668 Bunny web fonts.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_header_size" class="signals-strong"><?php esc_attr_e( 'Header Text Size', 'minimal-coming-soon-maintenance-mode' ); ?></label>

          <select name="signals_csmm_header_size" id="signals_csmm_header_size">
            <?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								CSMM::wp_kses_wf('<option value="' . $i . '"' . selected( $signals_csmm_options['header_font_size'], $i ) . '>' . $i . __( 'px', 'minimal-coming-soon-maintenance-mode' ) . '</option>');
							}

						?>
          </select>

          <p class="signals-form-help-block"><?php esc_attr_e( 'Font size for the header text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_secondary_size" class="signals-strong"><?php esc_attr_e( 'Content Text Size', 'minimal-coming-soon-maintenance-mode' ); ?></label>

          <select name="signals_csmm_secondary_size" id="signals_csmm_secondary_size">
            <?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								CSMM::wp_kses_wf('<option value="' . $i . '"' . selected( $signals_csmm_options['secondary_font_size'], $i ) . '>' . $i . __( 'px', 'minimal-coming-soon-maintenance-mode' ) . '</option>');
							}

						?>
          </select>

          <p class="signals-form-help-block"><?php esc_attr_e( 'Font size for the content text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_header_color" class="signals-strong"><?php esc_attr_e( 'Header Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input type="text" name="signals_csmm_header_color" id="signals_csmm_header_color"
            value="<?php esc_attr_e( $signals_csmm_options['header_font_color'] ); ?>"
            placeholder="<?php esc_attr_e( 'Font color for the Header text', 'minimal-coming-soon-maintenance-mode' ); ?>" class="jscolor signals-form-control color {required:false}">

          <p class="signals-form-help-block"><?php esc_attr_e( 'Select font color for the header text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_secondary_color" class="signals-strong"><?php esc_attr_e( 'Content Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input type="text" name="signals_csmm_secondary_color" id="signals_csmm_secondary_color"
            value="<?php esc_attr_e( $signals_csmm_options['secondary_font_color'] ); ?>"
            placeholder="<?php esc_attr_e( 'Font color for the content text', 'minimal-coming-soon-maintenance-mode' ); ?>" class="jscolor signals-form-control color {required:false}">

          <p class="signals-form-help-block"><?php esc_attr_e( 'Select font color for the content text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_antispam_size" class="signals-strong"><?php esc_attr_e( 'Antispam Text Size', 'minimal-coming-soon-maintenance-mode' ); ?></label>

          <select name="signals_csmm_antispam_size" id="signals_csmm_antispam_size">
            <?php

							// Loading font sizes with the help of a loop
							for ( $i = 10; $i < 21; $i++ ) {
								CSMM::wp_kses_wf('<option value="' . $i . '"' . selected( $signals_csmm_options['antispam_font_size'], $i ) . '>' . $i . __( 'px', 'minimal-coming-soon-maintenance-mode' ) . '</option>');
							}

						?>
          </select>

          <p class="signals-form-help-block"><?php esc_attr_e( 'Font size for the antispam text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_antispam_color" class="signals-strong"><?php esc_attr_e( 'Antispam Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <input type="text" name="signals_csmm_antispam_color" id="signals_csmm_antispam_color"
            value="<?php esc_attr_e( $signals_csmm_options['antispam_font_color'] ); ?>"
            placeholder="<?php esc_attr_e( 'Font color for the Antispam text', 'minimal-coming-soon-maintenance-mode' ); ?>" class="jscolor signals-form-control color {required:false}">

          <p class="signals-form-help-block"><?php esc_attr_e( 'Select font color for the antispam text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>
    </div>
  </div>
</div><!-- #design -->
