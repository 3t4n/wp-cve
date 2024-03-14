import React, { Component } from 'react';
import { connect } from 'react-redux';
import Field from './field/fieldIndex';
import Relation from './relation/relationIndex';
import Block from './block/blockIndex';
import Consequences from './consequences/consequencesIndex';
import clone from 'clone';
import Styles from './logic.less';
// import Styles from '../panel.less';

export default class LogicPanel extends Component {
  constructor(props) {
    super(props);
    this.handleKeyType = this.handleKeyType.bind(this);
    this.addKey = this.addKey.bind(this);
    this.updateData = this.updateData.bind(this);
    this.handleAddConsequences = this.handleAddConsequences.bind(this);
    this.updateEffectField = this.updateEffectField.bind(this);
    this.addCondition = this.addCondition.bind(this);
    this.handleAddCondition = this.handleAddCondition.bind(this);
    this.handleAddFieldTOBlock = this.handleAddFieldTOBlock.bind(this);
    this.deleteData = this.deleteData.bind(this);
    this.state = {
      logicBlock: [],
      effectField: [],
    };
  }
  componentWillMount() {

  }
  componentWillReceiveProps(nextProps) {

  }
  componentDidUpdate() {

  }
  updateData(id, data) {
    const uid = this.props.id;
    this.props.updateBlockData(id, data, uid );
  }
  handleKeyType() {
    return(
      <div className={Styles.scwpFieldBlockBtnWrapper}>
        <button className={`${Styles.scwpFieldBlockBtn} ${Styles.scwpFieldBtn}`} onClick={this.addKey.bind(this, 'field')}>Field</button>
        <button className={`${Styles.scwpFieldBlockBtn} ${Styles.scwpBlockBtn}`} onClick={this.addKey.bind(this, 'block')}>Block</button>
      </div>
    );
  }
  handleAddCondition(e) {
    e.preventDefault();
    this.props.addLogicBlock();
  }
  addCondition() {
    return<button className={Styles.scwpAddConditionBtn} onClick={this.handleAddCondition}>Add Condition</button>;
  }
  addKey(keyType, e) {
    e.preventDefault();
    const uid = this.props.id;
    this.props.addSingleLogicBlock(keyType, uid);
  }
  handleAddConsequences(e) {
    e.preventDefault();
    const uid = this.props.id;
    this.props.addEffectField(uid);
  }
  updateEffectField(id, data) {
    const uid = this.props.id;
    this.props.updateEffectField(id, data, uid);
  }
  handleAddFieldTOBlock(id) {
    const uid = this.props.id;
    this.props.addFieldTOBlock(id, uid);
  }
  deleteData(id) {
    const uid = this.props.id;
    this.props.deleteSingleLogicBlockField(id, uid);
  }
  render() {
    const { props } = this;
    const handleKeyType = this.handleKeyType();
    const addCondition = this.addCondition();
    const { logicBlock, effectField, allLogicBlock, id } = this.props;
    const renderBlock = (block, index) => {
      switch(block.key) {
        case 'field':
          return <Field updateData={this.updateData} deleteData={this.deleteData} block={block} fields={props.fields} key={index}/>;
        case 'relation':
          return <Relation updateData={this.updateData} block={block} key={index}/>;
        default:
          return <Block updateData={this.updateData} deleteBlockField={props.deleteBlockField} deleteData={this.deleteData} id={id} block={block} key={index} fields={props.fields} addFieldTOBlock={this.handleAddFieldTOBlock}/>;
      }
      // return <Field />;
    }
    const renderEffectField = (effectField, index) => {
      return <Consequences id={id} deleteConsequence={props.deleteConsequence} key={index} fields={props.fields} block={effectField} updateEffectField={this.updateEffectField} />;
    };
    const style = {
      'borderTopStyle': 'dashed',
      'borderWidth': '2px'
    };
    // console.log(props);

    return (<div className="scwpFormSettingsWrapper">
      <div className={Styles.scwpConditionFieldsSection}>
        <div className={Styles.scwpFieldsSection}>
          {id && handleKeyType}
          <div className={Styles.scwpConditionArrayWrapper}>{id && logicBlock.length ? logicBlock.map(renderBlock) : null} </div>
        </div>
        <div className={Styles.scwpConditionConsequencesSection}>
          {id ? <button className={Styles.scwpAddConsequencesBtn} onClick={this.handleAddConsequences}>Add Consequences</button> : null}
          <div className={Styles.scwpConditionConsequencesWrapper} >{ id && effectField.length ? effectField.map(renderEffectField) : null}</div>
        </div>
      </div>
    </div>);
  }
}
