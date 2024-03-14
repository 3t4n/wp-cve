import React, { Component } from 'react';

// Internal Dependencies
import './style-fmcAccount.css';


class FMCD_fmcAccount extends Component {

  static slug = 'fmcd_fmcaccount';

  render() {
    const Content = this.props.shown_fields;

    return (
      <h1 className='denixis'>
        <Content/> 
      </h1> 
    );
  }
}

export default FMCD_fmcAccount;