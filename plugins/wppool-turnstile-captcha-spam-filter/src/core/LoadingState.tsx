import React from "react";

//styles
import "./_loading-state.scss";

const LoadingState = () => {
    return (
        <div className="loading_parent">
            <div className="loading_theme section_parent">
                <div className="loading_title loading_theme_inner"></div>
                <div className="loading_content loading_theme_inner">
                    <div className="radio_options">
                        <div className="radio_button_sc"></div>
                        <div className="theme_options"></div>
                    </div>
                    <div className="radio_options">
                        <div className="radio_button_sc"></div>
                        <div className="theme_options"></div>
                    </div>
                    <div className="radio_options">
                        <div className="radio_button_sc"></div>
                        <div className="theme_options"></div>
                    </div>
                </div>
            </div>
            <div className="loading_submit section_parent">
                <div className="loading_title loading_submit_inner"></div>
                <div className="loading_content loading_submit_inner"></div>
            </div>
            <div className="loading_custom_message section_parent">
                <div className="loading_title loading_custom_message_inner"></div>
                <div className="loading_content loading_custom_message_inner"></div>
            </div>
            <div className="loading_save_changes_btn section_parent">
                <div className="loading_btn loading_save_changes_btn_inner"></div>
            </div>
        </div>
    );
};

export default LoadingState;
