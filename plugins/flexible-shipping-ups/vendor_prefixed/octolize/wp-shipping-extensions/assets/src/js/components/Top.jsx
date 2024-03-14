import React from 'react';

export default function Top({assets_url, header_title, header_description}) {
    const url = `${assets_url}img/logo-black.svg`;

    return <>
        <section className="oct-shipping-extensions-top">
            <h1>{header_title} <img alt="Octolize" src={url}/></h1>
            <p>{header_description}</p>
        </section>

        <div className="oct-shipping-extensions-notice-list-hide">
            <div className="wp-header-end"></div>
        </div>
    </>;
}
