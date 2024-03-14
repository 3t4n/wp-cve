import React, { Component } from 'react';
import DataContext from './DataContext';
import { Iframe } from './Iframe';
import Warning from './Warning';
import { gygData } from '../config';

class Edit extends Component {
  state = {
    optionsDisplay: 'flex',
  };

  render() {
    const {
      children,
      defaultAttributes,
      attributes,
      labels,
      handleChange,
      state,
      widgetType,
    } = this.props;
    const { optionsDisplay: display } = this.state;
    const keys = Object.keys({ ...defaultAttributes, ...attributes });
    return (
      <DataContext.Consumer>
        {data => (
          <div>
            {gygData.partnerID.length === 0 && <Warning />}
            <button
              className="components-button"
              type="button"
              style={{ float: 'right' }}
              onClick={() => this.setState(({ optionsDisplay }) => ({
                optionsDisplay: optionsDisplay === 'flex' ? 'none' : 'flex',
              }))
              }
            >
              {`${display === 'none' ? 'Show' : 'Hide'} options`}
            </button>
            <div
              className="components-placeholder"
              style={{
                alignItems: 'initial',
                display,
                marginBottom: '10px',
                textAlign: 'left',
              }}
            >
              {children({
                data,
                keys,
                defaultAttributes,
                attributes,
                labels,
                handleChange,
                state,
              })}
            </div>
            <Iframe {...{ widgetType, queries: { ...defaultAttributes, ...attributes } }} />
          </div>
        )}
      </DataContext.Consumer>
    );
  }
}

export default Edit;
