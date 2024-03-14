import React from 'react';
import PropTypes from 'prop-types';

const RequiredLabel = (props) => {

    if (!props.object.hasOwnProperty('required')) {        
        return null;
    }

    // Don't show if addon is active.
    if ( bv_settings.required_addons.hasOwnProperty( props.object.required ) && bv_settings.required_addons[ props.object.required ].active ) {
        return null;
    }

    let requiredLabelText = 'Required';
    let requiredLabelUrl = false;

    if ( bv_settings.required_addons.hasOwnProperty( props.object.required ) ) {
        if ( bv_settings.required_addons[ props.object.required ].hasOwnProperty( 'label' ) ) {
            requiredLabelText = bv_settings.required_addons[ props.object.required ].label;
        }
        if ( bv_settings.required_addons[ props.object.required ].hasOwnProperty( 'url' ) ) {
            requiredLabelUrl = bv_settings.required_addons[ props.object.required ].url;
        }
    }

    if ( requiredLabelUrl ) {
        return (
            <a href={requiredLabelUrl} target="_blank" className="bvs-setting-required">{ requiredLabelText }</a>
        );
    } else {
        return (
            <span className="bvs-setting-required">{ requiredLabelText }</span>
        );
    }
}

RequiredLabel.propTypes = {
    object: PropTypes.object.isRequired,
}

export default RequiredLabel;