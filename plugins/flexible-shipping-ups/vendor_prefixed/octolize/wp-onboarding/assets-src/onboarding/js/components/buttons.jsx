import React from 'react';
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Button from "react-bootstrap/Button";

/**
 * Onboarding Buttons.
 */
export default class Buttons extends React.Component {

    /**
     * @return {JSX.Element}
     */
    render() {
        const popup = this.props.popup;
        const buttons = this.props.buttons;

        if ( !buttons ) {
            return null;
        }

        let items = [];

        buttons.forEach( function ( item, index ) {
            items.push( <Button key={"button-" + index} onClick={() => popup.onClick( item )} variant="link"
                                className={item.classes}>{item.label}</Button> )
        } );

        return (
            <div>
                {items}
            </div>
        );
    }

}
