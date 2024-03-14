<?php
  if (!trinity_get_install_key()) return;
  if (!trinity_should_migrate_for('5.4.4')) return;

  trinity_phbu_start();

  // remove legacy fields (in next migration these should be removed)
  //  global $wpdb;
  //  $wpdb->delete($wpdb->postmeta, ['meta_key' => 'trinity_audio_post_hash_content_title']);
  //  $wpdb->delete($wpdb->postmeta, ['meta_key' => 'trinity_audio_post_hash_content']);
  //  $wpdb->delete($wpdb->postmeta, ['meta_key' => 'trinity_audio_post_hash_content_excerpt']);
  //  $wpdb->delete($wpdb->postmeta, ['meta_key' => 'trinity_audio_post_hash_content_excerpt_title']);
