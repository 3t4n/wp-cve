// Libraries.
import { Button, Modal, message } from 'antd';
import { useState } from 'react';

// Global Variables.
declare var jQuery: any;
declare var acfwAdminApp: any;

// Props.
interface Props {
  customer: any;
}

/**
 * Remind Button in Admin Manage Store Credit Customers Page
 *
 * @since 3.5.5
 *
 * @param {Props} props Component props.
 *
 * @return {JSX.Element} JSX Element.
 * */
export default (props: Props) => {
  const {
    store_credits_page: { rest_url, labels, nonce },
  } = acfwAdminApp;
  const { customer } = props;
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isSendingEmail, setIsSendingEmail] = useState(false);
  const [messageApi, contextHolder] = message.useMessage();

  // Trigger Show Modal.
  const showModal = () => {
    setIsModalOpen(true);
  };

  // Get Preview URL
  const GetPreviewURL = () => {
    let preview_url = `${acfwAdminApp.admin_url}admin-ajax.php?action=acfwp_store_credit_reminder_preview_email`;
    preview_url +=
      customer?.first_name && customer?.last_name ? `&args[name]=${customer?.first_name} ${customer?.last_name}` : '';
    preview_url += customer?.email ? `&args[email]=${customer?.email}` : '';
    preview_url += customer?.id ? `&args[user_id]=${customer?.id}` : '';
    preview_url += `&_wpnonce=${nonce?.preview}`;
    return preview_url;
  };

  // Trigger Close Modal.
  const closeModal = () => {
    setIsModalOpen(false);
  };

  // Trigger Preview.
  const handlePreview = () => {
    setIsModalOpen(false);
    window.open(GetPreviewURL());
  };

  // Trigger Send.
  const handleSend = () => {
    setIsSendingEmail(true);

    // Handle UI State after sending email.
    const handleUIState = () => {
      setIsSendingEmail(false);
      setIsModalOpen(false);
    };

    // Send Email.
    jQuery.ajax({
      type: 'GET',
      url: rest_url,
      data: {
        user_id: customer?.id,
      },
      success: function (response: any) {
        if (!response.status || response.status === 'error') {
          messageApi.open({
            type: 'error',
            content: `${labels?.modal?.error?.heading}: ${response?.message}`,
          });
        } else {
          messageApi.open({
            type: 'success',
            content: (
              <>
                {labels?.modal?.success?.text}
                {customer?.email ? ` <${customer?.email}>.` : ''}
              </>
            ),
          });
        }

        handleUIState();
      },
      error: function (xhr: any, status: any, error: any) {
        // extract WP_Error object response message if available
        if (xhr?.responseJSON?.message) {
          error = xhr.responseJSON.message;
        }

        // handle errors
        messageApi.open({
          type: 'error',
          content: `${labels?.modal?.error?.heading}: ${error}`,
        });

        handleUIState();
      },
    });
  };

  // Return null if remind label is not set.
  if (!labels?.remind) {
    return null;
  }

  // Return JSX Element.
  return (
    <>
      {contextHolder}
      <Button onClick={showModal}>{labels.remind}</Button>
      <Modal
        title={labels?.modal?.heading}
        className={'acfw-modal send-a-reminder-modal'}
        width={500}
        open={isModalOpen}
        onCancel={closeModal}
        footer={[
          <Button key="preview" className={'action-preview'} onClick={handlePreview} data-url={GetPreviewURL()}>
            {labels?.modal?.button?.preview}
          </Button>,
          <Button key="send" className={'action-send'} type="primary" onClick={handleSend} disabled={isSendingEmail}>
            {isSendingEmail ? `${labels?.loading}...` : labels?.modal?.button?.send}
          </Button>,
        ]}
      >
        <p>{labels?.modal?.text}</p>
        <p>
          {customer?.first_name} {customer?.last_name}
          {customer?.email ? ` <${customer?.email}>` : ''}
        </p>
      </Modal>
    </>
  );
};
