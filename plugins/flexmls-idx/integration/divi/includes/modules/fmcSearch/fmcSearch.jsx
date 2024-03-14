import React, { Component } from 'react';

class FMCD_fmcSearch extends Component {

  static slug = 'fmcd_fmcsearch';

  render() {
    const Content = this.props.content;

    return (
      <h1 className='fmcd_fmcsearch'>
        <Content/> 
      </h1>
    );
  }
}

export default FMCD_fmcSearch;