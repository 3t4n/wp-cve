import React, { Component } from 'react';
import { connect } from 'react-redux';
import Field from './field/fieldIndex';
import Relation from './relation/relationIndex';
import Block from './block/blockIndex';
import Consequences from './consequences/consequencesIndex';
import clone from 'clone';

export default class LogicPanel extends Component {
  constructor(props) {
    super(props);
    this.handleKeyType = this.handleKeyType.bind(this);
    this.addKey = this.addKey.bind(this);
    this.updateData = this.updateData.bind(this);
    this.createArray = this.createArray.bind(this);
    this.handleAddConsequences = this.handleAddConsequences.bind(this);
    this.updateEffectField = this.updateEffectField.bind(this);
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
    const { logicBlock } = this.state;
    const tempLogicBlock = [];
    logicBlock.map(function(block, index) {
      if (block.id === id) {
        tempLogicBlock.push(data);
      } else {
        tempLogicBlock.push(block);
      }
    });
    this.setState({ logicBlock: tempLogicBlock });
  }
  handleKeyType() {
    const style = {
      'paddingBottom': '10px',
    };
    return(
      <div className="" style={style}>
        <button onClick={this.addKey.bind(this, 'field')}>Field</button>
        <button onClick={this.addKey.bind(this, 'block')}>Block</button>
      </div>
    );
  }

  createArray(keyType) {
    let obj = {};
    switch(keyType) {
      case 'field':
        obj = {
          id: new Date().getTime(),
          key: keyType,
          value: {
              fieldID: null,
              secondOperand: {
                type: null,
                value: null,
              },
              operator: null,
          },
          childresult: null,
        }
        break;
      case 'relation':
        obj = {
          id: new Date().getTime(),
          key: keyType,
          value: null,
        }
        break;
      default:
        const date = new Date().getTime();
        obj =  {
          id: date,
          key: 'innerExpression',
          value: [
            {
              id: date + '-first',
              key: 'field',
              value:
              {
                fieldID: null,
                secondOperand: {
                  type: null,
                  value: null,
                },
                operator: null,
              },
              childresult: null,
            },
            {
              id: date + '-second',
              key: 'relation',
              value: null,
            },
            {
              id: date + '-third',
              key: 'field',
              value:
              {
                fieldID: null,
                secondOperand: {
                  type: null,
                  value: null,
                },
                operator: null,
              },
              childresult: null,
            }
          ],
          childresult: null,
        };
        break;
    }
    return obj;
  }
  addKey(keyType, e) {
    e.preventDefault();
    let { logicBlock } = this.state;
    let obj = {};
    if (logicBlock.length === 0) {
      logicBlock.push(this.createArray(keyType));
    } else {
      logicBlock.push(this.createArray('relation'));
      logicBlock.push(this.createArray(keyType));
    }
    this.setState({ logicBlock });
  }
  handleAddConsequences(e) {
    e.preventDefault();
    const { effectField } = this.state;
    let obj = {};
    obj.action = null;
    obj.fieldId = null;
    obj.id = new Date().getTime();
    effectField.push(obj);
    this.setState({ effectField });
  }
  updateEffectField(id, data) {
    const { effectField } = this.state;
    const tempEffectField = [];
    effectField.map(function(field, index) {
      if (field.id === id) {
        tempEffectField.push(data);
      } else {
        tempEffectField.push(field);
      }
    });
    this.setState({ effectField: tempEffectField });
  }
  render() {
    const { props } = this;
    const handleKeyType = this.handleKeyType();
    const { logicBlock, effectField } = this.state;
    const renderBlock = (block, index) => {
      switch(block.key) {
        case 'field':
          return <Field updateData={this.updateData} block={block} fields={props.fields} key={index}/>;
        case 'relation':
          return <Relation updateData={this.updateData} block={block} key={index}/>;
        default:
          return <Block updateData={this.updateData} block={block} key={index} fields={props.fields} />;
      }
      // return <Field />;
    }
    const renderEffectField = (effectField, index) => {
      return <Consequences key={index} fields={props.fields} block={effectField} updateEffectField={this.updateEffectField} />;
    };
    const style = {
      'borderTopStyle': 'dashed',
      'borderWidth': '2px'
    };
    return (<div className="scwpFormSettingsMainWrapper">
      {handleKeyType}
      {logicBlock.length ? logicBlock.map(renderBlock) : null}
      <div style={style}>
      <button onClick={this.handleAddConsequences}>Add Consequences</button>
      {effectField.length ? effectField.map(renderEffectField) : null}
      </div>
      {JSON.stringify(effectField)}
    </div>);
  }
}
