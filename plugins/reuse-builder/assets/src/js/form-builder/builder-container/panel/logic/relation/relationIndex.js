import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import Styles from './relationindex.less';

export default class Relation extends Component {
  constructor(props) {
    super(props);
    this.relationSelect = this.relationSelect.bind(this);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.handleSwitch = this.handleSwitch.bind(this);
    this.state = {
    	isOpen: false,
    	block: null,
    };
    this.relation = [
    {
    	label: 'not',
    	value: 'not',
    }, {
    	label: 'or',
    	value: 'or',
    }];
  }
  componentWillMount() {
		const { fields, block } = this.props;
		this.setState({ block: block });
  }
  componentWillReceiveProps(nextProps) {
  	this.setState({ block: nextProps.block });
  }
  componentDidUpdate() {

  }
  handleCollapse(type, e) {
  	e.preventDefault();
  	this.setState({ isOpen: !this.state.isOpen });
  }
  relationSelect(val) {
  	const { block } = this.state;
  	block.value = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  handleSwitch(value, e) {
    e.preventDefault();
    const { block } = this.props;
    if (block.value === value)
      return;
    block.value = value;
    this.props.updateData(block.id, block);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    const style = {
      'paddingBottom': '10px',
      'paddingTop': '10px',
    };
    const secondOperandStyle = {
      'borderStyle': 'solid',
    	'borderWidth': '2px 2px 2px 2px',
    	'width': '400px'
    };
    return (<div className={Styles.scwpConditionArrayRelation} >
      <span className={props.block.value === 'or' ? `${Styles.scwpRealtionBtn} ${Styles.scwpOrRealtion} ${Styles.active}` : `${Styles.scwpRealtionBtn} ${Styles.scwpOrRealtion}`} onClick={this.handleSwitch.bind(this, 'or')}>Or</span>
      <span className={props.block.value === 'zero' ? `${Styles.scwpRealtionBtn} ${Styles.scwpNoRealtion} ${Styles.active}` : `${Styles.scwpRealtionBtn} ${Styles.scwpNoRealtion}`} onClick={this.handleSwitch.bind(this, 'zero')}>No</span>
      <span className={props.block.value === 'and' ? `${Styles.scwpRealtionBtn} ${Styles.scwpAndRealtion} ${Styles.active}` : `${Styles.scwpRealtionBtn} ${Styles.scwpAndRealtion}`} onClick={this.handleSwitch.bind(this, 'and')}>And</span>
    </div>);
  }
}




/*

<Collapse isOpened={this.state.isOpen}>
  <div>
    <div>
      <span> FieldId</span>
    </div>
    <div>
      <Select
          name="form-field-name"
          options={this.relation}
          multi={false}
          value={props.block.value}
          onChange={this.relationSelect}
      />
    </div>
  </div>
</Collapse>



*/
