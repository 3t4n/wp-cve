// @flow
import React, { Fragment } from 'react';

export const Input = ({
  label,
  value,
  onChange,
  ...passThrough
}: {
  label: string,
  value: string,
  onChange: Function,
}) => (
  <div>
    <label>
      {label}
      <input
        {...{
          style: { display: 'block' },
          onChange,
          value,
          ...passThrough,
        }}
      />
    </label>
  </div>
);

export const Select = ({
  data = {},
  value,
  onChange,
  label,
}: {
  data: Object,
  value: string,
  onChange: Function,
  label: string,
}) => (
  <label style={{ display: 'block' }}>
    {label}
    <select style={{ display: 'block' }} value={value} onBlur={onChange} onChange={onChange}>
      {Object.keys(data).map((option) => {
        const { value: optionValue, label: optionLabel } = data[option];
        return (
          <option key={optionLabel} {...{ value: optionValue }}>
            {optionLabel}
          </option>
        );
      })}
    </select>
  </label>
);

export const Inputs = ({
  data,
  keys,
  labels,
  onChange,
  selects = [],
  values,
}: {
  data: Object,
  keys: Array<string>,
  labels: Object,
  onChange: Function,
  selects: Array<string>,
  values: Object,
}) => (
  <Fragment>
    {keys.map((key) => {
      if (selects.includes(key)) {
        return (
          <Select
            key={key}
            {...{
              data: data[key],
              label: labels[key],
              value: values[key],
              onChange: e => onChange(key, e),
            }}
          />
        );
      }
      return (
        <Input
          key={key}
          {...{
            label: labels[key],
            onChange: e => onChange(key, e),
            type: Number.isInteger(parseInt(values[key], 10)) ? 'number' : 'text',
            value: values[key],
          }}
        />
      );
    })}
  </Fragment>
);
