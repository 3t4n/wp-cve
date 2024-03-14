import React, { Component } from 'react';

class FMCD_fmcPhotos extends Component {

  static slug = 'fmcd_fmcphotos';

  render() {
    const Content = this.props.content;

    return (
      <h1 className='fmcd_class'>
        <Content/> 
      </h1>
    );
  }
}

export default FMCD_fmcPhotos;