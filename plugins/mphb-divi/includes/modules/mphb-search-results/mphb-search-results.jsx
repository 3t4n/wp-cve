import React, { Component } from 'react';
import MPHBModule from '../../components/MPHBModule'

class MPHB_Divi_Search_Results_Module extends Component {

    static slug = 'mphb-divi-search-results';

    /**
     * Module render in VB
     */
    render() {

        return (
            <MPHBModule name={ this.props.name }/>
        );

    }

}

export default MPHB_Divi_Search_Results_Module;