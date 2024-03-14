<?php
/**
 * Author: Vitaly Kukin
 * Date: 31.10.2018
 * Time: 16:24
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="row py-5">
	<div class="col text-center">
		<h4>
		<?php
			if( ! isset( $_GET[ 'mystore' ] ) )
				_e( 'There are no products that meet these requirements. Please modify your request.', 'dm' );
			else
				_e( ' You have not imported any products yet.', 'dm' );
		?>
		</h4>
	</div>
</div>
