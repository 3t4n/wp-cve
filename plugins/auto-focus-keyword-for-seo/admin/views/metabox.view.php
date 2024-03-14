<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="misc-pub-section misc-pub-section-last afkw-container"><span id="timestamp">

    <label class="afkw-label">Disable Auto Focus Keyword</label>
    <label class="afkw-toggle"><input type="checkbox" name="disable_afk" value="disable_afk" <?php 
        if ( isset($disable_afk) && !empty($disable_afk) ) { echo  'checked' ;  }
    ?> /> /><span class='afkw-toggle-slider afkw-toggle-round'></span></label>

    <p><?php echo esc_html__('Checking this box will disable auto focus keyword for this page.', 'auto-focus-keyword-for-seo'); ?></p>

</div>
