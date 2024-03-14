import LayoutInspector from './layout-inspector';
import AdvancedInspector from './advanced-inspector';
import StyleInspector from './style-inspector';

// Internationalization
const { __ } = wp.i18n;

// Extend component
const { Component } = wp.element;

// import Block component
const { InspectorControls } = wp.blockEditor;

// import Inspector components
const { TabPanel } = wp.components;


/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
    render() {
        const {
            attributes: {

            }
        } = this.props;


        return (
            <InspectorControls key="inspector">
                <div className="gpl-inspector-panel">
                    <TabPanel
                        className="gpl-tab-panels"
                        activeClass="active-tab"
                        tabs={[
                            {
                                name: "layoutInspector",
                                title: "Layout",
                                className: "tab-panel"
                            },
                            {
                                name: "styleInspector",
                                title: "Style",
                                className: "tab-panel"
                            },
                            {
                                name: "advancedInspector",
                                title: "Advanced",
                                className: "tab-panel"
                            }
                        ]}
                    >

                        {tab => {
                            let tabLayout;

                            if (tab.name == "layoutInspector") {
                                tabLayout = <LayoutInspector { ...this.props}/>;
                            } else if (tab.name == "styleInspector") {
                                tabLayout = <StyleInspector {...this.props}/>;
                            } else {
                               tabLayout = <AdvancedInspector {...this.props}/>;
                            }


                            return tabLayout;
                        }}
                    </TabPanel>
                </div>
            </InspectorControls>
        );
    }
}
