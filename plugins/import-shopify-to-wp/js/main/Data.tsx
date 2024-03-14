import React from "react";
import {Component} from "react";
import {__} from "@wordpress/i18n";

interface Props {
    data: {
        [key: string]: any
    },
    type: 'products' | 'customers' | 'orders',
    noData: () => false | string,
}

class Data extends Component<Props, any> {
    render() {
        if (this.props.noData() !== false) {
            return <>{this.props.noData()}</>
        }


        const {data} = this.props;
        // console.log(data);
        const keys = Object.keys(data);
        // console.log(data);
        return keys.map(key => {
            return <div className="import-shopify-to-wp__item" key={`${key}-${data[key].id}`}>
                <div>{this.getTitle(data[key])}</div>
                <div>{data[key].wpStatus
                    ? <mark className={data[key].wpStatus.toLowerCase()}>{data[key].wpStatus}</mark>
                    : <mark className="not-processed">{__('Not Processed', 'import-shopify-to-wp')}</mark>
                }</div>
            </div>
        })
    }

    getTitle(item: { [key: string]: any }) {
        if (this.props.type === 'products') {
            return item.title;
        } else if (this.props.type === 'customers') {
            return `${item.first_name} ${item.last_name} -- ${item.email}`;
        } else if (this.props.type === 'orders') {
            return `${item.name} -- ${item.email}`;
        }

        return item.id;
    }
}


export {
    Data
}
