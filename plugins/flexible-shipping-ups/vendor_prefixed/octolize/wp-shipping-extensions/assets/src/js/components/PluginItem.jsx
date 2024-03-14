import React from 'react';

export default function PluginItem({name, icon, description, plugin_url, assets_url, buy_plugin_label}) {
    const icon_url = `${assets_url}img/plugin-icons/${icon}`

    return <div className="oct-shipping-extensions-plugin">
        <div className="oct-plugin-info">
            <img
                className="oct-plugin-icon"
                src={icon_url}
                alt={name}/>

            <div className="oct-plugin-info-content">
                <h2 className="oct-plugin-name">
                    {name}
                </h2>

                <div className="oct-plugin-desc">
                    {description}
                </div>
            </div>
        </div>

        <div className="oct-plugin-actions">
            <a href={plugin_url} target="_blank" className="btn-buy">{buy_plugin_label}</a>
        </div>
    </div>;
}
