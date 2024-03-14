import React from 'react';
import renderer from 'react-test-renderer';
import Search from '../block/Search/Search';

describe('Search', () => {
  it('renders correctly with no props', () => {
    const tree = renderer.create(<Search />).toJSON();
    expect(tree).toMatchSnapshot();
  });
});
