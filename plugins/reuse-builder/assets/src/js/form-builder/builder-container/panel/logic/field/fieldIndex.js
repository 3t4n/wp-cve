import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import Styles from './fieldindex.less';
import { randomNumber } from 'utility/helper';
// import getComponent from 'reuse-form/library/getComponent';
const Async = __REUSEFORM_COMPONENT__['Async'];
// console.log(getComponent);
const ReuseForm = __REUSEFORM__;

export default class Field extends Component {
  constructor(props) {
    super(props);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.fieldIdSelect = this.fieldIdSelect.bind(this);
    this.logChange = this.logChange.bind(this);
    this.secondOperandTypeSelect = this.secondOperandTypeSelect.bind(this);
    this.handleSecondOperandValueChange = this.handleSecondOperandValueChange.bind(this);
    this.operatorChange = this.operatorChange.bind(this);
    this.secondOperandOperandSelect = this.secondOperandOperandSelect.bind(this);
    this.secondOperandFieldIdSelect = this.secondOperandFieldIdSelect.bind(this);
    this.handleDeleteField = this.handleDeleteField.bind(this);
    this.isFieldTabOn = this.isFieldTabOn.bind(this);
    this.state = {
    	isOpen: false,
    	block: null,
    };
    this.secondOperandType = [
    {
    	label: 'field',
    	value: 'field',
    }, {
    	label: 'value',
    	value: 'value',
    }];
    this.operator = [
    {
    	label: 'equal_to',
    	value: 'equal_to'
    }, {
    	label: 'greater_than',
    	value: 'greater_than'
    }, {
      label: 'less_than',
      value: 'less_than'
    }, {
      label: 'contains',
      value: 'contains'
    }, {
      label: 'not_contains',
      value: 'not_contains'
    }];
    this.secondOperator = [
    {
    	label: 'plus',
    	value: 'plus'
    }, {
    	label: 'minus',
    	value: 'minus'
    }, {
      label: 'multiple',
      value: 'multiple'
    }, {
      label: 'divide',
      value: 'divide'
    }];
  }
  componentWillMount() {
		const { fields, block } = this.props;
		let availableFields = [];
    const restrictedField = ['blank', 'combobox', 'countdown', 'compoundbutton', 'recaptcha', 'comboselect', 'text-repeat', 'label', 'password', 'atag', 'bundle'];
		fields.map(function(field, index){
      if (restrictedField.indexOf(field.type) === -1) {
        let tempField = {};
        tempField.label = field.id;
        tempField.value = field.id;
        availableFields.push(tempField);
      }
		});
		this.setState({ availableFields, block });
  }
  componentWillReceiveProps(nextProps) {
    const { fields, block } = nextProps;
    let availableFields = [];
    const restrictedField = ['blank', 'combobox', 'countdown', 'compoundbutton', 'recaptcha', 'comboselect', 'text-repeat', 'label', 'password', 'atag', 'bundle'];
    fields.map(function(field, index){
      if (restrictedField.indexOf(field.type) === -1) {
        let tempField = {};
        tempField.label = field.id;
        tempField.value = field.id;
        availableFields.push(tempField);
      }
    });
  	this.setState({ availableFields, block });
  }
  componentDidUpdate() {

  }
  logChange(val) {
  	console.log(val);
  }
  fieldIdSelect(val) {
  	const { block } = this.props;
  	block.value.fieldID = val !== null ? val.value : val;
    if (block.value.secondOperand.type === 'field') {
      block.value.secondOperand.id = null;
    }
    block.value.secondOperand.type = null;
    block.value.secondOperand.value = null;
  	this.props.updateData(block.id, block);
  }
  secondOperandTypeSelect(val, e) {
    e.preventDefault();
  	const { block } = this.props;
    if (block.value.secondOperand.type === val)
      return;
  	block.value.secondOperand.type = val;
  	this.props.updateData(block.id, block);
  }
  secondOperandOperandSelect(val) {
  	const { block } = this.props;
  	block.value.secondOperand.operator = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  secondOperandFieldIdSelect(val) {
  	const { block } = this.props;
  	block.value.secondOperand.id = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  handleSecondOperandValueChange(data, value) {
  	const { block } = this.props;
  	block.value.secondOperand.value = value;
  	this.props.updateData(block.id, block);
  }
  handleCollapse(type, e) {
  	e.preventDefault();
  	this.setState({ isOpen: !this.state.isOpen });
  }
  operatorChange(val) {
  	const { block } = this.props;
  	block.value.operator = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  handleDeleteField(e) {
    e.preventDefault();
    const { block } = this.props;
    this.props.deleteData(block.id);
  }
  isFieldTabOn(props) {
    const { fields, block } = props;
    const isFieldtab = block.value.fieldID === null ? false : true;
    let optionsFields = [];
    let type = null;
    if (isFieldtab) {
      fields.map(function(field){
        if (field.id === block.value.fieldID) {
          type = field.type;
        }
      });
    }
    fields.map(function(field) {
      if (field.type === type && field.id !== block.value.fieldID) {
        let tempField = {};
        tempField.label = field.id;
        tempField.value = field.id;
        optionsFields.push(tempField);
      }
    });
    return {
      fieldTab: isFieldtab,
      optionsFields: type === null ? [] : optionsFields,
    }
  }
  render() {
    const { props } = this;
    const { fields } = props;
    const { fieldID } = props.block.value;
    let Component = null;
    let item = null;
    let preValue = {};
    let preValueField = {};
    let itemField = {};
    let ComponentField = null;
    if (fieldID !== null) {
      let fieldIndex = 0;
      props.fields.map(function(field, index){
        if (field.id === fieldID) {
          fieldIndex = index;
        }
      });
      if (props.block.value.secondOperand.value) {
        preValue[props.fields[fieldIndex].id] = props.block.value.secondOperand.value;
      }
        Component = fieldProps => <Async
          componentProps={fieldProps}
          load={props.fields[fieldIndex].type}
        />;
        item = props.fields[fieldIndex];
    }

    const { availableFields } = this.state;
    const { fieldTab, optionsFields } = this.isFieldTabOn(props);
    let secondOperandType = null;
    if (props.block.value.secondOperand.id) {
      let fieldIndex = 0;
      props.fields.map(function(field, index){
        if (field.id === props.block.value.secondOperand.id) {
          fieldIndex = index;
        }
      });
      if (props.block.value.secondOperand.value) {
        preValueField[props.fields[fieldIndex].id] = props.block.value.secondOperand.value;
      }
        ComponentField = fieldProps => <Async
          componentProps={fieldProps}
          load={props.fields[fieldIndex].type}
        />;
        itemField = props.fields[fieldIndex];
        secondOperandType = props.fields[fieldIndex].type;
    }
    const style = {
      'paddingBottom': '10px',
      'paddingTop': '10px',
    };
    const secondOperandStyle = {
      'borderStyle': 'solid',
    	'borderWidth': '2px 2px 2px 2px',
    	// 'width': '400px'
    };
    return (<div className={Styles.scwpConditionSingleArray}>
    	<button type="button" className={Styles.scwpConditionArrayFieldBtn} onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Field</button>
			<Collapse isOpened={this.state.isOpen} className={Styles.scwpCollapsibleWrapper}>
				<div className={Styles.scwpConditionArrayField}>
					<div className={Styles.scwpConditionArrayFieldTitle}>
						<span> FieldId</span>
					</div>
					<div>
						<Select
						    name="form-field-name"
						    options={availableFields}
						    multi={false}
						    value={props.block.value.fieldID}
						    onChange={this.fieldIdSelect}
						/>
					</div>
				</div>

				<div className={`${Styles.scwpConditionArrayField} ${Styles.scwpSecondOperandWrapper}`}>
					<div className={Styles.scwpConditionArrayFieldTitle}>
						<span>Second Operand</span>
					</div>
					<div className={Styles.scwpSecondOperandFieldOptWrapper}>
						{/*<div>
							 <span>Type</span>
						</div>
            */}
						<div className={Styles.scwpSecondOperandOptWrapper}>
							{/*<Select
							    name="form-field-name"
							    options={this.secondOperandType}
							    value={props.block.value.secondOperand.type}
							    multi={false}
							    onChange={this.secondOperandTypeSelect}
							/>*/}
              <span className={props.block.value.secondOperand.type === 'field' ? `${Styles.scwpSecondOperandOptBtn} ${Styles.scwpActiveOpt}`: Styles.scwpSecondOperandOptBtn} onClick={this.secondOperandTypeSelect.bind(this, 'field')}>Field</span>
              <span className={props.block.value.secondOperand.type !== 'field' ? `${Styles.scwpSecondOperandOptBtn} ${Styles.scwpActiveOpt}`: Styles.scwpSecondOperandOptBtn } onClick={this.secondOperandTypeSelect.bind(this, 'value')}>Value</span>
						</div>
            <div className={Styles.scwpSecondOperandFieldWrapper}>
  						{props.block.value.secondOperand.type === 'field' ? <div className={Styles.scwpConditionArrayField}>
  							<div className={Styles.scwpConditionArrayFieldTitle}>
  								<span>FieldID</span>
  							</div>
  							{optionsFields.length ? <div>
  								<Select
  								    name="form-field-name"
  								    options={optionsFields}
  								    multi={false}
  								    value={props.block.value.secondOperand.id}
  								    onChange={this.secondOperandFieldIdSelect}
  								/>
  							</div> : 'No Matched Field Found' }
  						</div> : null}
  						{ props.block.value.secondOperand.type !== 'field' ? <div className={Styles.scwpConditionArrayField}>
  							<div className={Styles.scwpConditionArrayFieldTitle}>
  							</div>
  							<div>
                  {Component !== null ? <Component allErrors={{}} showError={{}} allFieldValue={preValue} item={item} updateData={this.handleSecondOperandValueChange} /> : 'Select a Field Id first'}
  							</div>
  						</div> : null }
              { props.block.value.secondOperand.type === 'field' ? <div className={Styles.scwpConditionArrayField}>
                <div className={Styles.scwpConditionArrayFieldTitle}>
                  <span>Value</span>
                </div>
                { props.block.value.secondOperand.id ? <div>
                  { ComponentField !== null ? <ComponentField allErrors={{}} showError={{}} allFieldValue={preValueField} item={itemField} updateData={this.handleSecondOperandValueChange} /> : 'Select Second Operand' }
                </div> : 'No Second Operand Selected'}
              </div> : null }
  						{props.block.value.secondOperand.type === 'field' ? <div className={Styles.scwpConditionArrayField}>
  							<div className={Styles.scwpConditionArrayFieldTitle}>
  								{ secondOperandType === 'text' ? <span>Operator</span> : null }
  							</div>
  							{ props.block.value.secondOperand.id && secondOperandType === 'text' ? <div>
  								<Select
  								    name="form-field-name"
  								    options={this.secondOperator}
  								    multi={false}
  								    value={props.block.value.secondOperand.operator}
  								    onChange={this.secondOperandOperandSelect}
  								/>
  							</div>: null }
  						</div> : null }
            </div>
					</div>
				</div>

				<div className={Styles.scwpConditionArrayField}>
					<div className={Styles.scwpConditionArrayFieldTitle}>
						<span>Operator</span>
					</div>
					{ props.block.value.fieldID !== null ? <div>
						<Select
						    name="form-field-name"
						    options={this.operator}
						    multi={false}
						    value={props.block.value.operator}
						    onChange={this.operatorChange}
						/>
					</div> : 'No Field Id Selected' }
				</div>
        <div className={Styles.scwpConditionArrayField}>
          <button type="button" className={Styles.scwpDeleteArrayFieldBtn} onClick={this.handleDeleteField}>Delete</button>
        </div>
			</Collapse>
    </div>);
  }
}
