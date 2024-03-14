import React from 'react';
import { Inputs } from '../common/util';
import Edit from '../common/Edit';

const CityEdit = props => (
  <Edit {...{ ...props, widgetType: 'city', wpWidgetType: 'wp_city' }}>
    {({
      data, keys, labels, handleChange, state,
    }) => (
      <Inputs
        {...{
          keys,
          data,
          labels,
          onChange: handleChange,
          values: state,
          selects: ['locale_code'],
        }}
      />
    )}
  </Edit>
);

export default CityEdit;
