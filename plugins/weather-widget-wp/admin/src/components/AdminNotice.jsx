import { __ } from '@wordpress/i18n';

const AdminNotice = ({ adminNoticeVisible, setAdminNoticeVisible, adminNoticeText, adminNoticeStatus }) => {

    const handleButtonDismiss = (e) => {
        e.preventDefault();
        setAdminNoticeVisible( false );
    }

    if ( adminNoticeVisible ) {
        return (
            <div className={`notice ${adminNoticeStatus}is-dismissible`}>
                <p>{ adminNoticeText }</p>
                <button
                    onClick={handleButtonDismiss}
                    type="button"
                    className="notice-dismiss"
                >
                    <span className="screen-reader-text">{ __( 'Dismiss this notice.', 'weather-widget-wp' ) }</span>
                </button>
            </div>
        )
    } else return null
}
export default AdminNotice;
