/**
 * Inspector controls wrapper controls.
 * 
 */
import GeneralInspector from './general-inspector';
import StyleInspector from './style-inspector';

const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { InspectorControls } = wp.blockEditor;
const { TabPanel } = wp.components;

export default class Inspector extends Component {
    constructor( props ) {
        super( ...arguments );
    }

    render() {
        return (
            <InspectorControls>
                <TabPanel
                    className="cvmm-tabs-panel"
                    activeClass="active-tab"
                    tabs={[
                        {
                            name: "generalInspector",
                            title: escapeHTML( __( "General", "wp-magazine-modules" ) ),
                            className: "tab-panel"
                        },
                        {
                            name: "styleInspector",
                            title: escapeHTML( __( "Style", "wp-magazine-modules" ) ),
                            className: "tab-panel"
                        }
                    ]}
                >
                    {
                        ( tab ) => {
                            if( tab.name == "generalInspector" ) {
                                return <GeneralInspector { ...this.props }/>
                            } else if( tab.name == "styleInspector" ) {
                                return <StyleInspector { ...this.props }/>
                            }
                        }
                    }
                </TabPanel>
            </InspectorControls>
        ); 
    }
}   