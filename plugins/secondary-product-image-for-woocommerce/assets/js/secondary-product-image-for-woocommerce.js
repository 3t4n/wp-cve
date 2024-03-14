// Access the product list
var productList = document.querySelector('.products');

if( ! productList ) {
	productList = document.querySelector('.products-block-post-template'); 
}

// Attach mouseover and mouseout event listeners to each product item
if ( productList ) {
	productList.addEventListener( 'mouseover', function (event) {
		
		var target = event.target;
		var parent = target.parentNode;		
		var secondaryWrapper = parent.querySelector('.wpzoom-secondary-image-container');
	
		// Add the class to show the .wpzoom-secondary-image-container with animation
		if ( secondaryWrapper ) secondaryWrapper.classList.add('show-secondary-image');
		
	});
}