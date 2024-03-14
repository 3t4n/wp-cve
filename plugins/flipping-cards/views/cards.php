<script>

		jQuery(document).ready(function(){

			jQuery('#flipping-cards .remove').click(function(){

				var fc = jQuery(this).parent('.flipping-card');

				jQuery.post(ajaxurl, {action: 'remove_fc', id: jQuery(this).attr('rel'), _ajax_nonce: '<?= wp_create_nonce( "remove_fc" ); ?>' }, function(){

					jQuery(fc).remove();

				});

			});

		});

</script>

<h2>All flipping cards grids</h2>
<form action="" method="post" id="form_new_fc">
	<?php wp_nonce_field( 'new_fc' ) ?>
	<b>Add a new flipping card</b><br />
	<label>Name: </label><input type="text" name="name" /><br />
	<label>Image width: </label><input type="text" name="width" />px<br />
	<label>Image height: </label><input type="text" name="height" />px<br />
	<input type="submit" value="Add" class="buttons button-primary" />
</form>

<div id="flipping-cards">
<?php

if(sizeof($cards) > 0)
{

	foreach($cards as $card)
	{
		echo '<div class="flipping-card"><form action="" method="post">'.wp_nonce_field( 'update_fc_'.$card->id, '_wpnonce', true, false );
		echo '<label>Name: </label>';
		echo '<input type="text" name="name" value="'.$card->name.'" /><input type="hidden" name="id" value="'.$card->id.'" /><br />';
		echo '<label>Image width: </label>';
		echo '<input type="text" name="width" value="'.$card->width.'" />px<br />';
		echo '<label>Image height: </label>';
		echo '<input type="text" name="height" value="'.$card->height.'" />px<br />';
		echo '<input type="image" src="'.plugins_url( 'flipping-cards/images/save.png').'" title"Save" /><a href="'.admin_url().'?page=flipping_cards&id='.$card->id.'" title="Edit this flipping cards images"><img src="'.plugins_url( 'flipping-cards/images/images.png').'" class="action" /></a> <img title="Remove this flipping cards grid" class="remove action" rel="'.$card->id.'" src="'.plugins_url( 'flipping-cards/images/remove.png' ).'" />';
		echo '</form>
		Shortcode : <input type="text" readonly value="[flipping-card id='.$card->id.']" onClick="this.select()" /></div>';

	}

}

else

	echo 'No Flipping Cards created yet!';



?>

</div>

<h3>Need more options? Look at <a href="https://www.info-d-74.com/en/produit/flipping-cards-pro-plugin-wordpress-2/" target="_blank">Flipping Card Pro!</a> <a href="https://www.facebook.com/infod74/" target="_blank"><img src="<?php echo plugins_url( 'images/fb.png', dirname(__FILE__)) ?>" alt="" /></a></h3>
	<a href="https://www.info-d-74.com/produit/flipping-cards-pro-plugin-wordpress/" target="_blank">
		<img src="<?php echo plugins_url( 'images/pro.jpg', dirname(__FILE__)) ?>" alt="" />
	</a>