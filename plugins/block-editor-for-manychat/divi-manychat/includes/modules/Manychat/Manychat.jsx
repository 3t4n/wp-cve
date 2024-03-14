// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class Manychat extends Component {

  static slug = 'dvmc_manychat';

  render() {
    return (
      <div>Manychat Widget: {this.props.__mc_widget_label}</div>
    );
  }
}

export default Manychat;
