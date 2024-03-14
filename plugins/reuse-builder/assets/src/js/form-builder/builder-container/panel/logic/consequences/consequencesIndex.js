import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import Styles from './consequences.less';
const Async = __REUSEFORM_COMPONENT__['Async'];

export default class Consequences extends Component {
  constructor(props) {
    super(props);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.fieldIdSelect = this.fieldIdSelect.bind(this);
    this.effectSelect = this.effectSelect.bind(this);
    this.handleEffectValueChange = this.handleEffectValueChange.bind(this);
    this.handleDeleteField = this.handleDeleteField.bind(this);
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
  handleEffectValueChange(data, value) {
    const { block } = this.state;
    block.value = value;
    this.props.updateEffectField(block.id, block);
  }
  handleDeleteField(e) {
    e.preventDefault();
    const uid = this.props.id;
    const {block} = this.props;
    this.props.deleteConsequence(block.id, uid);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    let preValue = {};
    let item = {};
    let Component = null;
    if (props.block.action === 'set') {
      if (props.block.fieldID !== null) {
        let fieldIndex = 0;
        props.fields.map(function(field, index){
          if (field.id === props.block.fieldID) {
            fieldIndex = index;
          }
        });
        if (props.block.value) {
          preValue[props.fields[fieldIndex].id] = props.block.value;
        }
        Component = fieldProps => <Async
          componentProps={fieldProps}
          load={props.fields[fieldIndex].type}
        />;
        item = props.fields[fieldIndex];
      }
    }
    const style = {
      'paddingBottom': '10px',
      'paddingTop': '10px',
    };
    return (<div className={Styles.scwpConsequencesWrapper}>
    	<button className={Styles.scwpConsequencesFieldBtn} onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Consequnces</button>
			<Collapse isOpened={this.state.isOpen} className={Styles.scwpCollapsibleWrapper}>
        <div className={Styles.scwpConsequencesField}>
          <div className={Styles.scwpConsequencesFieldTitle}>
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

        <div className={Styles.scwpConsequencesField}>
          <div className={Styles.scwpConsequencesFieldTitle}>
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
        { props.block.action === 'set' ? <div className={Styles.scwpConsequencesField}>
          <div className={Styles.scwpConsequencesFieldTitle}>
          </div>
          <div>
              { Component !== null ? <Component allErrors={{}} showError={{}} allFieldValue={preValue} item={item} updateData={this.handleEffectValueChange} /> : 'Select FieldID' }
          </div>
        </div> : null}

        <div className={Styles.scwpConsequencesField}>
          <button className={Styles.scwpDeleteConsequencesBtn} onClick={this.handleDeleteField}>Delete</button>
        </div>
			</Collapse>
    </div>);
  }
}
