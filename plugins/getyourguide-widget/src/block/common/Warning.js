import React from 'react';

const Warning = () => (
  <strong className="notice notice-error" style={{ display: 'block' }}>
    {"You haven't entered your GetYourGuide partner ID."}
    {' '}
    <a href={`${window.location.origin}/wp-admin/options-general.php?page=getyourguide`}>
      Enter it here.
    </a>
  </strong>
);

export default Warning;
