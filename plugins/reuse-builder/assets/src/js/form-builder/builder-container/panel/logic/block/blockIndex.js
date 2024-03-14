import React, { Component } from 'react';
import Collapse from 'react-collapse';
import Select from 'react-select-me';
import Field from '../field/fieldIndex';
import Relation from '../relation/relationIndex';
import clone from 'clone';
import Styles from './blockindex.less';

export default class Block extends Component {
  constructor(props) {
    super(props);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.updateBlockData = this.updateBlockData.bind(this);
    this.handleAddBlockField = this.handleAddBlockField.bind(this);
    this.handleDeleteBlock = this.handleDeleteBlock.bind(this);
    this.deleteBlockField = this.deleteBlockField.bind(this);
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
    block.value.value.map(function(singleBlock) {
      if (singleBlock.id === id) {
        tempValue.push(data);
      } else {
        tempValue.push(singleBlock);
      }
    });
    block.value.value = clone(tempValue);
    this.props.updateData(block.id, block);
  }
  handleAddBlockField(e) {
    const { block } = this.props;
    e.preventDefault();
    this.props.addFieldTOBlock(block.id);
  }
  handleDeleteBlock(e) {
    e.preventDefault();
    const { block } = this.props;
    this.props.deleteData(block.id);
  }
  deleteBlockField(id) {
    const uid = this.props.id;
    const { block } = this.props;
    this.props.deleteBlockField(id, block.id, uid);
  }
  render() {
    const { props } = this;
    const { availableFields } = this.state;
    const style = {
      'paddingBottom': '10px',
      'paddingTop': '10px',
    };
    const renderBlock = (block, index) => {
      switch(block.key) {
        case 'field':
          return <Field updateData={this.updateBlockData} deleteData={this.deleteBlockField} block={block} fields={props.fields} key={index}/>;
        case 'relation':
          return <Relation updateData={this.updateBlockData} block={block} key={index}/>;
        default:
          return ;
      }
      // return <Field />;
    }
    return (<div className={Styles.scwpConditionSingleBlock}>
    	<button type="button" className={Styles.scwpConditionSingleBlockBtn} onClick={this.handleCollapse.bind(this, this.state.isOpen)}>Block</button>
			<Collapse isOpened={this.state.isOpen} className={Styles.scwpCollapsibleWrapper}>
				<div>
          {props.block.value.value.length ? props.block.value.value.map(renderBlock) : null}
          {/*<SingleField updateData={this.updateBlockData} fields={props.fields} block={props.block.value[0]}/>
          <Relation updateData={this.updateBlockData} block={props.block.value[1]} />
          <SingleField updateData={this.updateBlockData} fields={props.fields} block={props.block.value[2]} />*/}

          <div className={Styles.scwpFieldBlockBtnWrapper}>
            <button type="button" className={`${Styles.scwpFieldBlockBtn} ${Styles.scwpFieldBtn}`} onClick={this.handleAddBlockField}>Add Field</button>
            <button type="button" className={`${Styles.scwpFieldBlockBtn} ${Styles.scwpDeleteBtn}`} onClick={this.handleDeleteBlock}>Delete</button>
          </div>
        </div>
			</Collapse>
    </div>);
  }
}
