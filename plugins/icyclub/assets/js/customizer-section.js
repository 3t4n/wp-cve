( function( api ) {

	// Extends our custom "example-1" section.
	api.sectionConstructor['plugin-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );


function consultupfrontpagesectionsscroll( section_id ){
    var scroll_section_id = "theme-slider";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {
        
        case 'accordion-section-slider_section':
        scroll_section_id = "slider-section";
        break;

        case 'accordion-section-services_section':
        scroll_section_id = "service-section";
        break;

        case 'accordion-section-home_callout_section':
        scroll_section_id = "callout-section";
        break;

        case 'accordion-section-project_section':
        scroll_section_id = "portfolio-section";
        break;

        case 'accordion-section-testimonial_section':
        scroll_section_id = "testimonial-section";
        break;

        case 'accordion-section-news_section':
        scroll_section_id = "news-section";
        break;
    }

    if( $contents.find('#'+scroll_section_id).length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + scroll_section_id ).offset().top
        }, 1000);
    }
}

 jQuery('body').on('click', '#sub-accordion-panel-homepage_sections .control-subsection .accordion-section-title', function(event) {
        var section_id = jQuery(this).parent('.control-subsection').attr('id');
        consultupfrontpagesectionsscroll( section_id );
});