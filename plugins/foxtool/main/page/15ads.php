<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('ADS', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check15" data-target="play15" type="checkbox" name="foxtool_settings[ads]" value="1" <?php if ( isset($foxtool_options['ads']) && 1 == $foxtool_options['ads'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play15" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-rectangle-ad"></i> <?php _e('Ads appear when clicking on the website', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[ads-click1]" value="1" <?php if ( isset($foxtool_options['ads-click1']) && 1 == $foxtool_options['ads-click1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable annoying ads', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[ads-click-c1]" value="1" <?php if ( isset($foxtool_options['ads-click-c1']) && 1 == $foxtool_options['ads-click-c1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable pop-up ads', 'foxtool'); ?></label>
	</p>
	<p>
	<input class="ft-input-small" name="foxtool_settings[ads-click-c2]" type="number" placeholder="24" value="<?php if(!empty($foxtool_options['ads-click-c2'])){echo sanitize_text_field($foxtool_options['ads-click-c2']);} ?>"/>
	<label class="ft-label-right"><?php _e('Ads display after (.. hours)', 'foxtool'); ?></label>
	</p>
	<p>
	<textarea style="height:150px;" class="ft-code-textarea" name="foxtool_settings[ads-click11]" placeholder="<?php _e('Enter ad links per line', 'foxtool'); ?>"><?php if(!empty($foxtool_options['ads-click11'])){echo esc_textarea($foxtool_options['ads-click11']);} ?></textarea>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable and enter ad links you want, each on a separate line, for automatic rotation each time a user visits', 'foxtool'); ?></p>
</div>