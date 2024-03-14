import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';

export default class Relation extends Component {
  constructor(props) {
    super(props);
    this.relationSelect = this.relationSelect.bind(this);
    this.handleCollapse = this.handleCollapse.bind(this);
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
    	<button onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Relation</button>
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
    </div>);
  }
}
