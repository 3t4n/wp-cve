<?php if ( $status == 'offer_selected' ) : ?>
    <p class="easypack_error" style="color:red; font-weight:bold;">
        <?php
        echo sprintf( '%1s <a href="https://manager.paczkomaty.pl/auth/login" target="_blank" style="color:blue;">%1s</a> %1s',
            __('The package has not been created! You do not have funds in', 'woocommerce-inpost'),
            __('your Parcel Manager account', 'woocommerce-inpost' ),
            __('or a contract for InPost services.', 'woocommerce-inpost' )
        );?>
	</p>
	<p class="easypack_error" style="color:red; font-weight:bold;">	
		<?php echo __('Re-creating a package is possible in the Package Manager after topping up funds.', 'woocommerce-inpost' ); ?>
    </p>
<?php endif; ?>