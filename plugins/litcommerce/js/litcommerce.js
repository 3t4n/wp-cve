jQuery( document ).on( 'submit', '#litcommerce-description form', function ( e ) {
	e.preventDefault();
	jQuery( '#litcommerce-description' ).hide();
	jQuery( '#litcommerce-progress' ).show();

	litcommcerceLaunchStep( 0 );
});

var currentStep;

function litcommerceFinishIntegration(result, isError, data ) {
	var $result = jQuery( '#litcommerce-result' );

	if ( isError ) {
		$result.html( result) ;
		$result.addClass( 'is-error' );
		return;
	}

	$result.removeClass( 'is-error' );
	$result.html( result + '<ul><li><b>Store URL:</b> ' + litcommerceStoreUrl + '</li><li><b>API Key:</b> ' + data[ 'consumer_key' ] + '</li><li><b>API Secret:</b> ' + data[ 'consumer_secret' ] + '</li><li><b>LitCommerce Connect URL:</b> <a href="' + data[ 'url' ] + '" target="_blank">' + data[ 'url' ] + '</a></li></ul>' );

	// try to open the URL in a new tab
	var win = window.open( data[ 'url' ], '_blank' );
	if ( win ) {
		// browser has allowed it to be opened
		win.focus();
	} else {
		// browser has blocked it, open in the same tab
		window.location = data[ 'url' ];
	}
}

function litcommerceStepResponseHandler(response ) {
	var data = response ? JSON.parse( response ) : null;

	if ( !data || !data.success ) {
		jQuery( '#litcommerce-step-' + currentStep ).addClass( 'step-failed' );
		litcommerceFinishIntegration( !data || !data.message ? defaultIntegrationError : data.message, true );
		return;
	}

	if ( currentStep + 1 === integrationStepCount ) {
		++currentStep;
		litcommmerceUpdateIntegrationProgress();
		litcommerceFinishIntegration( successfulIntegrationMessage, false, data.data );
		return;
	}

	litcommcerceLaunchStep( currentStep + 1 );
}

function litcommcerceLaunchStep(step ) {
	currentStep = step;
	litcommmerceUpdateIntegrationProgress();

	jQuery.ajax( {
		type: "POST",
		url: litcommerceBaseUrl,
		data: {
			action: 'litcommerce_integrate',
			step: currentStep
		}
	} ).always( litcommerceStepResponseHandler );
}

function litcommmerceUpdateIntegrationProgress() {
	for ( var i = 0; i < integrationStepCount; ++i ) {
		var $step = jQuery( '#litcommerce-step-' + i );
		$step.removeClass( 'step-in-progress' );
		$step.removeClass( 'step-complete' );
		$step.removeClass( 'step-failed' );

		if (i <= currentStep) {
			$step.addClass( ( i === currentStep ) ? 'step-in-progress' : 'step-complete' );
		}
	}
}
