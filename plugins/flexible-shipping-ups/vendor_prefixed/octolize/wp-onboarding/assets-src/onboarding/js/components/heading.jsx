import React from 'react';
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

/**
 * Onboarding Heading.
 */
export default class Heading extends React.Component {

    /**
     * @return {JSX.Element}
     */
    render() {
        const text = this.props.text;

        return (
            <Row>
                <Col className="heading">
                    <h1>{text}</h1>
                    {this.props.sub_text &&
                        <p>{this.props.sub_text}</p>
                    }
                </Col>
            </Row>
        );
    }

}
