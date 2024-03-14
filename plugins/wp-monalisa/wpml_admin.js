/**
 * Javascript functions for wp-monalisa admin dialog.
 *
 * @package wp-monalisa
 */

/**
 * Diese funktion aktualisiert die icons
   wenn in der select box ein neues icon ausgewählt wurde
   icondir gibt die url des smiley-verzeichnises an
   myid ist die id des anzusprechenden img tags.
 */
function updateImage(icondir,myid){
	var svar, ivar;
	if ( myid == "NEW" ) {
		ivar = "icoimg";
		svar = "NEWicon";
	} else {
		ivar = "icoimg" + myid;
		svar = "icon" + myid;
	}
	image  = document.getElementById( ivar );
	select = document.getElementById( svar );
	image.setAttribute( "src",icondir + "/" + select.value );
}


/*
  verschiebt die zeile mit id tid um eins nach oben oder unten.
*/
function switch_row(tid,direction)
{

	// finde naechste oder vorherige id der zeile.
	if ( direction == "up" ) {
		sid = tid - 1;
		while ( ! document.getElementById( "mark" + sid ) && sid > 0) {
			sid--;
		}
	} else {
		sid = tid + 1;
		while ( ! document.getElementById( "mark" + sid ) && sid < 9999) {
			sid++;
		}

	}

	// variablen fuer zwischenspeicherung.
	var mark1,icon1,icoimg1,onpost1,oncomment1;
	var mark2,icon2,icoimg2,onpost2,oncomment2;
	var Umark,Uicon,Uicoimg,Uonpost,Uoncomment;
	var Omark,Oicon,Oicoimg,Oonpost,Ooncomment;

	// objekte der unteren zeile merken.
	mark1      = document.getElementById( "mark" + tid );
	emoticon1  = document.getElementById( "emoticon" + tid );
	icon1      = document.getElementById( "icon" + tid );
	icoimg1    = document.getElementById( "icoimg" + tid );
	onpost1    = document.getElementById( "onpost" + tid );
	oncomment1 = document.getElementById( "oncomment" + tid );

	// objekte der oberen zeile merken.
	mark2      = document.getElementById( "mark" + sid );
	emoticon2  = document.getElementById( "emoticon" + sid );
	icon2      = document.getElementById( "icon" + sid );
	icoimg2    = document.getElementById( "icoimg" + sid );
	onpost2    = document.getElementById( "onpost" + sid );
	oncomment2 = document.getElementById( "oncomment" + sid );

	// werte der unteren zeile merken.
	Umark      = mark1.checked;
	Uemoticon  = emoticon1.value;
	Uicon      = icon1.value;
	Uicoimg    = icoimg1.getAttribute( "src" );
	Uonpost    = onpost1.checked;
	Uoncomment = oncomment1.checked;

	// werte der oberen zeile merken.
	Omark      = mark2.checked;
	Oemoticon  = emoticon2.value;
	Oicon      = icon2.value;
	Oicoimg    = icoimg2.getAttribute( "src" );
	Oonpost    = onpost2.checked;
	Ooncomment = oncomment2.checked;

	// werte von oberer zeile nach unten kopieren.
	mark1.checked = Omark;
	emoticon1.value = Oemoticon;
	icon1.value = Oicon;
	icoimg1.setAttribute( "src",Oicoimg );
	onpost1.checked = Oonpost;
	oncomment1.checked = Ooncomment;

	// werte von unterer zeile nach oben kopieren.
	mark2.checked = Umark;
	emoticon2.value = Uemoticon;
	icon2.value = Uicon;
	icoimg2.setAttribute( "src",Uicoimg );
	onpost2.checked = Uonpost;
	oncomment2.checked = Uoncomment;

}

function wpml_markall(objid)
{
	var i;
	// get newvalue for checkboxes.
	newval  = document.getElementById( objid ).checked;

	// set top and bottom checkbox to new value.
	document.getElementById( "markall" ).checked  = newval;
	document.getElementById( "markall1" ).checked = newval;

	// set row checkboxes to new value.
	list = document.getElementsByClassName( "wpml_mark" );
	var l = list.length;
	for (i = 0; i < l; i++) {
		list[i].checked = newval;
	}
}

function wpml_admin_switch()
{
	v = document.editopts.showicon.value;
	if ( v == 1) { // show only icon.
		document.editopts.showastable.disabled = false;
	} else {
		document.editopts.showastable.disabled = true;
	}

	w = document.editopts.showastable.checked;
	if ( w == true) { // showastable is active.
		document.editopts.smiliesperrow.disabled = false;
	} else {
		document.editopts.smiliesperrow.disabled = true;
	}

	t = document.editopts.showaspulldown.checked;
	if ( t == true) { // showaspulldown is active.
		document.editopts.smilies1strow.disabled = false;
	} else {
		document.editopts.smilies1strow.disabled = true;
	}

	if (document.editopts.wpml4bbpress && typeof(document.editopts.wpml4buddypress) != "undefined") {
		u = document.editopts.wpml4buddypress.checked;
	} else {
		u = false;
	}

	if ( u == true) { // buddypress is active.
		document.editopts.replaceicon.checked = false;
		document.editopts.replaceicon.disabled = true;
	} else {
		document.editopts.replaceicon.disabled = false;
	}

	if (document.editopts.wpml4bbpress && typeof(document.editopts.wpml4bbpress.checked) != "undefined") {
		u = document.editopts.wpml4bbpress.checked;
	} else {
		u = false;
	}

	if ( u == true) { // bbPress is active.
		document.editopts.replaceicon.checked = false;
		document.editopts.replaceicon.disabled = true;
	} else {
		document.editopts.replaceicon.disabled = false;
	}
}
