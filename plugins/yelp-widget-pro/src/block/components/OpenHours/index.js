import { __ } from "@wordpress/i18n";

export default function OpenHours( { hours = [] } ) {

    const getDay = ( day ) => {
        switch ( day ) {
            case 0:
                return __( 'Mon', 'yelp-widget-pro' );
            case 1:
                return __( 'Tue', 'yelp-widget-pro' );
            case 2:
                return __( 'Wed', 'yelp-widget-pro' );
            case 3:
                return __( 'Thu', 'yelp-widget-pro' );
            case 4:
                return __( 'Fri', 'yelp-widget-pro' );
            case 5:
                return __( 'Sat', 'yelp-widget-pro' );
            case 6:
                return __( 'Sun', 'yelp-widget-pro' );
            default:
                return '';
        }
    };

    const convertMilitaryTime = ( time ) => {

        time = time.replace(/(.{2})$/,':$1');
        time = time.split(':');

        const hours = Number(time[0]);
        const minutes = Number(time[1]);

        let timeValue;

        if (hours > 0 && hours <= 12) {
            timeValue= "" + hours;
        } else if (hours > 12) {
            timeValue= "" + (hours - 12);
        } else if (hours === 0) {
            timeValue= "12";
        }

        timeValue += (minutes < 10) ? ":0" + minutes : ":" + minutes;  // get minutes
        timeValue += (hours >= 12) ? " PM" : " AM";  // get AM/PM

        return timeValue;

    }

    const isCurrentDay = ( day ) => {
        const today = new Date().getDay();
        return day + 1 === today;
    }


    return (
        <div className={'rby-business-hours-wrap'}>
            {hours.map( ( hour, index ) => {
                return (
                    <div key={index} className={`rby-business-hours rby-business-hours__today-${isCurrentDay(hour.day)}`}>
                        <span className={'rby-business-hours__day'}>{getDay( hour.day )}</span>
                        <span className={'rby-business-hours__time'}>{convertMilitaryTime( hour.start )} - {convertMilitaryTime( hour.end )}</span>
                    </div>
                )
            } )}
        </div>
    );

}
