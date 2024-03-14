import React from 'react';
import renderer from 'react-test-renderer';
import CityEdit from '../block/city/Edit';

describe('CityEdit', () => {
  it('renders correctly with no props', () => {
    const tree = renderer.create(<CityEdit />).toJSON();
    expect(tree).toMatchSnapshot();
  });
});
