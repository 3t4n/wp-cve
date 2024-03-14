import WFTY_Component from "./abs-component";

/*global wftyDivi*/
class WFTY_Order_Details extends WFTY_Component {
    static  slug = 'et_wfty_order_details';

    constructor() {
        super();
        this.c_slug = 'et_wfty_order_details';
    }

    static css(props) {

        const utils = window.ET_Builder.API.Utils;
        let wfty_divi_style = [];
        if (window.hasOwnProperty(WFTY_Order_Details.slug + '_fields')) {
            wfty_divi_style = window[WFTY_Order_Details.slug + '_fields'](utils, props);
        }
        return [wfty_divi_style];
    }

    render() {
        let dsClass = ('on' == this.props.order_download_preview) ? "" : " wfty-hide-download";
        dsClass += ('on' == this.props.order_subscription_preview) ? "" : " wfty-hide-subscription";
        return (
            <div id={this.c_slug} className={dsClass}>
                <div className="wffn_order_details_table">
                    <div className="wfty_wrap">
                        <div className="wfty_box wfty_order_details">
                            <h2 className="wfty-order-details-heading wfty_title">{this.props.order_details_heading}</h2>
                            <div className={('on' == this.props.order_details_img) ? ("wfty_pro_list_cont wfty_show_images") : "wfty_pro_list_cont wfty_hide_images"}>
                                <div className="wfty_pro_list wfty_clearfix">
                                    <div className="wfty_leftDiv wfty_clearfix">
                                        {('on' == this.props.order_details_img) ?
                                            (<div className="wfty_p_img">
                                                <a href="javascript:void(0);"><img height="100" width="100" className="attachment-shop_thumbnail size-shop_thumbnail" src={wftyDiviOrder.img_url}/></a>
                                            </div>) : ''
                                        }
                                        <div className="wfty_p_name">
                                            <a href="javascript:void(0);"><span className="wfty_t">{wftyDiviOrder.pro_name}</span></a><span className="wfty_quantity_value_box"><span className="multiply"> x</span><span className="qty">1</span></span>
                                            <div className="wfty_info">
                                                <ul className="wc-item-meta">
                                                    <li><strong className="wc-item-meta-label">Color: </strong><p>Blue</p></li>
                                                    <li><strong className="wc-item-meta-label">Size: </strong><p>Large</p></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wfty_rightDiv">
                                        <span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">{wftyDiviOrder.currency}</span>{wftyDiviOrder.price}</span>
                                    </div>
                                    <div className="wfty-clearfix"></div>
                                </div>
                                <table>
                                    <tfoot>
                                    <tr>
                                        <th scope="row">{wftyDiviOrder.sub_head}:</th>
                                        <td>
                                            <span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">{wftyDiviOrder.currency}</span>{wftyDiviOrder.price}</span>
                                        </td>
                                    </tr>
                                    {('true' == wftyDiviOrder.shipping) ? (
                                        <tr>
                                            <th scope="row">{wftyDiviOrder.ship_head}:</th>
                                            <td>
                                                <span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">{wftyDiviOrder.currency}</span>{wftyDiviOrder.shipping_price}</span>
                                                <small className="shipped_via">{wftyDiviOrder.ship_text}</small>
                                            </td>
                                        </tr>) : ''
                                    }
                                    <tr>
                                        <th scope="row">{wftyDiviOrder.payment_head}:</th>
                                        <td>{wftyDiviOrder.payment_text}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{wftyDiviOrder.total_head}:</th>
                                        <td>
                                            <span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">{wftyDiviOrder.currency}</span>{wftyDiviOrder.total_price}</span>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div className="wfty_box wfty_order_download">
                            <div className="wfty_title">{this.props.order_download_heading}</div>
                            <table className="shop_table shop_table_responsive wfty_order_downloads">
                                <thead>
                                <tr>
                                    <th className="download-product"><span className="nobr">{wftyDiviOrder.down_th_file}</span></th>
                                    {('on' == this.props.order_downloads_file) ? (
                                        <th className="download-remaining"><span className="nobr">{wftyDiviOrder.down_th_down}</span></th>) : ''
                                    }
                                    {('on' == this.props.order_downloads_file_expiry) ? (
                                        <th className="download-expires"><span className="nobr">{wftyDiviOrder.down_th_exp}</span></th>) : ''
                                    }
                                    <th className="download-file"><span className="nobr"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td data-title={wftyDiviOrder.down_th_file} className="download-product">{wftyDiviOrder.down_td_file}</td>
                                    {('on' == this.props.order_downloads_file) ? (
                                        <td data-title={wftyDiviOrder.down_th_down} className="download-remaining">10</td>) : ''
                                    }
                                    {('on' == this.props.order_downloads_file_expiry) ? (
                                        <td data-title={wftyDiviOrder.down_th_exp} className="download-expires">{wftyDiviOrder.down_td_exp}</td>) : ''
                                    }
                                    <td className="download-file"><a href="javascript:void(0);">{this.props.order_downloads_btn_text}</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div className="wfty_box wfty_subscription">
                            <div className="wfty_title">{this.props.order_subscription_heading}</div>
                            <table className="shop_table shop_table_responsive my_account_orders">
                                <thead>
                                <tr>
                                    <th className="order-number wfty_left"><span className="nobr">{wftyDiviOrder.subs_th_title}</span></th>
                                    <th className="order-status wfty_center"><span className="nobr">{wftyDiviOrder.subs_th_pay}</span></th>
                                    <th className="order-total wfty_center"><span className="nobr">{wftyDiviOrder.subs_th_tot}</span></th>
                                    <th className="order-total wfty_center"><span className="nobr">{wftyDiviOrder.subs_th_act}</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr className="order">
                                    <td data-title={wftyDiviOrder.subs_th_title} className="subscription-id order-number wfty_left">
                                        <a href="javascript:void(0);"><strong>#1234 </strong></a><small>({wftyDiviOrder.subs_td_title})</small>
                                    </td>
                                    <td data-title={wftyDiviOrder.subs_th_pay} className="subscription-next-payment order-date wfty_center">{wftyDiviOrder.subs_td_pay}</td>
                                    <td data-title={wftyDiviOrder.subs_th_tot} className="subscription-total order-total wfty_center">
                                        <span class="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">{wftyDiviOrder.currency}</span>{wftyDiviOrder.subs_td_tot}</span>
                                    </td>
                                    <td data-title={wftyDiviOrder.subs_th_act} className="subscription-actions order-actions wfty_center">
                                        <a href="javascript:void(0);" className="button view">{wftyDiviOrder.subs_td_act}</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

export default WFTY_Order_Details;