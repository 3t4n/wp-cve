import React, { Component } from 'react';

class MPHB_Divi_Rates_Module extends Component {

    static slug = 'mphb-divi-rates';

    /**
     * Module render in VB
     */
    render() {

        return (
            <div dangerouslySetInnerHTML={{__html: this.props.__rates}}></div>
        );

    }

}

export default MPHB_Divi_Rates_Module;