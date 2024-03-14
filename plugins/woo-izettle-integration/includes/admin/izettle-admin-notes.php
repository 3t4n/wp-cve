<?php
/**
 * WooCommerce Admin (Dashboard) Giving feedback notes provider.
 *
 * Adds notes to the merchant's inbox about giving feedback.
 *
 * @package WooCommerce Admin
 */

namespace Automattic\WooCommerce\Admin\Notes;

defined('ABSPATH') || exit;

/**
 * WC_Admin_Notes_Giving_Feedback_Notes
 */
class WC_Admin_Notes_iZettle
{
    /**
     * Note traits.
     */
    use NoteTraits;

    /**
     * Add notes for admin giving feedback.
     */
    public static function add_activity_panel_inbox_note($id, $notice, $type = WC_Admin_Note::E_WC_ADMIN_NOTE_INFORMATIONAL)
    {
        try {
            self::possibly_add_activity_panel_inbox_note($id, $notice, $type);
        } catch (Exception $e) {
            WC_IZ()->logger->add(sprintf('add_activity_panel_inbox_note: %s', $e->getMessage()));
        }
    }

    /**
     *
     * type: 'error', 'warning', 'update', 'info'
     */
    protected static function possibly_add_activity_panel_inbox_note($id, $notice, $type)
    {

        $note_ids = self::get_notes_with_name($id);

        if (!empty($note_ids)) {
            $note = new WC_Admin_Note(reset($note_ids));
        } else {
            $note = new WC_Admin_Note();
        }

        $note->set_title(__('Zettle Integration', 'woo-izettle-integration'));
        $note->set_content($notice);
        $note->set_content_data((object) array());
        $note->set_type($type);
        $note->set_name('woo-izettle-integration-notice-' . $id);
        $note->set_source('woo-izettle-integration');
        $note->set_layout('banner');
        $note->save();
    }

    public static function clear($id = false)
    {

        if (false !== $id) {
            $note_ids = self::get_notes_with_name($id);
        } else {
            $note_ids = self::get_notes_with_source('woo-izettle-integration');
        }

        if (!empty($note_ids)) {
            foreach ($note_ids as $note_id) {
                $note = new WC_Admin_Note($note_id);
                $note->delete($note);
            }
        }

    }

    protected static function get_notes_with_name($id)
    {
        $data_store = \WC_Data_Store::load('admin-note');
        return $data_store->get_notes_with_name('woo-izettle-integration-notice-' . $id);
    }

    /**
     * Find all the notes with a source.
     *
     * @param string $source Source to search for.
     * @return array An array of matching note ids.
     */
    public static function get_notes_with_source($source)
    {
        global $wpdb;
        return $wpdb->get_col(
            $wpdb->prepare(
                "SELECT note_id FROM {$wpdb->prefix}wc_admin_notes WHERE source = %s ORDER BY note_id ASC",
                $source
            )
        );
    }

}
