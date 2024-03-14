// External Dependencies
import React, { Component, Fragment} from 'react';

// Internal Dependencies
// import './style.css';


class WpvrDivi extends Component {

    static slug = 'wpvr_divi';

    render() {
        return (
            <Fragment>
                <div>
                    <p className="wpvr-block-content">
                        WPVR id={this.props.vr_id}, Width={this.props.vr_width}{this.props.vr_width_unit}, Height={this.props.vr_height}{this.props.vr_height_unit}, Mobile Height={this.props.vr_mobile_height}{this.props.vr_mobile_height_unit}, Radius={this.props.vr_radius}{this.props.vr_radius_unit}
                    </p>
                </div>

            </Fragment>
        );
    }
}

export default WpvrDivi;
