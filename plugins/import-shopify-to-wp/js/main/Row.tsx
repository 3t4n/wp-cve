import React from "react";
import {Component} from "@wordpress/element";

class Row extends Component<any, any> {
    render() {
        return <div className="import-shopify-to-wp__row">
            {this.props.children}
        </div>
    }
}

export {
    Row
}
