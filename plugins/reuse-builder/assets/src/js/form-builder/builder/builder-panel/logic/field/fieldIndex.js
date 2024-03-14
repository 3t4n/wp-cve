import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';

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
    }];
    this.secondOperator = [
    {
    	label: 'plus',
    	value: 'plus'
    }, {
    	label: 'minus',
    	value: 'minus'
    }];
  }
  componentWillMount() {
		const { fields, block } = this.props;
		let availableFields = [];
		fields.map(function(field, index){
			let tempField = {};
			tempField.label = field.id;
			tempField.value = field.id;
			availableFields.push(tempField);
		});
		this.setState({ availableFields, block: block });
  }
  componentWillReceiveProps(nextProps) {
  	this.setState({ block: nextProps.block });
  }
  componentDidUpdate() {

  }
  logChange(val) {
  	console.log(val);
  }
  fieldIdSelect(val) {
  	const { block } = this.state;
  	block.value.fieldID = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  secondOperandTypeSelect(val) {
  	const { block } = this.state;
  	block.value.secondOperand.type = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  secondOperandOperandSelect(val) {
  	const { block } = this.state;
  	block.value.secondOperand.operator = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  secondOperandFieldIdSelect(val) {
  	const { block } = this.state;
  	block.value.secondOperand.id = val !== null ? val.value : val;
  	this.props.updateData(block.id, block);
  }
  handleSecondOperandValueChange(e) {
  	const { block } = this.state;
  	block.value.secondOperand.value = e.target.value;
  	this.props.updateData(block.id, block);
  }
  handleCollapse(type, e) {
  	e.preventDefault();
  	this.setState({ isOpen: !this.state.isOpen });
  }
  operatorChange(val) {
  	const { block } = this.state;
  	block.value.operator = val !== null ? val.value : val;
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
    return (<div style={style}>
    	<button onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Field</button>
			<Collapse isOpened={this.state.isOpen}>
				<div>
					<div>
						<span> FieldId</span>
					</div>
					<div>
						<Select
						    name="form-field-name"
						    options={availableFields}
						    multi={false}
						    value={props.block.fieldID}
						    onChange={this.fieldIdSelect}
						/>
					</div>
				</div>
				<div>
					<div>
						<span>Second Operand</span>
					</div>
					<div style={secondOperandStyle}>
						<div>
							<div>
								<span>Type</span>
							</div>
							<div>
								<Select
								    name="form-field-name"
								    options={this.secondOperandType}
								    value={props.block.value.secondOperand.type}
								    multi={false}
								    onChange={this.secondOperandTypeSelect}
								/>
							</div>
						</div>
						{props.block.value.secondOperand.type === 'field' ? <div>
							<div>
								<span>FieldID</span>
							</div>
							<div>
								<Select
								    name="form-field-name"
								    options={availableFields}
								    multi={false}
								    value={props.block.value.secondOperand.id}
								    onChange={this.secondOperandFieldIdSelect}
								/>
							</div>
						</div> : null}
						{ props.block.value.secondOperand.type !== null ? <div>
							<div>
								<span>Value</span>
							</div>
							<div>
								<input value={props.block.value.secondOperand.value} onChange={this.handleSecondOperandValueChange} />
							</div>
						</div> : null }
						{props.block.value.secondOperand.type === 'field' ? <div>
							<div>
								<span>Operator</span>
							</div>
							<div>
								<Select
								    name="form-field-name"
								    options={this.secondOperator}
								    multi={false}
								    value={props.block.value.secondOperand.operator}
								    onChange={this.secondOperandOperandSelect}
								/>
							</div>
						</div> : null }
					</div>
				</div>
				<div>
					<div>
						<span>Operator</span>
					</div>
					<div>
						<Select
						    name="form-field-name"
						    options={this.operator}
						    multi={false}
						    value={props.block.value.operator}
						    onChange={this.operatorChange}
						/>
					</div>
				</div>
			</Collapse>
    </div>);
  }
}
