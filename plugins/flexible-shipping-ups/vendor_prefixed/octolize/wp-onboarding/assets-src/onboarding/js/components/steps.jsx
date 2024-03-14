import React from 'react';
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

/**
 * Onboarding Steps.
 */
export default class Steps extends React.Component {

    /**
     * @return {JSX.Element}
     */
    render() {
        const current_step = this.props.step;
        const steps = parseInt( this.props.steps, 10 );

        const popup = this.props.popup;
        let title = '';

        if ( popup.title ) {
            title = popup.title;
        } else if ( popup.step ) {
            title = this.props.label_step + popup.step
        }

        if ( ! current_step ) {
            return null;
        }

        let content = [];

        for ( let step = 1; step <= steps; step ++ ) {
            let key = "step-" + step;

            if ( step === current_step ) {
                content.push( <li key={key} className="active">&nbsp;</li> );
            } else {
                content.push( <li key={key}>&nbsp;</li> );
            }
        }

        return (
            <>
                <Row className="steps">
                    <Col className="title">
                        {title}
                    </Col>
                    <Col className="steps">
                        <ul>{content}</ul>
                    </Col>
                </Row>
            </>
        );
    }

}
