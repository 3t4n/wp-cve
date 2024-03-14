import React, { Component } from 'react';
import MPHBModule from '../../../components/MPHBModule';

class MPHB_Divi_Accommodation_Type_Gallery_Module extends Component {

    static slug = 'mphb-divi-accommodation-type-gallery';

    render() {

        if ( ! this.props.__html ) {
            return <MPHBModule
                name={ this.props.name }
            />
        }

        return <div dangerouslySetInnerHTML={ { __html: this.props.__html } }></div>;

    }

}

export default MPHB_Divi_Accommodation_Type_Gallery_Module;