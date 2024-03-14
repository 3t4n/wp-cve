import React from 'react';
import { http } from '../utility/helper.js';
const $ = jQuery;

export default function Button() {
  const onClick = event => {
    event.preventDefault();
    const settings = $('#_reuse_builder_settings').val();
    let data = {};
    try {
      data = JSON.parse(settings);
    } catch (e) {}
    data.reuseb_settings = settings;
    data.action = REUSEB_AJAX_DATA.action;
    data.nonce = REUSEB_AJAX_DATA.nonce;
    data.action_type = 'update_option';
    http.post(data).end(function(err, res) {
      if (res) {
        window.location.reload();
      }
    });
  };
  var btnStyle = {
    marginTop: '30px'
  };
  return (
    <button style={btnStyle} className="reuseb-btn button button-primary button-large" type="button" onClick={onClick}>
      {' '}
      Save{' '}
    </button>
  );
}
