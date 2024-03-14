import React, { Component } from 'react';
import { connect } from 'react-redux';
import ConditionBar from './conditionBar';
import * as logicActions from '../../../../builder-modules/logicModule';


class CondtionPanel extends Component {
  constructor(props) {
    super(props);
    this.handleAddCondition = this.handleAddCondition.bind(this);
  }
  handleAddCondition(e) {
    e.preventDefault();
    this.props.addLogicBlock();
  }
  render() {
    const {selectLogicBlock, addLogicBlock, allLogicBlock, id, deleteLogicBlock } = this.props;

    const allCondition = (condition, index) => {
      return <ConditionBar deleteLogicBlock={deleteLogicBlock} title={'condition-'+ (index+1)} id={id} key={index} block={condition} selectLogicBlock={selectLogicBlock} />;
    }
    return (<div className="scwpFormFieldsMainWrapper">
      <div className="scwpPanelHeadingWrapper">
        <h1>Condition</h1>
      </div>
      <div className="scwpConditionFieldsWrapper" >
        {allLogicBlock.length ? allLogicBlock.map(allCondition): null}
      </div>
      <div className="scwpAddFieldsWrapper">
       <button className="scwpAddFormFields" type="button" onClick={this.handleAddCondition}>Add Condition</button>
      </div>
    </div>);
  }
}

function mapStateToProps(state) {
  const builder = state.builder;
  return {
    allLogicBlock: state.logic.allLogicBlock,
    id: state.logic.id,
  };
}
export default connect( mapStateToProps, {
  ...logicActions,
})(CondtionPanel);
