import React from "react";
import {Component} from "react";
import {wordpress} from '@wordpress/icons';
import {__} from '@wordpress/i18n';

const {shopAdminUrl} = window.shopify2wp

class Complete extends Component<any, any> {
    render() {
        return (
            <div className='complete-list'>
                <h2>{__('Congratulations, your shop is now ready!', 'import-shopify-to-wp')}</h2>

                <p>{__('Here\'s what to do next:', 'import-shopify-to-wp')}</p>

                <ul className='complete-list__recommendations'>
                    <li className='complete-list__recommendation'>
                        <span>
                            {wordpress}
                        </span>
                        <span>
                            <a href="https://www.wpbeginner.com/wp-tutorials/woocommerce-tutorial-ultimate-guide/"
                               target='_blank'>
                                {__('Read our Step by Step WooCommerce guide', 'import-shopify-to-wp')}
                            </a>
                        </span>
                    </li>
                    <li className='complete-list__recommendation'>
                        <span>
                             <svg height="682pt" viewBox="-21 -117 682.66672 682" width="682pt"
                                  xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="m626.8125 64.035156c-7.375-27.417968-28.992188-49.03125-56.40625-56.414062-50.082031-13.703125-250.414062-13.703125-250.414062-13.703125s-200.324219 0-250.40625 13.183593c-26.886719 7.375-49.03125 29.519532-56.40625 56.933594-13.179688 50.078125-13.179688 153.933594-13.179688 153.933594s0 104.378906 13.179688 153.933594c7.382812 27.414062 28.992187 49.027344 56.410156 56.410156 50.605468 13.707031 250.410156 13.707031 250.410156 13.707031s200.324219 0 250.40625-13.183593c27.417969-7.378907 49.03125-28.992188 56.414062-56.40625 13.175782-50.082032 13.175782-153.933594 13.175782-153.933594s.527344-104.382813-13.183594-154.460938zm-370.601562 249.878906v-191.890624l166.585937 95.945312zm0 0"
                                fill="currentColor"/>
                        </svg>
                        </span>
                        <span>
                            <a href="https://youtube.com/wpbeginner?sub_confirmation=1" target='_blank'>
                            {__('Learn more about WordPress by watching our tutorials on YouTube', 'import-shopify-to-wp')}
                            </a>
                        </span>
                    </li>
                    <li className='complete-list__recommendation'>
                        <span>
                            <svg viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99512 12C11.9251 12 13.4951 10.43 13.4951 8.5C13.4951 6.57 11.9251 5 9.99512 5C8.06512 5 6.49512 6.57 6.49512 8.5C6.49512 10.43 8.06512 12 9.99512 12ZM2.99512 17.25C2.99512 14.92 7.65512 13.75 9.99512 13.75C12.3351 13.75 16.9951 14.92 16.9951 17.25V19H2.99512V17.25ZM9.99512 15.75C8.20512 15.75 6.17512 16.42 5.33512 17H14.6551C13.8151 16.42 11.7851 15.75 9.99512 15.75ZM11.4951 8.5C11.4951 7.67 10.8251 7 9.99512 7C9.16512 7 8.49512 7.67 8.49512 8.5C8.49512 9.33 9.16512 10 9.99512 10C10.8251 10 11.4951 9.33 11.4951 8.5ZM17.0351 13.81C18.1951 14.65 18.9951 15.77 18.9951 17.25V19H22.9951V17.25C22.9951 15.23 19.4951 14.08 17.0351 13.81ZM19.4951 8.5C19.4951 10.43 17.9251 12 15.9951 12C15.4551 12 14.9551 11.87 14.4951 11.65C15.1251 10.76 15.4951 9.67 15.4951 8.5C15.4951 7.33 15.1251 6.24 14.4951 5.35C14.9551 5.13 15.4551 5 15.9951 5C17.9251 5 19.4951 6.57 19.4951 8.5Z"
                                    fill="currentColor"/>
                            </svg>
                        </span>
                        <span>
                            <div className="complete-list__title">
                                <strong>{__('Join our community:', 'import-shopify-to-wp')}</strong>
                            </div>
                            <a href="https://www.facebook.com/groups/wpbeginner/" target='_blank'
                               className='complete-list__link complete-list__link--facebook'>
                                <span>
                                    <svg x="0px" y="0px" width="96.124px" height="96.123px" viewBox="0 0 96.124 96.123">
                                    <g>
                                        <path d="M72.089,0.02L59.624,0C45.62,0,36.57,9.285,36.57,23.656v10.907H24.037c-1.083,0-1.96,0.878-1.96,1.961v15.803
                                            c0,1.083,0.878,1.96,1.96,1.96h12.533v39.876c0,1.083,0.877,1.96,1.96,1.96h16.352c1.083,0,1.96-0.878,1.96-1.96V54.287h14.654
                                            c1.083,0,1.96-0.877,1.96-1.96l0.006-15.803c0-0.52-0.207-1.018-0.574-1.386c-0.367-0.368-0.867-0.575-1.387-0.575H56.842v-9.246
                                            c0-4.444,1.059-6.7,6.848-6.7l8.397-0.003c1.082,0,1.959-0.878,1.959-1.96V1.98C74.046,0.899,73.17,0.022,72.089,0.02z"/>
                                    </g>
                                    </svg>
                                </span>
                                <span>{__('Join us on Facebook', 'import-shopify-to-wp')}</span>
                            </a>
                            <a href="https://twitter.com/wpbeginner" target='_blank'
                               className='complete-list__link complete-list__link--twitter'>
                                <span>
                                    <svg x="0px" y="0px"
                                         viewBox="0 0 512 512">
                                    <g>
                                        <g>
                                            <path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016
                                                c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992
                                                c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056
                                                c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152
                                                c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792
                                                c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44
                                                C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568
                                                C480.224,136.96,497.728,118.496,512,97.248z"/>
                                        </g>
                                    </g>
                                    </svg>
                                </span>
                                <span>{__('Follow on Twitter', 'import-shopify-to-wp')}</span>
                            </a>
                        </span>
                    </li>
                    <li className='complete-list__recommendation complete-list__view-shop'>
                        <a href={shopAdminUrl}>{__('Configure Your Shop', 'import-shopify-to-wp')}</a>
                    </li>
                </ul>
            </div>
        );
    }
}

export {
    Complete
}
