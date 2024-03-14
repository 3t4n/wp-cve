/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import tb_generateCSS from "./tb_generateCSS"

function tb_styling( props, id ) {

	const {
		boxbgColor,
        titleColor,
        primaryColor,
        secondaryColor,
        postmetaColor,
        postexcerptColor,
        postctaColor,
        socialShareColor,
        readmoreBgColor,
        timelineBgColor,
        timelineFgColor,
        titlefontSize,
        postmetafontSize,
        postexcerptfontSize,
        postctafontSize,
        socialSharefontSize,
        belowTitleSpace,
        belowImageSpace,
        belowexerptSpace,
        belowctaSpace,
        innerSpace,
        titleFontFamily,
        titleFontWeight,
        titleFontSubset,
        excerptFontFamily,
        excerptFontWeight,
        excerptFontSubset,
        metaFontFamily,
        metaFontSubset,
        metafontWeight,
        ctaFontFamily,
        ctaFontSubset,
        ctafontWeight,
	} = props.attributes

	var selectors = {
		" .tb-content-wrap" : {
			"padding-left" : innerSpace + "px",
			"padding-right" : innerSpace + "px",
			"margin-bottom" : innerSpace + "px",
		},
		" .tb-image": {
			"padding-bottom" : belowImageSpace + "px",
		},
		" div.tb-blogpost-title": {
			"padding-bottom" : belowTitleSpace + "px",
		},
		" .tb-blogpost-excerpt a.tb-link, .text-only a.tb-link, div.text-only ": {
			"font-size": postctafontSize + "px",
			"font-family": ctaFontFamily,
			"font-weight": ctafontWeight,
			"color": postctaColor + "!important",
			"margin-bottom" : belowctaSpace + "px",
		},
		" .tb-blogpost-excerpt a.tb-button, .tb-button-view a.tb-button ": {
			"border-radius": '5px',
			"padding": '10px 20px',
			"background-color": readmoreBgColor + "!important",
			"font-size": postctafontSize + "px",
			"font-family": ctaFontFamily,
			"font-weight": ctafontWeight,
			"color": postctaColor + "!important",
			"margin-bottom" : belowctaSpace + "px",
		},
		" .tb-button-view ": {
			"margin-bottom" : belowctaSpace + "px",
		},
		".tb-timeline-template1 .tb-timeline-item .tb-timeline-content:before" : {
		    "border-left-color" : boxbgColor + "!important",
		},
		".tb-timeline-template1 .tb-timeline-item:nth-child(even) .tb-timeline-content:before" : {
		    "border-right-color" : boxbgColor + "!important",
		},
		".tb-timeline-template1:before " : {
			"background" : timelineBgColor + " !important",
		},
		" .tb-title .tb-layout-1" : {
		    "background" : boxbgColor + "!important",
		},
		" .tb-is-list .tb-category-link-wraper div.category-link a": {
			"background": primaryColor + "!important",
			"color": secondaryColor + "!important",
		},
		" .tb-items-2 .tb-category-link-wraper": {
			"background": secondaryColor + "!important",
		},
		"  .tb-items-2 .tb-blogpost-bototm-wrap": {
			"border-top": "2px solid" + secondaryColor + "!important",
			"border-bottom": "2px solid" + secondaryColor + "!important",
		},
		" .tb-blogpost-excerpt p": {
			"font-family": excerptFontFamily,
			"font-weight": excerptFontWeight,
			"color" : postexcerptColor + "!important",
			"font-size" : postexcerptfontSize + "px",
			"margin-bottom" : belowexerptSpace + "px",
		},
		" .tb-blogpost-title a": {
			"font-family": titleFontFamily,
			"font-weight": titleFontWeight,
			"color" : titleColor + " !important",
			"font-size" : titlefontSize + "px",
		},
		" .tb-blogpost-author a, .tb-timeline-post-tags a, .tb-timeline-category-link a, .mdate span, .post-comments": {
			"font-family": metaFontFamily + " !important",
			"font-weight": metafontWeight + " !important",
			"color" : postmetaColor + " !important",
			"font-size" : postmetafontSize + "px" + " !important",
    		//"text-transform" : "uppercase",
		},
		" .mdate i, .post-comments i, .post-author i": {
			"font-weight": metafontWeight + " !important",
			"color" : postmetaColor + " !important",
			"font-size" : postmetafontSize + "px" + " !important",
		},
		" .tb-blogpost-byline div": {
			"font-family": metaFontFamily,
			"font-weight": metafontWeight,
			"color" : postmetaColor + "!important",
			"font-size" : postmetafontSize + "px",
		},
		" .tb-social-wrap .social-share-data a": {
			"padding" : '0px 5px',
			"display" : "table-cell",
			"vertical-align" : "middle",
			"color" : socialShareColor + " !important",
			"font-size" : socialSharefontSize + "px",
		},
		".tb-timeline-template2 .tb-timeline-item .timeline-icon, .tb-timeline-template2:before " : {
			"background" : timelineBgColor + " !important",
		},
		" .timeline-icon path" : {
		    "fill" : timelineFgColor + "!important",
		},
		" .tb-svg-icon" : {
			"fill" : timelineFgColor + "!important",
		},
	}

	var tb_styling_css = ""

	tb_styling_css = tb_generateCSS( selectors, `#${id}-${ props.clientId }` )

	return tb_styling_css
}

export default tb_styling
