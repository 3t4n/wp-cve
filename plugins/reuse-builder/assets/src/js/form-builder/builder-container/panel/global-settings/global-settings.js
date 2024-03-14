import React, { Component } from 'react';
import Collapse from 'react-collapse';
import SingleSettingsField from './singleFieldSettings';
import PostTypeSelect from './postTypeSelect';
import TriggerType from './triggerType';
import AllowPostType from './allowPost';
import { searchObjectIndex } from '../../../builder-modules/helperModule';
import clone from 'clone';
import Styles from './global-settings.less';
const ReuseForm = __REUSEFORM__;
const ReLoader = __REUSEFORM_COMPONENT__['ReLoader'];

export default class GlobalSettingsPanel extends Component {
  constructor(props) {
    super(props);
    this.updateData = this.updateData.bind(this);
    this.handleChangePostType = this.handleChangePostType.bind(this);
    this.handleTakeAction = this.handleTakeAction.bind(this);
    this.state = {
      changeAble: this.props.settings['postType']  === null ? true : false,
      postType: '',
      loader: false
    }
  }
  componentWillReceiveProps() {
    this.setState({ changeAble: false, loader: false });
  }
  updateData(type, data) {
    if (type === 'postType') {
      this.setState({ loader: true });
      this.props.updatePostTypeData(data);
    } else if (type === 'triggerType') {
      this.props.triggerTypeChange(data);
    } else if (type === 'allowPost') {
      this.props.allowPostChange(data);
    } else {
      this.props.updateFieldSettings(data);
    }
  }
  handleChangePostType(e) {
    e.preventDefault();
    this.setState({ changeAble: true });
  }
  handleTakeAction(settings, e) {
    e.preventDefault();
    let resultSettings = clone(settings);
    const { fieldSettings } = settings;
    let postDestination = [];
    fieldSettings.map(function(singleField, index){
      if (singleField.saveKey === 'custom_type') {
        resultSettings.fieldSettings[index].saveKey = singleField.value;
      }
      postDestination.push(singleField.postDestination);
    });
    resultSettings.postDestination = clone(postDestination);
  }
  render() {
    const { fields, settings } = this.props;
    const { fieldSettings } = settings;
    const singleRender = (singleField, index) => {
      const isPresent = searchObjectIndex( fieldSettings, 'fieldKey', singleField.id );
      let settingsData = {};
      if (isPresent !== -1) {
        settingsData = clone(fieldSettings[isPresent]);
      }
      return <SingleSettingsField selectedField={this.state.selectedField} settingsData={settingsData} {...this.props} updateData={this.updateData} key={index} item={singleField} />;
    }
    return (<div className="scwpFormSettingsWrapper">
      <div className="scwpSettingsPannelWrapper">
        { this.state.changeAble ? <PostTypeSelect updateData={this.updateData} preValue={settings.postType} />: <div className={Styles.scwpPostTypeSelection}>
          <div className={Styles.scwpPostTypeLabel}>
            <label>{settings.postType}</label>
            <button type="button" onClick={this.handleChangePostType}>Change</button>
          </div>
          <TriggerType postType={settings.postType} preValue={settings.trigger} updateData={this.updateData}/>
          <AllowPostType preValue={settings.allowPost} updateData={this.updateData}/>
         </div>
          }
        <div className={Styles.scwpSettingsFieldWrapper}>{!this.state.changeAble ? fields.map(singleRender) : null}</div>
        {this.state.loader ? ReLoader() : null}
      </div>
    </div>);
  }
}
