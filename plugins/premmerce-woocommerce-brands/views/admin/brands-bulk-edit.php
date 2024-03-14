<?php if ( ! defined( 'WPINC' ) ) die; ?>

<div class="inline-edit-group">
    <label class="alignleft">
        <span class="title"><?php _e('Brand', 'premmerce-brands'); ?></span>
        <span class="input-text-wrap">
    	   <select name="product_brand" id="product_brand_select">
    	 	  <option value=""> <?php _e( '— No change —', 'woocommerce' ) ?> </option>
              <option value="not_specified"> <?php _e( 'Not specified', 'premmerce-brands' ) ?> </option>
              
    	 	  <?php foreach ($brands as $brand):?>
    		  	   <option value="<?= $brand->slug; ?>"><?= $brand->name; ?></option>
    	  	  <?php endforeach; ?>
    	    </select>
        </span>
    </label>
</div>