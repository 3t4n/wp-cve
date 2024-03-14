import React from 'react';
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

/**
 * Onboarding Title.
 */
export default class Title extends React.Component {

    /**
     * @return {JSX.Element}
     */
    render() {
        const popup = this.props.popup;
        let title = '';

        if ( popup.title ) {
            title = popup.title;
        } else if ( popup.step ) {
            title = this.props.label_step + popup.step
        }

        if ( title ) {
            return <Row>
                <Col className="title">
                    {title}
                </Col>
            </Row>;
        } else {
            return <></>;
        }
    }

}
