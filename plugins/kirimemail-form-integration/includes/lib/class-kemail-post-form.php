<?php if (!defined('ABSPATH')) {
    exit;
}

class Kemail_Post_Form
{
    const POST_FORM = 'ke_page_form';
    private $table_name;
    private $wpdb;


    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . self::POST_FORM;
    }

    public function get($post_id)
    {
        $post = $this->wpdb->get_row("SELECT * FROM $this->table_name WHERE post_id = ".esc_sql($post_id));

        return $post;
    }

    public function save($post_id, $data)
    {
        return $this->wpdb->replace($this->table_name, array(
            'post_id' => $post_id,
            'widget' => $data['widget'],
            'bar' => $data['bar']
        ), array(
            '%d',
            '%s',
            '%s'
        ));
    }

    public function remove($post_id)
    {
        return $this->wpdb->delete($this->table_name, array('post_id' => $post_id));
    }
}
