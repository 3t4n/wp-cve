<?php

require_once('exceptions.php');
require_once('interfaces.php');

class CBroChat implements ICBroChat, JsonSerializable
{
    private $backend;
    private $id;
    private $title;
    private $is_main_chat;
    private $guid;
    private $display;
    private $selected_pages;
    private $display_to_guests;
    private $connections;
    private $costs;
    private $alerts;
    private $created_at;


    const id = "chatbro_chat_id";
    const title = "chatbro_chat_title";
    const is_main_chat = "chatbro_chat_is_main_chat";
    const guid = "chatbro_chat_guid";
    const display = "chatbro_chat_display";
    const selected_pages = 'chatbro_chat_selected_pages';
    const display_to_guests = "chatbro_chat_display_to_guests";
    const connections = "chatbro_chat_connections";
    const costs = "chatbro_chat_costs";
    const alerts = "chatbro_chat_alerts";
    const created_at = "chatbro_chat_created_at";

    const display_options = array(
        'everywhere' => 'Everywhere',
        'frontpage_only' => 'Front page only',
        'except_listed' => 'Everywhere except those listed',
        'only_listed' => 'Only the listed pages',
        'disable' => 'Disable'
    );


    public function __construct(
        $id,
        $title,
        $is_main_chat,
        $guid,
        $created_at,
        $display = 'disable',
        $selected_pages = false,
        $display_to_guests = true
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->is_main_chat = $is_main_chat;
        $this->guid = $guid;
        $this->display = self::sanitize_display($display);
        $this->selected_pages = $selected_pages;
        $this->display_to_guests = self::sanitize_checkbox($display_to_guests);
        $this->created_at = $created_at;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_is_main_chat()
    {
        return $this->is_main_chat;
    }

    public function get_guid()
    {
        return $this->guid;
    }

    public function get_display()
    {
        return $this->display;
    }

    public function get_display_text()
    {
        return self::display_options[$this->display];
    }

    public function get_selected_pages()
    {
        return $this->selected_pages;
    }

    public function get_display_to_guests()
    {
        return $this->display_to_guests;
    }

    public function get_connections()
    {
        return $this->connections;
    }

    public function get_costs()
    {
        return $this->costs;
    }

    public function get_alerts()
    {
        return $this->alerts;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function set_alerts($alerts)
    {
        $this->alerts = $alerts;
    }

    public function set_connections($connections)
    {
        $this->connections = $connections;
    }

    public function set_costs($costs)
    {
        $this->costs = $costs;
    }

    public function set_title($title)
    {
        $this->title = $title;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function set_display($display)
    {
        $this->display = self::sanitize_display($display);
    }

    public function set_selected_pages($selected_pages)
    {
        $this->selected_pages = $selected_pages;
    }

    public function set_display_to_guest($display_to_guests)
    {
        $this->display_to_guests = self::sanitize_checkbox($display_to_guests);
    }


    public function jsonSerialize()
    {
        return [
            CBroChat::id => $this->id,
            CBroChat::title => $this->title,
            CBroChat::is_main_chat => $this->is_main_chat,
            CBroChat::guid => $this->guid,
            CBroChat::display => $this->display,
            CBroChat::selected_pages => $this->selected_pages,
            CBroChat::display_to_guests => $this->display_to_guests,
            CBroChat::created_at => $this->created_at,
        ];
    }

    private static function sanitize_display($val)
    {
        if (!in_array($val, array_keys(self::display_options))) {
            throw new CBroSanitizeError(__("Invalid show popup chat option value", 'chatbro'), CBroSanitizeError::Error);
        }
        return $val;
    }

    private static function sanitize_checkbox($val)
    {
        return ($val == "true");
    }
}

?>