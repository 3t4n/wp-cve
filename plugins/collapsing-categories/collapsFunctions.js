/*  Collapse Functions, version 3.0
 *
 *--------------------------------------------------------------------------*/
String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g,"");
}

function collapsCatCreateCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  } else {
    var expires = "";
  }
  document.cookie = name+"="+value+expires+"; path=/;SameSite=Strict";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') {
      c = c.substring(1,c.length);
    }
    if (c.indexOf(nameEQ) == 0) {
      return c.substring(nameEQ.length,c.length);
    }
  }
  return null;
}

function eraseCookie(name) {
  collapsCatCreateCookie(name,"",-1);
}

function addExpandCollapseCat(widgetRoot, expandSym, collapseSym, accordion) {
	widgetRoot.querySelectorAll( 'span.collapsing-categories').forEach(item => {
		item.addEventListener('click', event => {
			let theLink = item.querySelector('a');
			if ( theLink ) {
				// This is to support the option of expanding and collapsing only, and
				// not linking to the category archive (linkToCat=false)
				theLink.removeAttribute('href');
			}
			if (accordion) {
				let theSpan = item.parentElement.parentElement.querySelector('span.collapse');
				// If we are collapsing the one item which is expanded then we don't
				// want to change the other items, thus we only do this when the item
				// and theSpan are different
				if ( theSpan && theSpan != item ) {
					let theDiv = theSpan.parentElement.querySelector('div');
					let divId = theDiv.getAttribute("id");
					theDiv.style.display = 'none';
					theSpan.classList.remove('collapse');
					theSpan.classList.add('expand');
					collapsCatCreateCookie(divId, 0, 7);
				}
			  widgetRoot.querySelectorAll( '.expand .sym').forEach(item => { item.innerHTML = expandSym;});
			}
			expandCollapseCat(item, expandSym, collapseSym, accordion );
		})
	});
}

function expandCollapseCat(symbol, expandSym, collapseSym, accordion ) {
		let newDiv = symbol.parentElement.querySelector('div');
		let divId = newDiv.getAttribute("id");

		newDiv.innerHTML = collapsItems[divId];
		// calling again here to add to sub-categories, which may not have been in the DOM before
		addExpandCollapseCat( newDiv, expandSym, collapseSym, accordion );
	//newDiv.style.maxHeight = newDiv.scrollHeight + "px";
	if (symbol.classList.contains('expand')) {
		newDiv.style.display = 'block';
		symbol.classList.remove('expand');
		symbol.classList.add('collapse');
		symbol.querySelector('.sym').innerHTML = collapseSym;
		collapsCatCreateCookie(divId, 1, 7);
	} else {
		newDiv.style.display = 'none';
		symbol.classList.remove('collapse');
		symbol.classList.add('expand');
		symbol.querySelector('.sym').innerHTML = expandSym;
		collapsCatCreateCookie(divId, 0, 7);
	}
	return false;
}

