
const wpApeGalleryTypeDialog = function(event){
	event.preventDefault();
	window['apeGalleryTypeDialog'].dialog("open");
	return false;
};

var wpApeGalleryTypeDialogContent = document.getElementById('ape-gallery-type-select');

var themesPage = document.getElementsByClassName(wpApeGalleryThemesBodyClass);
if( themesPage.length > 0 ){

	var buttonAdd = themesPage[0].getElementsByClassName('page-title-action');
	if( buttonAdd.length > 0 ){

		/* for testing */
		//setTimeout(function(){ buttonAdd.click() }, 500);

		buttonAdd = buttonAdd[0];
		if( buttonAdd.addEventListener ){
		   buttonAdd.addEventListener( 'click', wpApeGalleryTypeDialog );
		   buttonAdd.addEventListener( 'dblclick', wpApeGalleryTypeDialog );
		}else if( buttonAdd.attachEvent ){
		   buttonAdd.attachEvent( 'onclick', wpApeGalleryTypeDialog);
		}
	}
}

/* Items */
var wpApeGalleryTypeDialogItems = wpApeGalleryTypeDialogContent.getElementsByClassName("type-grid-item");

var wpApeGalleryTypeDialogSelectItem = function(el){
	var items = wpApeGalleryTypeDialogItems,
		itemsCount = items.length,
		i;
	for (i = 0; i < itemsCount; i++) {
		items[i].className = items[i].className.replace(/\bactive\b/g, "");
	}
	el.className += " active";
};

var wpApeGalleryTypeDialogSelectPremiumItem = function(el){
	var url = el.getAttribute('data-url');
//	window.location.href = url;
	window.open( url, '_blank');
};


const wpApeGalleryGetLoading = function(){
	var loaderWrap = document.createElement('div');
	loaderWrap.style.textAlign = 'center';
	var loader = document.createElement('span');
	loader.className = 'spinner';
	loader.style.visibility = 'visible';
	loader.style.cssFloat = 'none';
	loaderWrap.appendChild(loader);
	return loaderWrap;
};

var wpApeGalleryTypeDialogOpenItem= function(){
	var items = wpApeGalleryTypeDialogItems,
		itemsCount = items.length,
		i;
	for (i = 0; i < itemsCount; i++) {
		if( (' ' + items[i].className + ' ').indexOf(' active ') > -1 ){
			var url = items[i].getAttribute('data-url');
			window.location.href = url;
			//wpApeGalleryTypeDialogContent.innerHTML = '';
			while (wpApeGalleryTypeDialogContent.firstChild) {
				wpApeGalleryTypeDialogContent.removeChild(wpApeGalleryTypeDialogContent.firstChild);
			}
			wpApeGalleryTypeDialogContent.appendChild( wpApeGalleryGetLoading() );
			return ;
		}
	}
};
