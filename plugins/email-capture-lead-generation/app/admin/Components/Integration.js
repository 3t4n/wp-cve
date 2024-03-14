/**
 * Admin integration page component.
 *
 * @package email-capture-lead-generation
 * @since 1.0.2
 */

import { useState, useEffect } from '@wordpress/element'
import { Panel, PanelBody, PanelRow, ToggleControl, TextControl, Spinner, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';


const integrationDefault = () => {
    return {
        useOwnList: 'yes',
        selectedProvider: null,
        apiKeys: {},
        listIDs: {},
    }
}


const providerOptions = () => {
    return [
        { label: __('Select One', 'email-capture-lead-generation'), value: null, disabled: true },
        { label: 'MailChimp', value: 'mailchimp' },
        { label: 'ActiveCampaign', value: 'activecampaign' }
    ];
}

const providerLists = () => {
    return [
        { label: __('Click Refresh', 'email-capture-lead-generation'), value: null, disabled: true },
    ]
}

const Integration = () => {

    const { eclg_options } = eclg_data;

    const { eclg_integration, lists_options } = eclg_options;

    const providers = providerOptions();

    const lists = providerLists();

    const [isLoading, setIsLoading] = useState(true);

    const [getLists, setLists] = useState(lists);

    const [integrationData, setIntegrationData] = useState({});

    const [isRefreshing, setIsRefreshing] = useState(false);

    const [isSaving, setIsSaving] = useState(false);


    useEffect(function () {
        let data = {};
        data = { ...integrationDefault(), ...eclg_integration };
        setIntegrationData(data);
        setLists(lists_options);
        setIsLoading(false);
    }, []);


    const { useOwnList, selectedProvider, apiKeys, listIDs } = integrationData;

    let providerLabel = '';

    if (null !== selectedProvider && 'undefined' !== typeof providers) {
        providers.filter(function (provider) {
            if (provider.value == selectedProvider) {
                providerLabel = provider.label;
            }
        });
    }

    let panelBodyTitle = null !== providerLabel ? providerLabel + ' ' + __('configurations', 'email-capture-lead-generation') : null;

    const apiKey = 'undefined' !== typeof apiKeys && 'undefined' !== typeof apiKeys[selectedProvider] ? apiKeys[selectedProvider] : '';

    let providersUrl = '';
    let providersKey = '';
    providersUrl = 'undefined' !== typeof apiKey && 'undefined' !== typeof apiKey.url ? apiKey.url : '';
    providersKey = 'undefined' !== typeof apiKey && 'undefined' !== typeof apiKey.key ? apiKey.key : '';


    let getList = 'undefined' !== typeof getLists && 'undefined' !== typeof getLists[selectedProvider] ? getLists[selectedProvider] : lists;


    let listID = 'undefined' !== typeof listIDs && 'undefined' !== typeof listIDs[selectedProvider] ? listIDs[selectedProvider] : '';


    const onRefreshButtonClicked = () => {
        setIsRefreshing(true);

        let data = new FormData();

        if ('yes' !== useOwnList && null !== selectedProvider) {
            data.append('eclg_integration[selectedProvider]', selectedProvider);
            data.append(`eclg_integration[apiKeys][${selectedProvider}][url]`, providersUrl);
            data.append(`eclg_integration[apiKeys][${selectedProvider}][key]`, providersKey);
        }
        data.append('eclg_fetching_lists', 'yes');

        apiFetch({
            url: `${ajaxurl}?action=eclg_header_auth`,
            method: 'post',
            body: data,
        }).then(res => {
            if ('undefined' !== typeof res.success && true === res.success) {
                let listOptn = res.data;
                setLists({ ...getLists, ...listOptn });
            }
            setIsRefreshing(false);
        });


    }


    const onSaveButtonClicked = () => {

        setIsSaving(true);

        let data = new FormData();

        data.append('eclg_integration[useOwnList]', useOwnList);

        if ('yes' !== useOwnList && null !== selectedProvider) {
            data.append('eclg_integration[selectedProvider]', selectedProvider);
            data.append(`eclg_integration[apiKeys][${selectedProvider}][url]`, providersUrl);
            data.append(`eclg_integration[apiKeys][${selectedProvider}][key]`, providersKey);
            data.append(`eclg_integration[listIDs][${selectedProvider}]`, listIDs[selectedProvider]);
        }
        data.append('eclg_doing_ajax', 'yes');

        // POST
        apiFetch({
            url: `${ajaxurl}?action=eclg_save_data`,
            method: 'post',
            body: data,
        }).then(res => {
            setIsSaving(false);
        });

    }


    return (


        <div className="eclg-fields-container">

            <div className="eclg-fields-left">

                <Panel header={__('Integration and Newsletters', 'email-capture-lead-generation')}>

                    {
                        isLoading ?

                            <Spinner />

                            :

                            <>
                                <PanelBody title={__('Listing Providers', 'email-capture-lead-generation')}>

                                    <PanelRow>
                                        <label>{__('Use default own list', 'email-capture-lead-generation')}</label>
                                        <div className="email-capture-lead-generation-field-wrapper">
                                            <ToggleControl
                                                checked={useOwnList == 'yes'}
                                                onChange={
                                                    () => {
                                                        let isChecked = useOwnList == 'yes' ? 'no' : 'yes';
                                                        setIntegrationData({
                                                            ...integrationData,
                                                            useOwnList: isChecked
                                                        });
                                                    }
                                                }
                                            />
                                        </div>
                                    </PanelRow>


                                    {
                                        useOwnList == 'no' &&
                                        <PanelRow>
                                            <label>{__('Select your provider', 'email-capture-lead-generation')}</label>
                                            <div className="email-capture-lead-generation-field-wrapper">
                                                <SelectControl
                                                    options={providers}
                                                    value={selectedProvider ? selectedProvider : ''}
                                                    onChange={
                                                        (value) => {
                                                            setIntegrationData({
                                                                ...integrationData,
                                                                selectedProvider: value
                                                            });

                                                        }
                                                    }
                                                />
                                            </div>
                                        </PanelRow>
                                    }

                                </PanelBody>


                                {
                                    useOwnList == 'no' && null !== selectedProvider ?
                                        <>

                                            <PanelBody title={panelBodyTitle}>

                                                {
                                                    'mailchimp' !== selectedProvider &&

                                                    <PanelRow>
                                                        <label>{__('URL', 'email-capture-lead-generation')}</label>
                                                        <div className="email-capture-lead-generation-field-wrapper">
                                                            <TextControl
                                                                value={providersUrl ? providersUrl : ''}
                                                                readOnly={'mailchimp' == selectedProvider && true}
                                                                onChange={
                                                                    (value) => {
                                                                        let api = {};
                                                                        api = integrationData.apiKeys;

                                                                        if ('undefined' == typeof api[selectedProvider]) {
                                                                            api[selectedProvider] = {};
                                                                        }
                                                                        api[selectedProvider]['url'] = value;
                                                                        setIntegrationData({
                                                                            ...integrationData,
                                                                            apiKeys: api
                                                                        });
                                                                    }
                                                                }
                                                            />
                                                        </div>
                                                    </PanelRow>
                                                }


                                                <PanelRow>
                                                    <label>{__('Key', 'email-capture-lead-generation')}</label>
                                                    <div className="email-capture-lead-generation-field-wrapper">
                                                        <TextControl
                                                            value={providersKey ? providersKey : ''}
                                                            onChange={
                                                                (value) => {
                                                                    if ('mailchimp' == selectedProvider) {
                                                                        let mailChimpUrl = '';
                                                                        let serverPrefix = '';
                                                                        if ('undefined' !== typeof value && '' !== value) {
                                                                            serverPrefix = value.split('-')[1];
                                                                            if (serverPrefix) {
                                                                                mailChimpUrl = `https://${serverPrefix}.api.mailchimp.com`
                                                                            }
                                                                        }

                                                                        if (mailChimpUrl) {
                                                                            let api = {};
                                                                            api = integrationData.apiKeys;

                                                                            if ('undefined' == typeof api[selectedProvider]) {
                                                                                api[selectedProvider] = {};
                                                                            }
                                                                            api[selectedProvider]['url'] = mailChimpUrl;
                                                                            setIntegrationData({
                                                                                ...integrationData,
                                                                                apiKeys: api
                                                                            });
                                                                        }
                                                                    }
                                                                    let api = {};
                                                                    api = integrationData.apiKeys;

                                                                    if ('undefined' == typeof api[selectedProvider]) {
                                                                        api[selectedProvider] = {};
                                                                    }
                                                                    api[selectedProvider]['key'] = value;
                                                                    setIntegrationData({
                                                                        ...integrationData,
                                                                        apiKeys: api
                                                                    });
                                                                }
                                                            }
                                                        />
                                                    </div>
                                                </PanelRow>


                                                <PanelRow>
                                                    <label>{__('Lists', 'email-capture-lead-generation')}</label>
                                                    <div className="email-capture-lead-generation-field-wrapper lists-wrapper">
                                                        {
                                                            isRefreshing ?
                                                                <Spinner />
                                                                :
                                                                <>
                                                                    <SelectControl
                                                                        options={getList}
                                                                        value={listID ? listID : ''}
                                                                        onChange={
                                                                            (value) => {
                                                                                let listID = {};

                                                                                listID = integrationData.listIDs;

                                                                                listID[selectedProvider] = value;
                                                                                setIntegrationData({
                                                                                    ...integrationData,
                                                                                    listIDs: listID
                                                                                });
                                                                            }
                                                                        }
                                                                    />

                                                                    <Button
                                                                        isSecondary
                                                                        onClick={() => onRefreshButtonClicked()}
                                                                    >{__('Refresh', 'email-capture-lead-generation')}
                                                                    </Button>
                                                                </>
                                                        }
                                                    </div>
                                                </PanelRow>


                                            </PanelBody>

                                        </>

                                        :

                                        null
                                }

                            </>
                    }

                </Panel>

            </div>

            <div className="eclg-fields-right">

                <Panel header="">

                    <PanelBody title={__('Save Settings', 'email-capture-lead-generation')}>

                        <PanelRow>

                            <Button
                                isBusy={isSaving}
                                disabled={isSaving || isLoading || ('no' == useOwnList && null == selectedProvider)}
                                onClick={() => onSaveButtonClicked()}
                                className="eclg-save-button"
                                isPrimary>
                                {isSaving ? __('Saving', 'email-capture-lead-generation') : __('Save', 'email-capture-lead-generation')}
                            </Button>

                        </PanelRow>

                    </PanelBody>

                </Panel>

            </div>

        </div >

    );
}



export default Integration;