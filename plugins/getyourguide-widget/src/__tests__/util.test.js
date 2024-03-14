import React from 'react';
import renderer from 'react-test-renderer';
import { Input, Select } from '../block/common/util';

describe('Input', () => {
  it('renders correctly with no props', () => {
    const tree = renderer.create(<Input />).toJSON();
    expect(tree).toMatchSnapshot();
  });
});

describe('Select', () => {
  it('renders correctly with no props', () => {
    const tree = renderer.create(<Select />).toJSON();
    expect(tree).toMatchSnapshot();
  });
});
