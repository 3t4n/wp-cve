<?php

namespace FluentSupport\App\Services\MailerInbox;

use FluentSupport\App\App;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\Framework\Support\Arr;
use Exception;

class MailBoxService
{
    /**
     * This `getMailBoxes` method is used to get all mailboxes.
     * @return array
     */
    public function getMailBoxes ()
    {
        $mailboxes = MailBox::all();

        foreach ($mailboxes as $mailbox) {
            $mailbox->tickets_count = Ticket::countTicketByMailBoxId($mailbox->id);
        }

        return [
            'mailboxes' => $mailboxes
        ];
    }

    /**
     * This `deleteMailBox` method is used to delete a mailbox.
     * @param int $mailBoxId The id of the mailbox to be deleted.
     * @param int $fallbackId The id of the fallback mailbox to be used.
     * @return array
     */
    public function deleteMailBox($mailBoxId, $fallbackId )
    {
        if ( $mailBoxId == $fallbackId ) {
            throw new \Exception('Fallback Box can not be the same as MailBox ID');
        }

        $box = MailBox::findOrFail($mailBoxId);
        $fallbackBox = MailBox::findOrFail($fallbackId);

        // if the mailbox is default, then make the fallback mailbox default
        if($box->is_default == 'yes'){
            $fallbackBox->is_default = 'yes';
            $fallbackBox->save();
        }

        /*
         * Action before delete a mailbox
         *
         * @since v1.0.0
         * @param object $box           Mailbox
         * @param object $fallbackBox   Fallback mailbox
         */
        do_action('fluent_support/before_delete_email_box', $box, $fallbackBox);

        $this->deleteAllMailBoxMeta($mailBoxId);

        MailBox::where('id', $mailBoxId)->delete();

        // let's transfer the tickets now
        Ticket::syncMailBoxId($mailBoxId, $fallbackId);

        /*
         * Action on mailbox delete
         *
         * @since v1.0.0
         * @param integer $mailBoxId
         * @param object $fallbackBox   Fallback mailbox
         */
        do_action('fluent_support/mailbox_deleted', $mailBoxId, $fallbackBox);

        return [
            'message' => __('Selected Business has been deleted', 'fluent-support')
        ];
    }

    /**
     * This `moveTickets` method is used to move tickets from one mailbox to another.
     * @param array $data
     * @param int $mailBoxId
     * @return array
     */
    public function moveTickets ($data, $mailBoxId )
    {
        $this->validateTicketMoving( $data, $mailBoxId );

        $newBox = MailBox::findOrFail($data['new_box_id']);
        $oldBox = MailBox::findOrFail($mailBoxId);

        /**
         * Action before tickets moved
         * @since v1.5.7
         * @param array $data
         * @param object $oldBox Current mailbox
         * @param object $newBox Mailbox where selected tickets will be moved
         */
        do_action( 'fluent_support/before_move_tickets', $data, $oldBox, $newBox );

        if (!empty($data['ticket_ids'])) {
            Ticket::whereIn('id', $data['ticket_ids'])
                ->update([
                    'mailbox_id' => $newBox->id
                ]);
        } else {
            // Move all ticket for the MailBox
            Ticket::where('mailbox_id', $mailBoxId)
                ->update([
                    'mailbox_id' => $newBox->id
                ]);
        }
        /**
         * Action after tickets moved
         * @since v1.5.7
         * @param array $data
         * @param object $oldBox Mailbox where selected tickets were moved from
         * @param object $newBox Mailbox where selected tickets were moved to
         */
        do_action( 'fluent_support/tickets_moved', $data, $oldBox, $newBox );

        return [
            'message' => __('All ticket moves to the selected Business', 'fluent-support')
        ];

    }

    /**
     * This `setAsDefault` method will set a business box as default. Update is_default column to yes for the selected mailbox and no for others.
     * @param int $mailBoxId
     * @return array
     */
    public function setAsDefault ($mailBoxId )
    {
        $box = MailBox::findOrFail($mailBoxId);

        $box->is_default = 'yes';
        $box->save();

        MailBox::where('id', '!=', $mailBoxId)
            ->update([
                'is_default' => 'no'
            ]);

        return [
            'message' => __('Selected Business has been set as default', 'fluent-support')
        ];
    }

    /**
     * This `getEmailSetups` method is used to get the email setups
     * @param int $boxId
     * @return array
     */
    public function getEmailsSetups ( $boxId )
    {
        $settings = new Settings();
        $box = MailBox::findOrFail( $boxId );

        $types = $settings->getEmailSettingsKeys();

        $req = [];
        foreach ( $types as $type ) {
            $req[] = $settings->getBoxEmailSettings( $box, $type );
        }

        return [
            'email_configs' => $req,
            'email_keys' => $types
        ];
    }

    /**
     * This `saveEmailSettings` method is used to save the email settings for the mailbox
     * @param object $request
     * @param int $boxId
     * @return array
     */
    public function saveEmailSettings ($emailType, $boxId, $data )
    {
        $settings = new Settings();

        $data['email_body'] = wp_kses_post($data['email_body']);

        $box = MailBox::findOrFail($boxId);

        $settings->saveBoxEmailSettings($box, $emailType, $data);

        return [
            'message' => __('Settings has been updated', 'fluent-support')
        ];
    }

    /**
     * This `getTickets` method is used to get the tickets for the given mailbox
     * @param array $filters
     * @param int $boxId
     * @return array
     */
    public function getTickets ($filters, $boxId )
    {
        if (!$boxId) {
            throw new \Exception('MailBox ID must be provided');
        }

        $ticketsQuery = Ticket::getTicketsQuery();

        $ticketsQuery->where('mailbox_id', $boxId);

        if ( $filters['customer_id'] ) {
            $ticketsQuery->where('customer_id', $filters['customer_id']);
        }

        if ( $filters['product_id'] ) {
            $ticketsQuery->where('product_id', $filters['product_id']);
        }

        if ( $filters['ticket_title']  ) {
            $ticketsQuery->where('title', 'LIKE', "%". $filters['ticket_title'] ."%");
        }

        return [
            'tickets' => $ticketsQuery->paginate()
        ];
    }

    private function validateTicketMoving ( $data, $mailBoxId )
    {

        if ($data['new_box_id'] == $mailBoxId) {
            throw new \Exception('New Box can not be the same as MailBox ID');
        }

        if ( $data['move_type'] == 'Custom' && empty($data['ticket_ids']) ) {
            throw new \Exception('Invalid request submitted, Select ticket first');
        }

        return true;
    }

    private function deleteAllMailBoxMeta($id)
    {
        $class = __NAMESPACE__ . '\MailBox';

        Meta::where('object_type', $class)
            ->where('object_id', $id)
            ->delete();
    }
}
