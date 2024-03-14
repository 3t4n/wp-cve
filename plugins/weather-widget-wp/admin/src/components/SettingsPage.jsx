import { __ } from '@wordpress/i18n';
import React, { useState, useEffect } from 'react';

import AdminNotice from './AdminNotice';
import InputApiKey from './InputApiKey';
import InputCaching from './InputCaching';
import InputCheckUninstall from './InputCheckUninstall';
import ButtonSubmit from './ButtonSubmit';

const SettingsPage = () => {
    const [ apiKey, setApiKey ]                 = useState( '' );
    const [ caching, setCaching ]               = useState( '' );
    const [ uninstallData, setUninstallData ]   = useState( true );
    const [ saveButtonText, setSaveButtonText ] = useState( __( 'Save Settings', 'weather-widget-wp' ) );
    const [ adminNoticeText, setAdminNoticeText ]       = useState( '' );
    const [ adminNoticeStatus, setAdminNoticeStatus ]   = useState( '' );
    const [ adminNoticeVisible, setAdminNoticeVisible ] = useState( false );

    /*
     *  weatherWidgetWpObject object is created via wp_localize_script (this script)
     *  also WordPress nonce is set and an apiUrl
     */
    const url = `${weatherWidgetWpObject.apiUrl}weather-widget-wp/api/settings`;

    /*
     *  Get the form data on page load via useEffect hook
     */
    useEffect(() => {
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-NONCE': weatherWidgetWpObject.nonce
            }
        })
        .then(response => {
            if (!response.ok) throw Error(response.status + ' - Settings could not be loaded.')
            return response
        })
        .then(response => response.json())
        .then(data => {
            setApiKey(data.api_key);
            setCaching(data.caching);
            setUninstallData(data.uninstall_data);
        })
        .catch(error => console.log(error));
    }, []);


    /*
     *  Submit the form data
     */
    const handleFormSubmit = (e) => {
        e.preventDefault();

        setSaveButtonText( __( 'Saving ...', 'weather-widget-wp' ) );

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-NONCE': weatherWidgetWpObject.nonce
            },
            body: JSON.stringify({
                api_key: apiKey,
                caching: caching,
                uninstall_data: uninstallData
            })
        }).then(response => {
            setAdminNoticeVisible( true );
            setSaveButtonText( __( 'Save Settings', 'weather-widget-wp' ) );

            if (response.ok) {
                console.log( __( 'Plugin settings saved.', 'weather-widget-wp' ) );
                setAdminNoticeText( __( 'Settings saved.', 'weather-widget-wp' ) );
                setAdminNoticeStatus( 'notice-success ' );
            } else {
                console.log( __( 'Error ', 'weather-widget-wp' ) + response.status + __( ': Settings could not be saved.', 'weather-widget-wp' ) );
                setAdminNoticeText( __( 'Oops! Something went wrong! Settings where not saved.', 'weather-widget-wp' ) );
                setAdminNoticeStatus( 'notice-error ' );
            }
        });
    }

    return (
        <>
            <AdminNotice adminNoticeVisible={ adminNoticeVisible } setAdminNoticeVisible={ setAdminNoticeVisible } adminNoticeText={ adminNoticeText } adminNoticeStatus={ adminNoticeStatus } />

            <form onSubmit={handleFormSubmit}>
                <table className="form-table" role="presentation">
                    <tbody>
                        <InputApiKey apiKey={ apiKey } setApiKey={ setApiKey } />
                        <InputCaching caching={ caching } setCaching={ setCaching } />
                        <InputCheckUninstall uninstallData={ uninstallData } setUninstallData={ setUninstallData } />
                        <ButtonSubmit saveButtonText={ saveButtonText } />
                    </tbody>
                </table>
            </form>
        </>
    )
}
export default SettingsPage;
