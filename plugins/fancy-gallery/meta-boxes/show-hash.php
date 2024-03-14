<?php

use WordPress\Plugin\GalleryManager\{
    I18n
}

?>
<p><?php I18n::_e('To start this gallery in a lightbox by clicking a link you can link to this <em>#hash</em>:') ?></p>
<p><input type="text" class="gallery-hash" value="#gallery-<?php echo get_The_ID() ?>" readonly="readonly"></p>
<p><small><?php I18n::_e('Just use this hash as link target (href).') ?></small></p>
