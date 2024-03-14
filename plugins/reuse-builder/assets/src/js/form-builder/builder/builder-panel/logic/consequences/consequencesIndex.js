import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';

export default class Consequences extends Component {
  constructor(props) {
    super(props);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.fieldIdSelect = this.fieldIdSelect.bind(this);
    this.effectSelect = this.effectSelect.bind(this);
    this.handleEffectValueChange = this.handleEffectValueChange.bind(this);
    this.state = {
    	isOpen: false,
    	block: null,
    };
    this.effect = [
    {
    	label: 'set',
    	value: 'set',
    }, {
    	label: 'show',
    	value: 'show',
    }, {
      label: 'hide',
      value: 'hide',
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
  handleCollapse(type, e) {
  	e.preventDefault();
  	this.setState({ isOpen: !this.state.isOpen });
  }
  fieldIdSelect(val) {
    const { block } = this.state;
    block.fieldID = val !== null ? val.value : val;
    this.props.updateEffectField(block.id, block);
  }
  effectSelect(val) {
    const { block } = this.state;
    block.action = val !== null ? val.value: val;
    this.props.updateEffectField(block.id, block);
  }
  handleEffectValueChange(e) {
    const { block } = this.state;
    block.value = e.target.value;
    this.props.updateData(block.id, block);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    const style = {
      'paddingBottom': '10px',
      'paddingTop': '10px',
    };
    return (<div style={style}>
    	<button onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Consequnces</button>
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
            <span>Action</span>
          </div>
          <div>
            <Select
                name="form-field-name"
                options={this.effect}
                multi={false}
                value={props.block.action}
                onChange={this.effectSelect}
            />
          </div>
        </div>
        { props.block.action === 'set' ? <div>
          <div>
            <span>Value</span>
          </div>
          <div>
            <input value={props.block.value} onChange={this.handleEffectValueChange} />
          </div>
        </div> : null}
			</Collapse>
    </div>);
  }
}
