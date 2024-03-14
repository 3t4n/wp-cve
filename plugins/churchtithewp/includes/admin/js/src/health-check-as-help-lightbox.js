// This component acts as a wrapper to map data and load a Health Check component as the "Help" lightbox for an input field.
window.Church_Tithe_WP_Health_Check_As_Help_Lightbox = class Church_Tithe_WP_Health_Check_As_Help_Lightbox extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    if (!this.props.data) {
      return "";
    }

    var DynamicReactComponent = eval(this.props.data.react_component);
    var dynamic_react_component = (
      <DynamicReactComponent
        main_component={this.props.main_component}
        data={{
          [this.props.data.key]: {
            is_healthy: false,
            unhealthy: {
              mode: "live_site",
              component_data: this.props.data
            }
          }
        }}
        health_check_key={this.props.data.key}
        slug_suffix={"_help"}
        this_lightbox_slug={this.props.data.key + "_help"}
      />
    );

    return dynamic_react_component;
  }
};
