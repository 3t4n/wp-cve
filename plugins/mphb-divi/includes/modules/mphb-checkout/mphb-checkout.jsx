import React, { Component } from 'react';
import MPHBModule from '../../components/MPHBModule'

class MPHB_Divi_Checkout_Module extends Component {

    static slug = 'mphb-divi-checkout';

    /**
     * Module render in VB
     */
    render() {

        return (
            <MPHBModule name={ this.props.name }/>
        );

    }

}

export default MPHB_Divi_Checkout_Module;