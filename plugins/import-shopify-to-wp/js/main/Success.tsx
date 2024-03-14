import React from "react";
import {Component} from "@wordpress/element";

class Success extends Component<any, any> {
    render() {
        return <div className="import-shopify-to-wp__success">
            {this.props.children}
        </div>
    }
}

export {
    Success
}
