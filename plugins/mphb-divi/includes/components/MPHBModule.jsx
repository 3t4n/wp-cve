import React from 'react';

const MPHBModule = ( {
    name,
    description = ''
} ) => (
    <div>
        <div style={
            {
                'background': '#f6f7f7',
                'borderRadius': '10px',
                'padding': '3em 1em'
            }
        }>
            <p
                style={
                    {
                        'textAlign': 'center',
                        'fontWeight': '700',
                        'fontSize': '1.5em'
                    }
                }
            >{ name }</p>
            { description && <p
                style={
                    {
                        'textAlign': 'center'
                    }
                }
            >{ description }</p> }
        </div>
    </div>
);

export default MPHBModule;