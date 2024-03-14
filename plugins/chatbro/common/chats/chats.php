<?php

require_once(__DIR__ . '/../core/backendable.php');
require_once(__DIR__ . '/../admin/admin.php');
require_once('chat.php');

class CBroChatsIterator implements Iterator
{
  private $keys;
  private $pos;

  public function __construct($keys)
  {
    $this->keys = $keys;
    $this->pos = 0;
  }

  public function rewind()
  {
    $this->pos = 0;
  }

  public function current()
  {
    return CBroChats::get($this->keys[$this->pos]);
  }

  public function key()
  {
    return $this->keys[$this->pos];
  }

  public function next()
  {
    ++$this->pos;
  }

  public function valid()
  {
    return isset($this->keys[$this->pos]);
  }
}

class CBroChatNotFound extends Exception
{
  public function __construct($id = null)
  {
    $msg = 'Chat not found';

    if ($id)
      $msg .= ": {$id}";
    parent::__construct($msg);
  }
}

class CBroChats extends CBroBackendable
{
  private $chats;
  static $main_chat_id = 1;
  static $main_chat_title = 'Trollbox';

  protected function __construct($backend)
  {
    parent::__construct($backend);
    $this->chats = array();
  }

  public static function init_chats()
  {
    try {
      $list = json_decode(self::get_backend()->get(), true);

      // Sort by created_at
      usort($list, function ($chat1, $chat2) {
        return $chat1[CBroChat::created_at] < $chat2[CBroChat::created_at];
      });

      foreach ($list as $key => $chat) {

        if ($chat[CBroChat::is_main_chat] && $chat[CBroChat::id]) {
          self::$main_chat_id = $chat[CBroChat::id];
        }

        self::init_chat(
          new CBroChat(
            $chat[CBroChat::id],
            $chat[CBroChat::title],
            $chat[CBroChat::is_main_chat],
            $chat[CBroChat::guid],
            $chat[CBroChat::created_at],
            $chat[CBroChat::display],
            $chat[CBroChat::selected_pages],
            $chat[CBroChat::display_to_guests]
          )
        );
      }

    } catch (CBroChatsNotFound $ex) {
      $chat = CBroChats::create_default_chat();
      CBroChats::init_chat($chat);
      CBroChats::save();
    }
  }

  public static function create_default_chat()
  {
    $date = new DateTimeImmutable();
    $milli = (int) $date->format('Uv');

    return new CBroChat(
      self::$main_chat_id,
      self::$main_chat_title,
      true,
      CBroSettings::get(CBroSettings::guid),
      $milli,
      CBroSettings::get(CBroSettings::display),
      CBroSettings::get(CBroSettings::selected_pages),
      CBroSettings::get(CBroWPSettingsBackend::display_to_guests)
    );
  }

  public static function init_chat($chat)
  {
    self::get_instance()->chats[$chat->get_id()] = $chat;
  }

  public static function get($id)
  {
    if (!array_key_exists($id, self::get_instance()->chats)) {
      throw new CBroChatNotFound($id);
    }

    return self::get_instance()->chats[$id];
  }

  public static function get_default_chat()
  {
    try {
      return self::get(self::$main_chat_id);
    } catch (CBroChatNotFound $ex) {
      // $chat = self::create_default_chat();
      // CBroChats::init_chat($chat);
      // CBroChats::save();
      // return $chat;
    }
  }

  public static function create_chat($id, $guid, $save = true)
  {
    $date = new DateTimeImmutable();
    $milli = (int) $date->format('Uv');

    $chat = new CBroChat(
      $id,
      $id,
      false,
      $guid,
      $milli
    );

    self::init_chat($chat);

    if ($save) {
      self::save();
    }

    return $chat;
  }

  public static function update($chat)
  {
    self::get($chat->get_id());
    self::get_instance()->chats[$chat->get_id()] = $chat;
  }

  public static function change_chat_id($chat, $new_id)
  {
    self::delete($chat->get_id());
    $chat->set_id($new_id);
    self::get_instance()->chats[$chat->get_id()] = $chat;
  }

  public static function delete($id)
  {
    self::get($id);
    unset(self::get_instance()->chats[$id]);
  }

  public static function deleteAll()
  {
    foreach (CBroChats::iterator() as $name => $chat) {
      unset(self::get_instance()->chats[$chat->get_id()]);
    }

    self::save();
  }

  public static function save()
  {
    self::get_backend()->set(
      self::prepare()
    );
  }

  public static function prepare()
  {
    $res = array();

    foreach (CBroChats::iterator() as $name => $chat) {
      array_push($res, $chat);
    }

    return json_encode($res);
  }

  public static function iterator()
  {
    return new CBroChatsIterator(array_keys(self::get_instance()->chats));
  }

  public static function isEmpty()
  {
    return empty(self::get_instance()->chats);
  }
}

?>