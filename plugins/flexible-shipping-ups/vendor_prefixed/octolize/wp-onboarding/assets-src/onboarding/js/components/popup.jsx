import React from 'react';
import Modal from 'react-bootstrap/Modal'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Steps from "./steps";
import Title from "./title";
import Heading from "./heading";
import Buttons from "./buttons";
import Parser from 'html-react-parser';

/**
 * Onboarding Popup.
 */
export default class Popup extends React.Component {

    /**
     * @param {Object} props
     */
    constructor( props ) {
        super( props );

        this.state = {
            show: props.content.show,
            sending: false,
            modal_props: {},
            label_step: props.label_step || '',
            content: props.content || [],
            ajax: props.ajax || [],
            steps: props.steps || 0,
            logo_img: props.logo_img || '',
            assets_url: props.assets_url || ''
        };
        this.onClick = this.onClick.bind( this );
        this.onHide = this.onHide.bind( this );
        this.fieldValueChanged = this.fieldValueChanged.bind( this );
    }

    /**
     *
     */
    onHide() {
        let state = this.state;
        state.content.show = false;

        this.props.on_close_popup();

        this.setState( state );
    }

    /**
     * @param {Object} props
     * @param {Object} state
     * @return {Object}
     */
    static getDerivedStateFromProps( props, state ) {
        return {
            content: props.content,
            show: props.content.show,
        };
    }

    /**
     *
     * @param {object} button
     */
    onClick( button ) {
        this.props.on_button_click( button, this.props.id );
    }

    /**
     * @param {Event} e Event.
     */
    fieldValueChanged(e) {
        this.props.on_field_value_changed(this.props.popup_id, e.target.id, e.target.value, e.target.checked);
    }

    /**
     * @return {JSX.Element}
     */
    render() {
        let self = this;
        let props = this.state.modal_props;
        props.show = this.state.show;

        return (
            <Modal
                {...props}
                size="lg"
                aria-labelledby="contained-modal-title-vcenter"
                centered
                onHide={this.onHide.bind( this )}
                className={"octolize-onboarding-popup " + this.state.content.id}
                keyboard={false}
                backdrop={"static"}
            >
                <Modal.Header closeButton>
                    {this.state.logo_img &&
                        <div className="logo">
                            <img src={this.state.logo_img}/>
                        </div>
                    }
                </Modal.Header>
                <Modal.Body style={{
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center",
                }}>
                    <Container>
                        {this.state.steps > 1 &&
                            <Steps step={this.state.content.step} steps={this.state.steps} popup={this.state.content}
                                   label_step={this.state.label_step}/>
                        }
                        <Heading text={this.state.content.heading} sub_text={this.state.content.sub_heading}/>
                        {this.state.content.content.map((field, index) => {
                            return (
                                <Row key={index} className={"field " + field.type}>
                                    <Col className={"field " + field.type + " " + ( field.checked ? 'checked' : '' )}>
                                        { field.type === 'html' ?
                                            (
                                                <div id={field.id || ''} className={field.class || ''}>
                                                    {Parser( field.value )}
                                                </div>
                                            ) :
                                            (
                                                <>
                                                    <label htmlFor={field.id}>
                                                        {field.label}
                                                        {field.sublabel &&
                                                            <span className={"sublabel"}>{field.sublabel}</span>
                                                        }
                                                    </label>
                                                    <input
                                                        type={field.type}
                                                        value={field.value}
                                                        name={field.name}
                                                        id={field.id}
                                                        className={field.class || ''}
                                                        onChange={self.fieldValueChanged}
                                                        autoComplete={field.autocomplete}
                                                        checked={field.checked || false}
                                                    />
                                                </>
                                            )
                                        }
                                    </Col>
                                </Row>
                            )
                        })}
                    </Container>
                </Modal.Body>
                <Modal.Footer>
                    <Buttons buttons={this.state.content.buttons} popup={this}/>
                </Modal.Footer>
            </Modal>
        )
    }
}
