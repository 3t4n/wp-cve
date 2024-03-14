import React, { Component, Fragment } from 'react';
import Edit from '../common/Edit';
import { Inputs } from '../common/util';

class ActivitiesEdit extends Component {
  constructor(props) {
    super(props);
    this.filterKeys = (keys, group = 'primary') => {
      const primaryKeys = ['currency', 'locale_code', 'number_of_items', 'q'];
      return group === 'primary' ? primaryKeys : keys.filter(key => !primaryKeys.includes(key));
    };
    this.wpClassNames = {
      button: 'components-button is-button is-default is-large',
    };
    this.toggleAdditionalOptions = this.toggleAdditionalOptions.bind(this);
    this.state = { showAdditionalOptions: false };
  }

  toggleAdditionalOptions(e) {
    e.preventDefault();
    this.setState(({ showAdditionalOptions }) => ({
      showAdditionalOptions: !showAdditionalOptions,
    }));
  }

  render() {
    const { filterKeys, toggleAdditionalOptions, wpClassNames } = this;
    const { showAdditionalOptions } = this.state;
    return (
      <Edit {...this.props}>
        {({
          data, keys, labels, handleChange, state,
        }) => (
          <Fragment>
            <Inputs
              {...{
                keys: filterKeys(keys),
                data,
                labels,
                onChange: handleChange,
                values: state,
                selects: ['currency', 'locale_code'],
              }}
            />
            <button
              style={{ alignSelf: 'flex-start' }}
              className={wpClassNames.button}
              onClick={toggleAdditionalOptions}
              type="button"
            >
              Show additional options
            </button>
            {showAdditionalOptions && (
              <Inputs
                {...{
                  keys: filterKeys(keys, 'secondary'),
                  data,
                  labels,
                  onChange: handleChange,
                  values: state,
                }}
              />
            )}
          </Fragment>
        )}
      </Edit>
    );
  }
}

export default ActivitiesEdit;
