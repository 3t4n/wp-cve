<?php

interface ICBroChat {
  function get_id();
  function get_title();
  function get_is_main_chat();
  function get_guid();
  function get_display();
  function get_selected_pages();
  function get_display_to_guests();
}

interface ICBroChatsBackend {
  // Прочитать сохраненное значение параметра
  function get();
  // Сохранить значение параметра
  function set($value);
  // Удаление параметра из базы
  function del();
  // Отложить запись в хранилище до вызова flush (можно использовать когда параметры
  // хранятся не в виде отдельных записей в базе, а одним куском, чтобы не пере
  // писывать их по 10 раз)
  function postpone_write();
  function flush();
}

?>
