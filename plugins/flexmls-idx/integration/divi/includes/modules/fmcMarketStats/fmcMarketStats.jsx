import React, { Component } from 'react';

// Internal Dependencies

class FMCD_fmcMarketStats extends Component {

  static slug = 'fmcd_fmcmarketstats';

  render() {
    const Content = this.props.content;

    return (
      <h1 className='fmcMarketStats'>
        <Content/> 
      </h1>
    );
  }
}

export default FMCD_fmcMarketStats;