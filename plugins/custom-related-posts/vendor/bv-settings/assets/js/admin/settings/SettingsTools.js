import React from 'react';
import PropTypes from 'prop-types';

const SettingsTools = (props) => {
    return (
        <div id={`bvs-settings-group-${props.group.id}`} className="bvs-settings-group">
            <h2 className="bvs-settings-group-name">{props.group.name}</h2>
            <div className="bvs-settings-group-container">
                <div className="bvs-setting-container">
                    <div className="bvs-setting-label-container">
                        <span className="bvs-setting-label">
                            Reset to defaults
                        </span>
                        <span className="bvs-setting-description">Reset all settings to their default values.</span>
                    </div>
                    <div className="bvs-setting-input-container">
                        <button
                            className="button"
                            onClick={props.onResetDefaults}
                        >Reset to Defaults</button>
                    </div>
                </div>
            </div>
        </div>
    );
}

SettingsTools.propTypes = {
    group: PropTypes.object.isRequired,
    settings: PropTypes.object.isRequired,
    onResetDefaults: PropTypes.func.isRequired,
}

export default SettingsTools;