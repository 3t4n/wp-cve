import React from "react";
import {Component} from "@wordpress/element";

class Error extends Component<any, any> {
    render() {
        return <div className="import-shopify-to-wp__error">
            {this.props.children}
        </div>
    }
}

export {
    Error
}
