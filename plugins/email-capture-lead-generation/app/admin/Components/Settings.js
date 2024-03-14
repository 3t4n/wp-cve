/**
 * Admin settings page component.
 *
 * @package email-capture-lead-generation
 * @since 1.0.2
 */

import { useState, useEffect } from '@wordpress/element'
import { Panel, PanelBody, PanelRow, ToggleControl, TextControl, Spinner, ClipboardButton, Button, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';


const settingsDefault = () => {
    return {
        formTitle: 'Subscribe Us',
        formDescription: '',
        displayFirstName: 'yes',
        displayLastName: 'yes',
        buttonLabel: __('Submit', 'email-capture-lead-generation'),
        templateType: 'default',
    }
}


const templateOptions = () => {
    return [
        { label: __('Default', 'email-capture-lead-generation'), value: 'default' },
        { label: __('Classic', 'email-capture-lead-generation'), value: 'classic' },
        { label: __('Standard', 'email-capture-lead-generation'), value: 'standard' },
        { label: __('Aurora', 'email-capture-lead-generation'), value: 'aurora' }
    ];
}


/**
 * Settings page main component.
 *
 * @since 1.0.2
 */
const Settings = () => {

    /**
     * eclg_data is being localized from php.
     */
    const { eclg_options } = eclg_data;

    const { eclg_settings } = eclg_options;

    const [settingsData, setSettingsData] = useState({});

    const [isLoading, setIsLoading] = useState(true);

    const [isSaving, setIsSaving] = useState(false);

    useEffect(function () {

        let data = {};
        data = 'undefined' !== typeof eclg_settings && Object.keys(eclg_settings).length > 0 ? eclg_settings : settingsDefault();
        setSettingsData(data);
        setIsLoading(false);
    }, []);


    const { formTitle, formDescription, displayFirstName, displayLastName, buttonLabel, templateType } = settingsData;


    const onSaveButtonClicked = () => {

        setIsSaving(true);

        let data = new FormData();

        data.append('eclg_settings[formTitle]', formTitle);
        data.append('eclg_settings[formDescription]', formDescription);
        data.append('eclg_settings[displayFirstName]', displayFirstName);
        data.append('eclg_settings[displayLastName]', displayLastName);
        data.append('eclg_settings[buttonLabel]', buttonLabel);
        data.append('eclg_settings[templateType]', templateType);
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

                <Panel header={__('Shortcode and Settings', 'email-capture-lead-generation')}>

                    {
                        isLoading ?

                            <Spinner />

                            :

                            <>

                                <Shortcode settingsData={settingsData} />

                                <Template settingsData={settingsData} />

                                <PanelBody title={__('Settings', 'email-capture-lead-generation')}>

                                    <PanelRow>
                                        <label>{__('Subscription form type', 'email-capture-lead-generation')}</label>
                                        <div className="email-capture-lead-generation-field-wrapper">
                                            <SelectControl
                                                options={templateOptions()}
                                                value={templateType ? templateType : 'default'}
                                                onChange={
                                                    (value) => {
                                                        setSettingsData({
                                                            ...settingsData,
                                                            templateType: value
                                                        })
                                                    }
                                                }
                                            />

                                        </div>
                                    </PanelRow>

                                    {
                                        'undefined' !== typeof templateType && 'default' !== templateType ?
                                            <>
                                                <PanelRow>
                                                    <label>{__('Form title', 'email-capture-lead-generation')}</label>
                                                    <div className="email-capture-lead-generation-field-wrapper">
                                                        <TextControl
                                                            value={formTitle ? formTitle : ''}
                                                            onChange={
                                                                (value) => {
                                                                    setSettingsData({
                                                                        ...settingsData,
                                                                        formTitle: value
                                                                    })
                                                                }
                                                            }
                                                        />
                                                    </div>
                                                </PanelRow>


                                                <PanelRow>
                                                    <label>{__('Form description', 'email-capture-lead-generation')}</label>
                                                    <div className="email-capture-lead-generation-field-wrapper">
                                                        <TextControl
                                                            value={formDescription ? formDescription : ''}
                                                            onChange={
                                                                (value) => {
                                                                    setSettingsData({
                                                                        ...settingsData,
                                                                        formDescription: value
                                                                    })
                                                                }
                                                            }
                                                        />
                                                    </div>
                                                </PanelRow>
                                            </>
                                            : null
                                    }

                                    <PanelRow>
                                        <label>{__('Display first name field in subscription form?', 'email-capture-lead-generation')}</label>
                                        <div className="email-capture-lead-generation-field-wrapper">
                                            <ToggleControl
                                                checked={displayFirstName == 'yes'}
                                                onChange={
                                                    () => {
                                                        let isChecked = displayFirstName == 'yes' ? 'no' : 'yes';
                                                        setSettingsData({
                                                            ...settingsData,
                                                            displayFirstName: isChecked
                                                        });
                                                    }
                                                }
                                            />
                                        </div>
                                    </PanelRow>

                                    <PanelRow>
                                        <label>{__('Display last name field in subscription form?', 'email-capture-lead-generation')}</label>
                                        <div className="email-capture-lead-generation-field-wrapper">
                                            <ToggleControl
                                                checked={displayLastName == 'yes'}
                                                onChange={
                                                    () => {
                                                        let isChecked = displayLastName == 'yes' ? 'no' : 'yes';
                                                        setSettingsData({
                                                            ...settingsData,
                                                            displayLastName: isChecked
                                                        });
                                                    }
                                                }
                                            />
                                        </div>
                                    </PanelRow>

                                    <PanelRow>
                                        <label>{__('Subscription form button label', 'email-capture-lead-generation')}</label>
                                        <div className="email-capture-lead-generation-field-wrapper">
                                            <TextControl
                                                value={buttonLabel ? buttonLabel : ''}
                                                onChange={
                                                    (value) => {
                                                        setSettingsData({
                                                            ...settingsData,
                                                            buttonLabel: value
                                                        })
                                                    }
                                                }
                                            />
                                        </div>
                                    </PanelRow>

                                </PanelBody>

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
                                disabled={isSaving || isLoading}
                                onClick={() => onSaveButtonClicked()}
                                className="eclg-save-button"
                                isPrimary>
                                {isSaving ? __('Saving', 'email-capture-lead-generation') : __('Save', 'email-capture-lead-generation')}
                            </Button>

                        </PanelRow>

                    </PanelBody>

                </Panel>

            </div>

        </div>

    );
};



const Template = ({ settingsData }) => {
    const { formTitle, formDescription, displayFirstName, displayLastName, buttonLabel, templateType } = settingsData;

    const formHeader = <>
        {
            formTitle || formDescription ?
                <header className="email-capture__header">
                    {formTitle ? <h2>{formTitle}</h2> : null}
                    {formDescription ? <h5>{formDescription}</h5> : null}
                </header>
                : null
        }
    </>

    let forms = {};
    forms['classic'] = <>
        <div className="email-capture email-capture--bg-primary">
            {formHeader}
            <form>
                <div className="email-capture__ele">

                    {
                        'yes' == displayFirstName ?
                            <fieldset className="email-capture__ele__fieldset">
                                <legend>{__('First Name', 'email-capture-lead-generation')}</legend>
                                <input type="text" />
                                <div className="error-msg">{__('Please enter your firstname.', 'email-capture-lead-generation')}</div>
                            </fieldset>
                            : null
                    }

                    {
                        'yes' == displayLastName ?
                            <fieldset className="email-capture__ele__fieldset">
                                <legend>{__('Last Name', 'email-capture-lead-generation')}</legend>
                                <input type="text" />
                                <div className="error-msg">{__('Please enter your lastname.', 'email-capture-lead-generation')}</div>
                            </fieldset>
                            : null
                    }
                    <fieldset className="email-capture__ele__fieldset">
                        <legend>{__('Email', 'email-capture-lead-generation')}</legend>
                        <input type="email" />
                        <div className="error-msg">{__('Please enter email address.', 'email-capture-lead-generation')}</div>
                    </fieldset>
                    <fieldset className="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
                        <legend>&nbsp;</legend>
                        <button type="button" disabled>{buttonLabel}</button>
                    </fieldset>
                </div>
            </form>
        </div>
    </>;

    forms['standard'] = <>
        <div className="email-capture email-capture--full-width email-capture--bg-secondary">
            {formHeader}
            <form>
                <div className="email-capture__ele">

                    {
                        'yes' == displayFirstName ?
                            <fieldset className="email-capture__ele__fieldset">
                                <legend>{__('First Name', 'email-capture-lead-generation')}</legend>
                                <input type="text" />
                                <div className="error-msg">{__('Please enter your firstname.', 'email-capture-lead-generation')}</div>
                            </fieldset>
                            : null
                    }

                    {
                        'yes' == displayLastName ?
                            <fieldset className="email-capture__ele__fieldset">
                                <legend>{__('Last Name', 'email-capture-lead-generation')}</legend>
                                <input type="text" />
                                <div className="error-msg">{__('Please enter your lastname.', 'email-capture-lead-generation')}</div>
                            </fieldset>
                            : null
                    }
                    <fieldset className="email-capture__ele__fieldset">
                        <legend>{__('Email', 'email-capture-lead-generation')}</legend>
                        <input type="email" />
                        <div className="error-msg">{__('Please enter email address.', 'email-capture-lead-generation')}</div>
                    </fieldset>
                    <fieldset className="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
                        <legend>&nbsp;</legend>
                        <button type="button" disabled>{buttonLabel}</button>
                    </fieldset>
                </div>
            </form>
        </div>
    </>

    forms['aurora'] = <>
        <div className="email-capture email-capture--center email-capture--bg-secondary">
            <div className="box-border">
                {formHeader}
                <form>
                    <div className="email-capture__ele email-capture__ele--no-space">

                        {
                            'yes' == displayFirstName ?
                                <fieldset className="email-capture__ele__fieldset">
                                    <legend>{__('First Name', 'email-capture-lead-generation')}</legend>
                                    <input type="text" />
                                    <div className="error-msg">{__('Please enter your firstname.', 'email-capture-lead-generation')}</div>
                                </fieldset>
                                : null
                        }

                        {
                            'yes' == displayLastName ?
                                <fieldset className="email-capture__ele__fieldset">
                                    <legend>{__('Last Name', 'email-capture-lead-generation')}</legend>
                                    <input type="text" />
                                    <div className="error-msg">{__('Please enter your lastname.', 'email-capture-lead-generation')}</div>
                                </fieldset>
                                : null
                        }
                        <fieldset className="email-capture__ele__fieldset">
                            <legend>{__('Email', 'email-capture-lead-generation')}</legend>
                            <input type="email" placeholder="Your email address" />
                            <div className="error-msg">{__('Please enter email address.', 'email-capture-lead-generation')}</div>
                        </fieldset>
                        <fieldset className="email-capture__ele__fieldset email-capture__ele__fieldset--btn-wrap">
                            <legend>&nbsp;</legend>
                            <button type="button" disabled>{buttonLabel}</button>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </>

    return 'undefined' !== typeof forms[templateType] ?
        <PanelBody className="eclg-form-template admin-panel main__cnt" title={__('Template Demo', 'email-capture-lead-generation')}>
            {forms[templateType]}
        </PanelBody>
        : '';
}



const Shortcode = ({ settingsData }) => {
    const { displayFirstName, displayLastName, buttonLabel, templateType } = settingsData;

    const [hasCopied, setHasCopied] = useState(false);

    let shortCodeText = `[eclg_capture firstname="${displayFirstName}" lastname="${displayLastName}"  button_text="${buttonLabel}"]`;
    return (
        <>
            <PanelBody title={__('Shortcode', 'email-capture-lead-generation')}>
                <PanelRow>
                    <label>
                        <code>{shortCodeText}</code>
                    </label>


                    <div className="email-capture-lead-generation-field-wrapper">
                        <ClipboardButton
                            isSecondary
                            text={shortCodeText}
                            onCopy={() => setHasCopied(true)}
                            onFinishCopy={() => setHasCopied(false)}
                        >
                            {hasCopied ? __('Shortcode Copied!', 'email-capture-lead-generation') : __('Copy Shortcode', 'email-capture-lead-generation')}
                        </ClipboardButton>
                    </div>

                </PanelRow>
            </PanelBody>
        </>
    );
}


export default Settings;