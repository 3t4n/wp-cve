import WFTY_Component from "./abs-component";

/*global wftyDiviCustomer*/
class WFTY_Customer_Details extends WFTY_Component {
    static  slug = 'et_wfty_customer_details';

    constructor() {
        super();
        this.c_slug = 'et_wfty_customer_details';
    }

    static css(props) {

        const utils = window.ET_Builder.API.Utils;
        let wfty_divi_style = [];
        if (window.hasOwnProperty(WFTY_Customer_Details.slug + '_fields')) {
            wfty_divi_style = window[WFTY_Customer_Details.slug + '_fields'](utils, props);
        }
        return [wfty_divi_style];
    }

    render() {
        //const Content = this.props.content;
        let wfty_width_class = 'wfty_content wfty_clearfix wfty_text';
        wfty_width_class += ('1c' === this.props.customer_layout) ? (" wfty_full_width") : "";
        wfty_width_class += ('2c' === this.props.customer_layout_tablet) ? (" wfty_2c_tab_width") : "";
        wfty_width_class += ('2c' === this.props.customer_layout_phone) ? (" wfty_2c_mob_width") : "";
        return (
            <div id={this.c_slug}>
                <div class="wfty_wrap">
                    <div class="wfty_box wfty_customer_info">
                        <h2 class="wfty-customer-info-heading wfty_title">{this.props.heading}</h2>
                        <div class={wfty_width_class}>
                            <div class="wfty_2_col_left">
                                <div class="wfty_text_bold"><strong>{wftyDiviCustomer.email_text}</strong></div>
                                <div class="wfty_view">john.doe@gmail.com</div>
                            </div>
                            <div class="wfty_2_col_right">
                                <div class="wfty_text_bold"><strong>{wftyDiviCustomer.phone_text}</strong></div>
                                <div class="wfty_view">(999) 999-9999</div>
                            </div>
                            <div class="wfty_clear_15"></div>
                            <div class="wfty_2_col_left">
                                <div class="wfty_text">
                                    <div class="wfty_text_bold"><strong>{wftyDiviCustomer.bill_text}</strong></div>
                                    <div class="wfty_view">John Doe <br/>711-2880 Nulla St <br/>New York, NY 10001</div>
                                </div>
                            </div>
                            {('true' == wftyDiviCustomer.shipping) ? (
                                <div class="wfty_2_col_right">
                                    <div class="wfty_text">
                                        <div class="wfty_text_bold"><strong>{wftyDiviCustomer.ship_text}</strong></div>
                                        <div className="wfty_view">John Doe <br/>711-2880 Nulla St <br/>New York, NY 10001</div>
                                    </div>
                                </div>) : ''
                            }
                            <div class="wfty_clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

export default WFTY_Customer_Details;