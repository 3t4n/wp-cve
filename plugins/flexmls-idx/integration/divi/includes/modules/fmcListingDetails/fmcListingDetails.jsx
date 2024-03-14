// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class FMCD_fmcListingDetails extends Component {

  static slug = 'fmcd_hello_world';

  render() {
    const Content = this.props.content;

    return (
      <h1 className='denix'>
        <Content/> 
      </h1>
    );
  }
}

export default FMCD_fmcListingDetails;
