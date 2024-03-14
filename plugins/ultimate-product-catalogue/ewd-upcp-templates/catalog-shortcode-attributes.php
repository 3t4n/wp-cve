<input type='hidden' name='catalog-id' value='<?php echo esc_attr( $this->catalog->ID ); ?>' />
<input type='hidden' name='catalog-excluded-views' value='<?php echo esc_attr( implode( ',', $this->excluded_views ) ); ?>' />
<input type='hidden' name='catalog-current-page' value='<?php echo esc_attr( $this->current_page ); ?>' />
<input type='hidden' name='catalog-max-page' value='<?php echo esc_attr( $this->max_pages ); ?>' />
<input type='hidden' name='catalog-order-by' value='<?php echo esc_attr( $this->orderby ); ?>' />
<input type='hidden' name='catalog-order' value='<?php echo esc_attr( $this->order ); ?>' />
<input type='hidden' name='catalog-product-per-page' value='<?php echo esc_attr( $this->products_per_page ); ?>' />
<input type='hidden' name='catalog-default-search-text' value='<?php _e( 'Search...', 'ultimate-product-catalogue' ); ?>' />
<input type='hidden' name='catalog-base-url' value='<?php echo esc_attr( $this->ajax_url ); ?>' />