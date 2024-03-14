import React, { Component } from 'react';
import { connect } from 'react-redux';
import AddFieldpanel from './addfieldpanel';
import EditFieldPanel from './editfieldpanel';
import PreviewPanel from './previewpanel';
import LogicPanel from './logic/';
import GlobalSettings from './global-settings/global-settings';
import * as builderActions from '../../builder-modules/builderModule';
import { changeView } from 'form-builder/builder-modules/viewModule';
import * as logicActions from '../../builder-modules/logicModule';
import renderPanelHeader from 'form-builder/builder-components/panel-view-change-component';
import { setOutputIntoDiv } from '../../../utility/helper';
import * as settingsActions from '../../builder-modules/settingsModule';

class BuilderPanel extends Component {
  constructor(props) {
    super(props);
    this.icons = RE_ICON.icon_provider; // icon array
    let values = undefined;
    try {
      values = JSON.parse(this.props.preValue);
    } catch (e) {
      // try {
      //   values = JSON.parse(localStorage.getItem(`${this.props.root}-formBuilder`));
      // } catch (e1) {}
      try {
        //values = JSON.parse(localStorage.getItem(`${this.props.root}-formBuilder`));
      } catch (e1) {}
    }
    if (values) {
      if (values.formBuilder.fields) {
        this.props.setFields(values.formBuilder.fields);
      }
      if (values.formBuilder.allLogicBlock) {
        this.props.setLogicBlock(values.formBuilder.allLogicBlock);
      }
      if (values.formBuilder.globalSettings) {
        // console.log(values);
        this.props.setGlobalSettings(values.formBuilder.globalSettings, values.formBuilder.fields);
      }
    }
  }
  render() {
    const { viewMode, tempField, changeView, updateField, editField, changeLogic, fields, logicBlock,
           addSingleLogicBlock, updateBlockData, addEffectField, effectField, updateEffectField,
           addLogicBlock, allLogicBlock, selectLogicBlock, id, addFieldTOBlock, deleteSingleLogicBlockField,
           output, deleteBlockField, deleteConsequence, panelMode, postChangeType, globalSettings,
           updateFieldSettings, triggerTypeChange, allowPostChange, hideSettings } = this.props;
//     const { viewMode, tempField, changeView, updateField, editField, fields, output } = this.props;
    if (output) {
      setOutputIntoDiv(output, 'formBuilderPostTypeSelect',  globalSettings.settings.postType);
      setOutputIntoDiv(output, 'formBuilder', {fields, allLogicBlock, globalSettings}); // allLogicBlock
      allLogicBlock.map(function(logic){
        if(logic.id === id) {
          logic.logicBlock = logicBlock;
          logic.effectField = effectField;
        }
      });
    }
    // localStorage.setItem(`${this.props.root}-formBuilder`, JSON.stringify({formBuilder: {fields,allLogicBlock}}));
    const renderPanelBody = () => {
      if (panelMode === 'logic') {
        const logicPanelOptions = {
          fields,
          logicBlock,
          changeLogic,
          addSingleLogicBlock,
          updateBlockData,
          addEffectField,
          effectField,
          updateEffectField,
          addLogicBlock,
          allLogicBlock,
          selectLogicBlock,
          id,
          addFieldTOBlock,
          deleteSingleLogicBlockField,
          deleteBlockField,
          deleteConsequence,
          editField,
        }
        return <LogicPanel {...logicPanelOptions} />;
      }
      switch(viewMode) {
        case 'preview':
          const previewPanelOptions = {
            fields,
            icons: this.icons,
            conditionDecisions: allLogicBlock,
          };
          return <PreviewPanel {...previewPanelOptions}/>
        case 'add_field':
          const addFieldpanelOptions = {
            tempField,
            changeView,
            editField,
          }
          return <AddFieldpanel {...addFieldpanelOptions} />;
        case 'edit_field':
          const editFieldpanelOptions = {
            tempField,
            changeView,
            updateField,
            editField,
            globalSettings,
          }
          return <EditFieldPanel {...editFieldpanelOptions} icons={this.icons}/>;
        case 'globalSettings':
          return <GlobalSettings allowPostChange={allowPostChange} triggerTypeChange={triggerTypeChange} updateFieldSettings={updateFieldSettings} settings={globalSettings.settings} { ...globalSettings } updatePostTypeData={postChangeType} fields={fields} />
        default:
          return viewMode;
      }
    }
    let activeLogicBlock = null;
    allLogicBlock.map(function(logicBlock, index){
      if (logicBlock.id === id) {
        activeLogicBlock = 'Condition-' + (index + 1);
      }
    })
    const showLogic = this.props.showLogic !== false && fields.length > 0 ;
    return( <div className="scwpFormSettingsMainWrapper">
      {renderPanelHeader(viewMode, panelMode, changeView, showLogic, activeLogicBlock, hideSettings)}
      {renderPanelBody()}
    </div>);
  }
}
function mapStateToProps(state) {
  // let allLogicBlock = null;
  // try{
  //   allLogicBlock = JSON.parse(state.logic.allLogicBlock);
  // } catch(e) {
  //   allLogicBlock = state.logic.allLogicBlock;
  // }
  return {
    viewMode: state.view.viewMode,
    tempField: state.builder.tempField,
    logicBlock: state.logic.logicBlock,
    effectField: state.logic.effectField,
    fields: state.builder.fields,
    dataPanelPreload: state.builder.dataPanelPreload,
    allLogicBlock: state.logic.allLogicBlock,
    id: state.logic.id,
    globalSettings: state.globalSettings,
  };
}
export default connect(mapStateToProps, {
  ...builderActions,
  ...logicActions,
  ...settingsActions,
  changeView,
})(BuilderPanel);
