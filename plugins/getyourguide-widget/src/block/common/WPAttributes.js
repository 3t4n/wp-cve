// @flow
import { Component } from 'react';

type WPAttributesProps = {
  attributes: Object,
  children: React.Node,
  defaultAttributes: Object,
  setAttributes: Function,
};

export default class WordPress extends Component<WPAttributesProps> {
  constructor(props) {
    super(props);
    const { defaultAttributes } = this.props;
    this.labels = Object.keys(defaultAttributes).reduce((acc, key) => {
      switch (key) {
      case 'cmp':
        return { ...acc, cmp: "Track your widget's performance with a campaign name (optional)" };
      case 'iata':
        return { ...acc, iata: 'IATA Airport Code' };
      case 'currency':
        return { ...acc, currency: 'Currency' };
      case 'locale_code':
        return { ...acc, locale_code: 'Language' };
      case 'number_of_items':
        return { ...acc, number_of_items: 'Number of items' };
      case 'q':
        return { ...acc, q: 'Search query' };
      default:
        return acc;
      }
    }, {});
    this.state = defaultAttributes;
  }

  componentDidUpdate(_, prevState) {
    const { attributes, defaultAttributes, setAttributes } = this.props;
    setAttributes(
      Object.keys({ ...defaultAttributes, ...attributes }).reduce((acc, key) => {
        const { [key]: update } = this.state;
        return prevState[key] !== update ? { ...acc, [key]: update } : acc;
      }, {}),
    );
  }

  handleChange = (key = '', e = { preventDefault: () => null, target: { value: '' } }) => {
    e.preventDefault();
    this.setState({ [key]: e.target.value });
  };

  render() {
    const { labels, handleChange, state } = this;
    const { attributes, children, defaultAttributes } = this.props;
    return children({
      labels,
      state,
      attributes,
      defaultAttributes,
      handleChange,
    });
  }
}
