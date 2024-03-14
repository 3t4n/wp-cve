jQuery(document).ready(function($){
	let dragging = false;

	let roundNumber = ( num, decimals = 2 ) => +( Math.round( num + 'e+' + decimals )  + 'e-' + decimals );

	let moveHandle = e => {
		let $image = $('.focal-point-area img');
		$('.focal-point-handle').css({
			top: Math.min( roundNumber( $image.height(), 0 ), Math.max( 0, e.clientY - $image.offset().top + window.scrollY ) ),
			left: Math.min( roundNumber( $image.width(), 0 ), Math.max( 0, e.clientX - $image.offset().left + window.scrollX ) )
		});
	}

	let updateValues = () => {
		let $handle = $('.focal-point-handle');
		let $image = $('.focal-point-area img');
		let top = ( $handle.offset().top - $image.offset().top + 15 ) / roundNumber( $image.height(), 0 );
		let left = ( $handle.offset().left - $image.offset().left + 15 ) / roundNumber( $image.width(), 0 );
		$('.focal-point-top').attr( 'data-value', roundNumber( top, 6 ) ).text( Math.max( 0, Math.min( 100, roundNumber( top * 100, 0 ) ) ) );
		$('.focal-point-left').attr( 'data-value', roundNumber( left, 6 ) ).text( Math.max( 0, Math.min( 100, roundNumber( left * 100, 0 ) ) ) );
		$('.focal-point-previews img').css( 'object-position', `${ left * 100 }% ${ top * 100 }%` );
	}

	$(document)
	.on('mouseup', function(){
		if( dragging ){
			dragging = false;
			$('.focal-point-area').removeClass('dragging');
			updateValues();
		}
	})
	.on('mousedown', '.focal-point-area', function(e){
		if( e.button == 0 ){
			dragging = true;
			$('.focal-point-area').addClass('dragging');
			moveHandle( e );
		}
	})
	.on('mousemove', function(e){
		if( dragging ){
			moveHandle( e );
			updateValues();
		}
	})
	.on('click', '.pick-focal-point', function(e){
		e.preventDefault();
		$(this).hide();
		$('.save-focal-point').show();
		$('.focal-point-area').show();
		$('.focal-point-previews').css('display', 'grid');

		[ handle_left, handle_top ] = $('.focal-point-input').val().split(';');
		handle_top = parseFloat( handle_top ) * 100;
		handle_left = parseFloat( handle_left ) * 100;
		$('.focal-point-handle').css({
			top: handle_top + '%',
			left: handle_left + '%'
		});
		$('.focal-point-previews img').css( 'object-position', `${handle_left}% ${handle_top}%` );
	})
	.on('click', '.save-focal-point', function(e){
		e.preventDefault();
		$(this).hide();
		$('.pick-focal-point').show();
		$('.focal-point-area, .focal-point-previews').hide();
		let top = parseFloat( $('.focal-point-top').attr('data-value') );
		let left = parseFloat( $('.focal-point-left').attr('data-value') );
		$('.focal-point-input').val( Math.max( 0, Math.min( 1, left ) ) + ';' + Math.max( 0, Math.min( 1, top ) ) ).trigger('change');
	});
});