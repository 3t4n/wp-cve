import axios from "axios";
import {__} from '@wordpress/i18n';

class Import {
    private type: "products" | "customers" | "orders";

    constructor(type: "products" | "customers" | "orders") {
        this.type = type;
        this.state = {};
        this.setState = () => null;
        this.getState = (key?: string) => {
        };
    }

    state: { [key: string]: any }

    public setState: (arg0: object) => void
    public getState: (key?: string) => any

    process = async () => {
        const items: { [key: string]: any } = this.getState(this.type);
        const keys = Object.keys(items);
        // console.log(items);
        let keysProgress = keys.length;

        for (let i = 0; i < keys.length; i++) {
            const prevCopy = {...items}

            // Not imported yet
            if (!prevCopy[keys[i]].wpStatus) {

                prevCopy[keys[i]].wpStatus = __('Processing', 'import-shopify-to-wp');
                this.setState({[this.type]: prevCopy});

                await axios.post(`${window.ajaxurl}?action=shopify2wp_import`, {
                    nonce: window.shopify2wp.ajaxNonce || false,
                    item: items[keys[i]],
                    type: this.type
                })
                    .then(response => {
                        const {data} = response;

                        console.log(response);

                        if (data.error) {
                            prevCopy[keys[i]].wpStatus = __('Failed', 'import-shopify-to-wp');
                            this.setState({[this.type]: prevCopy});

                            //TODO: Implement a retry

                            return;
                        }

                        prevCopy[keys[i]].wpStatus = __('Processed', 'import-shopify-to-wp');
                        this.setState({[this.type]: prevCopy});
                    })
                    .catch(err => {
                        console.error(err);

                        prevCopy[keys[i]].wpStatus = __('Failed', 'import-shopify-to-wp');
                        this.setState({[this.type]: prevCopy});
                    })
                    .finally(async () => {
                        keysProgress--;

                        console.log(keysProgress);

                        if (keysProgress === 0) {
                            // console.log('get next page');
                            await this.next_page();
                        }
                    });
            }
        }
    }

    next_page = async () => {
        const state = this.getState();

        const probablyNextPageNumber = state[`${this.type}_current_page`] + 1;

        if (probablyNextPageNumber > state[`${this.type}_total_pages`]) {
            this.setState({[`${this.type}_import_complete`]: true});

            // console.log('All items imported!');

            return true;
        }

        await axios.post(`${window.ajaxurl}?action=shopify2wp_next_page`, {
            nonce: window.shopify2wp.ajaxNonce || false,
            type: this.type
        })
            .then(async (response) => {
                const {data} = response;
                // console.log(state);
                // console.log(response);

                this.setState({
                    [this.type]: data[this.type],
                    [`${this.type}_total_pages`]: Number(data[`${this.type}_total_pages`]),
                    [`${this.type}_current_page`]: Number(data[`${this.type}_current_page`])
                });

                await this.process();
            })
            .catch(err => {
                console.error(err);
                // console.log(state);

            })
            .finally(() => {
                console.log(state);
            });

        return false
    }
}

export default Import
