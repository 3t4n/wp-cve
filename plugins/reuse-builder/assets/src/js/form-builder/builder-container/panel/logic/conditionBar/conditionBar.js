import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import Styles from './conditionBar.less';

export default class ConditionBar extends Component {
  constructor(props) {
    super(props);
    this.openLogicBlock = this.openLogicBlock.bind(this);
    this.deleteLogicButton = this.deleteLogicButton.bind(this);
    this.state = {
    	isOpen: false,
    	block: null,
    };
  }
  componentWillMount() {

  }
  componentWillReceiveProps(nextProps) {

  }
  componentDidUpdate() {

  }
  deleteLogicButton(e) {
    e.preventDefault();
    this.props.deleteLogicBlock(this.props.block.id);
  }
  openLogicBlock(e) {
    e.preventDefault();
    this.props.selectLogicBlock(this.props.block.id);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    return (<div className={props.block.id === props.id ? `${Styles.active} ${Styles.scwpConditionField}` : Styles.scwpConditionField} >
      <h3 onClick={this.openLogicBlock}>{props.title}</h3>
      <button type="button" className={Styles.scwpDeleteCondition} onClick={this.deleteLogicButton}></button>
    </div>);
  }
}
