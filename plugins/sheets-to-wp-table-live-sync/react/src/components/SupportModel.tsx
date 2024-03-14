import React, { useState, useEffect, useRef } from 'react'
import { wppoollogo } from './../icons';
import gmailicon from '../../../assets/public/icons/support/gmail.svg';
import defaultEmail from '../../../assets/public/icons/support/default-email.svg';
import iconmark from '../../../assets/public/icons/support/iconmark.svg';
import outlook from '../../../assets/public/icons/support/outlook.svg';
import supportmail from '../../../assets/public/icons/support/supportmail.svg';
import yahoo from '../../../assets/public/icons/support/yahoo.svg';
import { toast } from 'react-toastify';
import { getStrings } from './../Helpers';
import './../styles/_supportModel.scss';

const SupportModel = () => {
    const [copySuccess, setCopySuccess] = useState(false);

    const openGmailCompose = () => {
        const email = 'support@wppool.dev';
        const subject = 'Subject for Gmail';
        const body = 'Body for Gmail';
        const gmailComposeUrl = `https://mail.google.com/mail/u/0/?view=cm&fs=1&to=${encodeURIComponent(
            email
        )}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        window.open(gmailComposeUrl, '_blank');


    };

    const handleDefaultmailClick = (email, subject = '', body = '') => {
        window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    };

    const openYahooInBrowser = () => {
        const email = 'support@wppool.dev';
        const subject = 'Subject for Gmail';
        const body = 'Body for Gmail';
        const gmailComposeUrl = `https://compose.mail.yahoo.com/?to=${encodeURIComponent(
            email
        )}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`
        window.open(gmailComposeUrl, '_blank');
    };

    const openOutlookInBrowser = (email, subject = '', body = '') => {
        // window.open('https://www.microsoft.com/en/microsoft-365/outlook/email-and-calendar-software-microsoft-outlook', '_blank');
        window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    };

    const handlebtnCopyMail = async (mail) => {
        const button = document.querySelector('.copy-mail-btn');
        const originalButtonText = button.textContent;
        const originalButtonStyle = {
            backgroundColor: button.style.backgroundColor,
            color: button.style.color,
            padding: '8px 10px',
        };

        // Check if copy operation is in progress
        if (button.getAttribute('data-copying') === 'true') {
            return;
        }

        // Set copying state
        button.setAttribute('data-copying', 'true');

        // Update button state
        button.textContent = 'Copied!';
        button.style.backgroundColor = '#2563EB';
        button.style.color = 'white';
        button.style.padding = '8px 10px';
        button.style.width = '63px';

        try {
            await navigator.clipboard.writeText(mail);
            setCopySuccess(true);
            toast.success('Mail copied successfully.');
        } catch (err) {
            setCopySuccess(false);
            toast.success('Mail copy failed.');
        } finally {
            // Revert button state after 3 seconds
            setTimeout(() => {
                button.textContent = originalButtonText;
                button.style.backgroundColor = originalButtonStyle.backgroundColor;
                button.style.color = originalButtonStyle.color;
                button.style.padding = originalButtonStyle.padding;
                button.style.width = 'unset';

                // Reset copying state
                button.setAttribute('data-copying', 'false');
            }, 800);
        }
    };


    return (
        <div className='support-body'>
            <div className="spt-header">
                <div className="brandlogo">
                    {wppoollogo}
                </div>
                <h3>{getStrings('support-modal-title')}</h3>
            </div>
            <div className="spt-content">

                <div className="template" onClick={openGmailCompose}>
                    <div className="logo">
                        <img src={gmailicon} alt="gmailicon" />
                    </div>
                    <div className="content">
                        <div className="spt-title">{getStrings('gmail')}</div>
                        <div className="spt-details">{getStrings('gmail-content')}</div>
                    </div>
                    <div className='iconmark'>
                        <img src={iconmark} alt="iconmark" />
                    </div>
                </div>

                <div className="template" onClick={() => openOutlookInBrowser('support@wppool.dev', '', 'Facing problem in Sheets to WP Table Live Sync')}>
                    <div className="logo">
                        <img src={outlook} alt="outlook" />
                    </div>
                    <div className="content" >
                        <div className="spt-title">{getStrings('outlook')}</div>
                        <div className="spt-details">{getStrings('outlook-content')}</div>
                    </div>
                    <div className='iconmark'>
                        <img src={iconmark} alt="iconmark" />
                    </div>
                </div>

                <div className="template" onClick={openYahooInBrowser}>
                    <div className="logo">
                        <img src={yahoo} alt="yahoo" />
                    </div>
                    <div className="content" >
                        <div className="spt-title">{getStrings('yahoo')}</div>
                        <div className="spt-details">{getStrings('yahoo-content')}</div>
                    </div>
                    <div className='iconmark'>
                        <img src={iconmark} alt="iconmark" />
                    </div>
                </div>

                <div className="template" onClick={() => handleDefaultmailClick('support@wppool.dev', '', 'Facing problem in Sheets to WP Table Live Sync')}>
                    <div className="logo">
                        <img src={defaultEmail} alt="defaultEmail" />
                    </div>
                    <div className="content">
                        <div className="spt-title">{getStrings('default-mail')}</div>
                        <div className="spt-details">{getStrings('open-default-mail')}</div>
                    </div>
                    <div className='iconmark'>
                        <img src={iconmark} alt="iconmark" />
                    </div>
                </div>

                <div className="template" onClick={() => handlebtnCopyMail('support@wppool.dev')}>
                    <div className="logo">
                        <img src={supportmail} alt="supportmail" />
                    </div>
                    <div className="content">
                        <div className="spt-title">support@wppool.dev</div>
                        <div className="spt-details">{getStrings('copy-content')}</div>
                    </div>
                    <div className='iconmark' >
                        <button className='copy-mail-btn'>COPY EMAL</button>
                    </div>
                </div>

            </div>
            <div className="spt-footer">
                <h4 className="spt-footer-content">{getStrings('powered-by')} <a href='https://wppool.dev/' target="_blank">{getStrings('WPPOOL')}</a></h4>
            </div>
        </div>
    )
}

export default SupportModel
