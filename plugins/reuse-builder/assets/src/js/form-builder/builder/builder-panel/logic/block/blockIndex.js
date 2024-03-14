import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import SingleField from '../field/fieldIndex';
import Relation from '../relation/relationIndex';
import clone from 'clone';
import Styles from './blockindex.less';

export default class Block extends Component {
  constructor(props) {
    super(props);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.updateBlockData = this.updateBlockData.bind(this);
    this.state = {
    	isOpen: false,
    	block: null,
    };
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
  // relationSelect(val) {
  // 	const { block } = this.state;
  // 	block.value = val !== null ? val.value : val;
  // 	this.props.updateData(block.id, block);
  // }
  updateBlockData(id, data) {
    const { block } = this.props;
    const tempValue = []
    block.value.map(function(singleBlock) {
      if (singleBlock.id === id) {
        tempValue.push(data);
      } else {
        tempValue.push(singleBlock);
      }
    });
    block.value = clone(tempValue);
    this.props.updateData(block.id, block);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    // const style = {
    //   'paddingBottom': '10px',
    //   'paddingTop': '10px',
    // };
    return (<div className={Styles.scwpConditionSingleBlock}>
    	<button className={Styles.scwpConditionSingleBlockBtn} onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Block</button>
			<Collapse isOpened={this.state.isOpen} className={Styles.scwpCollapsibleWrapper}>
				<div>
          <SingleField updateData={this.updateBlockData} fields={props.fields} block={props.block.value[0]}/>
          <Relation updateData={this.updateBlockData} block={props.block.value[1]} />
          <SingleField updateData={this.updateBlockData} fields={props.fields} block={props.block.value[2]} />
				</div>
			</Collapse>
    </div>);
  }
}
