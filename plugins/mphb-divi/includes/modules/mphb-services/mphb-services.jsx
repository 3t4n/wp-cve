import React, { Component } from 'react';

class MPHB_Divi_Services_Module extends Component {

    static slug = 'mphb-divi-services';

    /**
     * Module render in VB
     */
    render() {

        return (
            <div dangerouslySetInnerHTML={{__html: this.props.__services}}></div>
        );

    }

}

export default MPHB_Divi_Services_Module;