<?php

namespace WPDeskFIVendor;

/**
 * File: footer.php
 */
?>
<div class="fix"></div>
<input type="hidden" name="document_id" value="<?php 
echo \esc_attr($correction->get_id());
?>"/>
<input type="hidden" name="order_id" value="<?php 
echo \esc_attr($correction->get_order_id());
?>"/>
</div>
</body>
</html>
<?php 
