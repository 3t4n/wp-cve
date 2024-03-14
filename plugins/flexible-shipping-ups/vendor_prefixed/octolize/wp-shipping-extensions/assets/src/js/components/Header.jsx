import React from 'react';

export default function Header({title}) {
    return <header className="oct-shipping-extensions-header">
        <h1 className="oct-shipping-extensions-header-title">
            {title}
        </h1>
    </header>;
}
