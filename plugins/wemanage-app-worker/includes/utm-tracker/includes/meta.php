<?php defined('ABSPATH') || exit;

return array(

  'cookie_consent' => array(
    'label' => 'Cookie Consent',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'email', 'export')
  ),

  'cookie_expiry' => array(
    'label' => 'Cookie Expiry',
    'type' => 'integer',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),

  'conversion_type' => array(
    'label' => 'Conversion Type',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'conversion_lag_human' => array(
    'label' => 'Conversion Lag',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'conversion_lag' => array(
    'label' => 'Conversion Lag (seconds)',
    'type' => 'integer',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'conversion_ts' => array(
    'label' => 'Conversion Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'conversion_date_utc' => array(
    'label' => 'Conversion Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'conversion_date_local' => array(
    'label' => 'Conversion Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'sess_visit' => array(
    'label' => 'Session Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'sess_visit_date_utc' => array(
    'label' => 'Session Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'sess_visit_date_local' => array(
    'label' => 'Session Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'sess_landing' => array(
    'label' => 'Session Landing Page',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'sess_landing_clean' => array(
    'label' => 'Session Landing Page (clean)',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'scope' => array('converted', 'active', 'export')
  ),

  'sess_referer' => array(
    'label' => 'Session Referer URL',
    'type' => 'url',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'sess_referer_clean' => array(
    'label' => 'Session Referer URL (clean)',
    'type' => 'url',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'sess_ga' => array(
    'label' => 'Google Analytics Client ID',
    'type' => 'text',
    'value' => '',
    'is_cookie' => true,
    'cookie_name' => '_ga',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'utm_1st_url' => array(
    'label' => 'First UTM URL',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'utm_1st_url_clean' => array(
    'label' => 'First UTM URL (clean)',
    'type' => 'url',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'utm_1st_visit' => array(
    'label' => 'First UTM Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_1st_visit_date_utc' => array(
    'label' => 'First UTM Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_1st_visit_date_local' => array(
    'label' => 'First UTM Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'utm_source_1st' => array(
    'label' => 'First UTM Source',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_medium_1st' => array(
    'label' => 'First UTM Medium',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_campaign_1st' => array(
    'label' => 'First UTM Campaign',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_term_1st' => array(
    'label' => 'First UTM Term',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_content_1st' => array(
    'label' => 'First UTM Content',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'utm_url' => array(
    'label' => 'Last UTM URL',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'utm_url_clean' => array(
    'label' => 'Last UTM URL (clean)',
    'type' => 'url',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'utm_visit' => array(
    'label' => 'Last UTM Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_visit_date_utc' => array(
    'label' => 'Last UTM Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_visit_date_local' => array(
    'label' => 'Last UTM Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),

  'utm_source' => array(
    'label' => 'Last UTM Source',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_medium' => array(
    'label' => 'Last UTM Medium',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_campaign' => array(
    'label' => 'Last UTM Campaign',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_term' => array(
    'label' => 'Last UTM Term',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'utm_content' => array(
    'label' => 'Last UTM Content',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'gclid_url' => array(
    'label' => 'GCLID URL',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'gclid_url_clean' => array(
    'label' => 'GCLID URL (clean)',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'gclid_visit' => array(
    'label' => 'GCLID Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'gclid_visit_date_utc' => array(
    'label' => 'GCLID Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'gclid_visit_date_local' => array(
    'label' => 'GCLID Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'gclid_value' => array(
    'label' => 'GCLID Value',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'fbclid_url' => array(
    'label' => 'FBCLID URL',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'fbclid_url_clean' => array(
    'label' => 'FBCLID URL (clean)',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'fbclid_visit' => array(
    'label' => 'FBCLID Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'fbclid_visit_date_utc' => array(
    'label' => 'FBCLID Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'fbclid_visit_date_local' => array(
    'label' => 'FBCLID Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'fbclid_value' => array(
    'label' => 'FBCLID Value',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'msclkid_url' => array(
    'label' => 'MSCLKID URL',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'msclkid_url_clean' => array(
    'label' => 'MSCLKID URL (clean)',
    'type' => 'url',
    'value' => '',
    'is_own_url' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'msclkid_visit' => array(
    'label' => 'MSCLKID Visit Date (timestamp)',
    'type' => 'timestamp',
    'value' => '',
    'is_cookie' => true,
    'rewrite_cookie' => true,
    'scope' => array('converted', 'active', 'export')
  ),
  'msclkid_visit_date_utc' => array(
    'label' => 'MSCLKID Visit Date (utc)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),
  'msclkid_visit_date_local' => array(
    'label' => 'MSCLKID Visit Date (local)',
    'type' => 'datetime',
    'value' => '',
    'scope' => array('converted', 'email', 'active', 'export')
  ),
  'msclkid_value' => array(
    'label' => 'MSCLKID Value',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  ),

  'created_by' => array(
    'label' => 'Created By',
    'type' => 'text',
    'value' => '',
    'scope' => array('converted', 'active', 'export')
  )

);
