<input type='hidden' name='shop_url' value='<?php echo esc_attr( ewd_uwcf_get_shop_url() ); ?>' />
<input type='hidden' name='min_price' value='<?php echo esc_attr( isset( $_GET['min_price'] ) ? esc_attr( intval( $_GET['min_price'] ) ) : 0 ); ?>' />
<input type='hidden' name='max_price' value='<?php echo esc_attr( isset( $_GET['max_price'] ) ? esc_attr( intval( $_GET['max_price'] ) ) : 0 ); ?>' />