import React from 'react';
import renderer from 'react-test-renderer';
import { Iframe } from '../block/common/Iframe';

describe('Iframe', () => {
  it('renders correctly with no props', () => {
    const tree = renderer.create(<Iframe />).toJSON();
    expect(tree).toMatchSnapshot();
  });
  it('renders a city widget', () => {
    const tree = renderer.create(<Iframe widgetType="city" />).toJSON();
    expect(tree).toMatchSnapshot();
  });
});
