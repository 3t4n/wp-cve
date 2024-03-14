import React, { Component } from 'react';
import $ from 'jquery';

class MPHB_Divi_Rooms_Module extends Component {

    static slug = 'mphb-divi-accommodations';

    /**
     *
     */
    componentDidMount() {

        setTimeout(() => this.initSlider(), 200);

    }

    componentDidUpdate(prevProps) {

        if (prevProps.__rooms !== this.props.__rooms) {
            setTimeout(() => this.initSlider(), 200);
        }

    }

    /**
     * Module render in VB
     */
    render() {

        return (
            <div dangerouslySetInnerHTML={{__html: this.props.__rooms}}></div>
        );

    }

    initSlider() {

        const flexsliderGalleries = $('.mphb-flexslider-gallery-wrapper');

        flexsliderGalleries.each(function (index, flexsliderGallery) {
            new window.MPHB.FlexsliderGallery(flexsliderGallery).initSliders();
            $(flexsliderGallery).closest('.type-mphb_room_type').on('click', 'a, .button', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
            });
        });

    }

}

export default MPHB_Divi_Rooms_Module;