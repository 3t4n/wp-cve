<?php

use WordPress\Plugin\GalleryManager\{
    I18n
}

?>
<p><?php I18n::_e('To embed this gallery in another posts content you can use this <em>[gallery]</em> shortcode:') ?></p>
<p><input type="text" class="gallery-code" value="[gallery id=&quot;<?php echo get_The_ID() ?>&quot;]" readonly="readonly"></p>
<p><small><?php I18n::_e('Just copy this code to all places where this gallery should appear.') ?></small></p>
