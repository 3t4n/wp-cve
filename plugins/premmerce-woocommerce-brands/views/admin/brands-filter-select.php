<?php if ( ! defined( 'WPINC' ) ) die; ?>

<select name="product_brand" id="product_brand_select">
	  <option value=""> <?php _e('Filter by brand', 'premmerce-brands') ?> </option>
	  <?php foreach ($brands as $brand):?>
        <?php $filterGetParam = isset($_GET['product_brand'])?$_GET['product_brand']:''?>
	  	<?php $selected = selected( $filterGetParam, $brand->slug, false ); ?>

  	    <option value="<?= $brand->slug; ?>" <?=$selected; ?> ><?= $brand->name; ?></option>

	  <?php endforeach; ?>
</select>