var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	return new bootstrap.Popover(popoverTriggerEl);
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl);
});

var modals = document.getElementsByClassName("modal");
var modal_links = [];
for (var i = 0; i < modals.length; i++) {
	var modal = modals.item(i);
	var links = document.querySelectorAll("[href=\'#" + modal.id + "\']");
	if ( !links.length ) {
		continue;
	}
	for (var modal_i = 0; modal_i < links.length; modal_i++) {
		modal_links.push( links.item(modal_i) );
	}
}
if ( modal_links.length ) {
	modal_links.forEach( function( link ) {
		link.addEventListener("click", function(event) {
			event.preventDefault();
			var id = link.href.split("#");
			var modal = new bootstrap.Modal(document.getElementById(id[1]), {
				keyboard: false
			});
			modal.show();
		}, false );
	});
}

var collapses = document.getElementsByClassName("collapse");
var collapse_links = [];
for (var i = 0; i < collapses.length; i++) {
	var collapse = collapses.item(i);
	var links = document.querySelectorAll("[href=\'#" + collapse.id + "\']");
	
	if ( !links.length ) {
		continue;
	}
	for (var link_i = 0; link_i < links.length; link_i++) {
		collapse_links.push( links.item(link_i) );
	}
}
if ( collapse_links.length ) {
	collapse_links.forEach( function( link ) {
		link.addEventListener("click", function(event) {
			event.preventDefault();
			var id = link.href.split("#");
			var collapse = new bootstrap.Collapse(document.getElementById(id[1]), {
				keyboard: false
			});
			collapse.toggle();
		}, false );
	});
}

var offcanvass = document.getElementsByClassName("offcanvas");
var offcanvas_links = [];
for (var i = 0; i < offcanvass.length; i++) {
	var offcanvas = offcanvass.item(i);
	var links = document.querySelectorAll("[href=\'#" + offcanvas.id + "\']");
	
	if ( !links.length ) {
		continue;
	}
	for (var link_i = 0; link_i < links.length; link_i++) {
		offcanvas_links.push( links.item(link_i) );
	}
}
if ( offcanvas_links.length ) {
	offcanvas_links.forEach( function( link ) {
		link.addEventListener("click", function(event) {
			event.preventDefault();
			var id = link.href.split("#");
			var offcanvas = new bootstrap.Offcanvas(document.getElementById(id[1]), {
				keyboard: false
			});
			offcanvas.show();
		}, false );
	});
}

var toasts = document.getElementsByClassName("toast");
var toast_links = [];
for (var i = 0; i < toasts.length; i++) {
	var toast = toasts.item(i);
	var links = document.querySelectorAll("[href=\'#" + toast.id + "\']");
	
	if ( !links.length ) {
		continue;
	}
	for (var link_i = 0; link_i < links.length; link_i++) {
		toast_links.push( links.item(link_i) );
	}
}
if ( toast_links.length ) {
	toast_links.forEach( function( link ) {
		link.addEventListener("click", function(event) {
			event.preventDefault();
			var id = link.href.split("#");
			var toast = new bootstrap.Toast(document.getElementById(id[1]), {
				keyboard: false,
			});
			toast.show();
		}, false );
	});
}

jQuery(document).ready(function($){

	$( '.areoi-tabs' ).each( function() {

		var active = $( this ).find( '.nav a.active:first-of-type' );

		$( this ).find( '.nav a.active:not(:first-of-type)' ).removeClass( 'active' );

		$( this ).find( '> div' ).addClass( 'tab-pane d-none' );

		var active_tab = $( this ).find( active.attr( 'href' ) );

		if ( active_tab ) {
			active_tab.removeClass( 'd-none' );
		}
	});

	$( document ).on( 'click', '.areoi-tabs .nav a', function( e ) {
		e.preventDefault();
		var container = $( this ).parents( '.areoi-tabs' );
		var active_tab = container.find( $( this ).attr( 'href' ) );
		
		container.find( '.nav a' ).removeClass( 'active' );
		$( this ).addClass( 'active' );

		if ( active_tab ) {
			container.find( '> div' ).addClass( 'd-none' );
			active_tab.removeClass( 'd-none' );
		}
	} );

});