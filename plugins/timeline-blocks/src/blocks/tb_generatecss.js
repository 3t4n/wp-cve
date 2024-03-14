function tb_generateCSS ( selectors, id, isResponsive = false, responsiveType = "" ) {

	var tb_styling_css = ""
	
	for( var i in selectors ) {

		tb_styling_css += id

		tb_styling_css += i + " { "

		var sel = selectors[i]
		var css = ""

		for( var j in sel ) {

			css += j + ": " + sel[j] + ";"
		}

		tb_styling_css += css + " } "
	}

	// if ( isResponsive ) {
	// 	styling_css += " }"
	// }

	return tb_styling_css
}

export default tb_generateCSS
