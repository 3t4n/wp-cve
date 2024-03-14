import {Button} from '@wordpress/components';
import axios from 'axios';
import React, {Component} from 'react';
import {Error} from './Error';
import {__, sprintf} from "@wordpress/i18n";

interface State {
    selectedFile?: File | null
    isUploading?: boolean,
    canUpload?: boolean,
    uploadError?: string | null
}

class Uploader extends Component<any, State> {
    state = {
        selectedFile: null,
        isUploading: false,
        canUpload: false,
        uploadError: null
    };

    // On file select (from the pop up)
    onFileChange = (event: Event) => {
        // Update the state
        // @ts-ignore
        this.setState({selectedFile: event.target && event.target.files[0]});
    };

    // On file upload (click the upload button)
    onFileUpload = () => {
        // Create an object of formData
        const formData = new FormData();

        // Update the formData object
        formData.append(
            "myFile",
            this.state.selectedFile,
            this.state.selectedFile.name
        );

        // Details of the uploaded file
        // console.log(this.state.selectedFile);

        // Request made to the backend api
        // Send formData object
        axios.post(`${window.ajaxurl}?action=shopify2wp_upload&nonce=${window.shopify2wp.ajaxNonce}`, formData).then(response => {
            const {data} = response;
            // console.log(data);

            if (data.error) {
                // console.log(data.message);
                this.setState({
                    uploadError: data.message
                });
                return data.message;
            }

            this.props.refreshState({
                products: data.newState.products,
                products_total_pages: Number(data.newState.products_total_pages),
                products_current_page: Number(data.newState.products_current_page),
                products_import_complete: !!data.newState.products_import_complete,

                customers: data.newState.customers,
                customers_total_pages: Number(data.newState.customers_total_pages),
                customers_current_page: Number(data.newState.customers_current_page),
                customers_import_complete: !!data.newState.customers_import_complete,

                orders: data.newState.orders,
                orders_total_pages: Number(data.newState.orders_total_pages),
                orders_current_page: Number(data.newState.orders_current_page),
                orders_import_complete: !!data.newState.orders_import_complete,

                current_step: data.newState.current_step
            });


            this.setState({
                uploadError: null
            });
        })
            .catch(err => {
                console.error(err);

            })
            .finally(() => {
                this.setState({isUploading: false});
            });
    };

    // File content to be displayed after
    // file upload is complete
    fileData = () => {
        if (this.state.selectedFile) {
            if (!this.state.selectedFile || this.state.selectedFile.name.indexOf('.zip') < 0) {
                return (
                    <Error>{__('Please select a valid ZIP file.', 'import-shopify-to-wp')}</Error>
                );
            } else {
                return (
                    <div className="import-shopify-to-wp__upload-button-container">
                        <Button isPrimary disabled={this.state.isUploading}
                                onClick={this.onFileUpload}>
                            {__('Upload', 'import-shopify-to-wp')}
                        </Button>
                    </div>
                );
            }
        } else {
            return (
                <div>
                    <h4 dangerouslySetInnerHTML={{
                        __html: sprintf(
                            __('Choose the ZIP file that you downloaded from %s', 'import-shopify-to-wp'),
                            '&nbsp;<a href="https://shopifytowp.com" target="_blank">shopifytowp.com</a>'
                        )
                    }}>
                    </h4>
                </div>
            );
        }
    };

    render() {
        if (window.shopify2wp.woocommerce_status !== 'active') {
            return (
                <div className="notice notice-warning">
                    <p dangerouslySetInnerHTML={{
                        __html: sprintf(
                            __('Please %sinstall and activate WooCommerce%s before you can proceed.', 'import-shopify-to-wp'),
                            '<a href="' + window.shopify2wp.adminUrl + 'plugin-install.php?s=woocommerce&tab=search&type=term">',
                            '</a>'
                        )
                    }}>
                    </p>
                </div>
            )
        }

        return (
            <div>
                <h3>
                    {__('Please select the ZIP file.', 'import-shopify-to-wp')}
                </h3>

                <div className='import-shopify-to-wp__file-input-container'>
                    <input type="file" onChange={this.onFileChange}/>
                </div>
                {this.fileData()}

                {this.state.uploadError !== null && <Error>{this.state.uploadError}</Error>}
            </div>
        );
    }
}

export {
    Uploader
}
