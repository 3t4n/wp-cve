var print_link = document.getElementById( 'print-link' );
if ( print_link ) {
	print_link.addEventListener( 'click', function(event) {
		print = window.open( this.href, 'print_win', 'width=1024, height=800, scrollbars=yes' );
		event.preventDefault();
	}, false);
}