import React, { Component } from 'react';
import $ from 'jquery';

class MPHB_Divi_Availability_Calendar_Module extends Component {

    static slug = 'mphb-divi-availability-calendar';

    constructor(props) {
        super(props);

        this.calendar = React.createRef();
    }

    componentDidMount() {
        this.initCalendar();
    }

    componentDidUpdate() {
        this.initCalendar();
    }

    initCalendar() {
        const calendarEl = $(this.calendar.current).find('.mphb-calendar.mphb-datepick');

        if ( window.MPHB ) {
            new window.MPHB.RoomTypeCalendar($(calendarEl));
        }
    }

    /**
     * Module render in VB
     */
    render() {

        return <div
            ref={ this.calendar }
            dangerouslySetInnerHTML={ { __html: this.props.__calendar } }
        ></div>;

    }

}

export default MPHB_Divi_Availability_Calendar_Module;