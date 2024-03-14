import React, { Component, Fragment } from 'react';
import './style.css';

class PleziForm extends Component {
  static slug = 'plz_plezi_form';

  render() {
    return (
      <Fragment>
        { !! this.props.plezi_form && (
          `[plezi form=${this.props.plezi_form}]`
      	) }
      </Fragment>
    );
  }
}

export default PleziForm;
