import React, { Component } from 'react';
const ReuseForm = __REUSEFORM__;
// import SelectBox from 'reuse-form/elements/re-selectbox/selectbox';
// import 'reuse-form/elements/less/common.less';
import { MANUAL_DATA, PRELOAD, PRELOAD_ITEM, POST_TYPE_SELECTION } from './../../../formConfig/dataconfig';
import { randomNumber } from 'utility/helper';
import clone from 'clone';
import Styles from './../../../builder-components/edit-panel-view-change-component.less';
const SelectBox = __REUSEFORM_COMPONENT__['selectbox'];
function setOption(option) {
  const options = {};
  let errorExists = false;
  if (option) {
    option.forEach(opt => {
      opt.data.forEach(singeData => {
        if (singeData.value === undefined || singeData.length === 0) {
          errorExists = true;
        }
      });
      try {
       if (opt.data[0].value.length > 0 && opt.data[1].value.length > 0)
       options[opt.data[0].value] = opt.data[1].value;
     } catch(e){}
    });
    if (errorExists) {
      return {};
    } else {
      return options;
    }
  }
  return undefined;
}
function createOption(options) {
  if(!options )
    return undefined;
  let option = [];
  Object.keys(options).forEach(key => {
    const singleData = { id: randomNumber() };
    let data = [];
    data.push({id: 'key', value: key});
    data.push({id: 'value', value: options[key]});
    singleData.data = data;
    option.push(singleData);
  });
  return { option };
}

export default class dataPanel extends Component {
  constructor(props) {
    super(props);
    this.state = this.getInitState(props);
  }
  componentWillReceiveProps(nextProps) {
    if (nextProps.field.id !== this.props.field.id) {
      this.setState(this.getInitState(nextProps));
    }
  }
  getInitState(props) {
    const { field, showManual } = props;
    return {
      currentState: showManual ? 'manual' : 'LoadData',
      type: props.type,
      preValue: createOption(field.options),
      options: field.options,
      preload: field.preload ? field.preload : '',
      preload_item: field.preload_item ? field.preload_item : '',
    };
  }
  preLoadComponent() {
    const { currentState, preload, preload_item } = this.state;
    const { getUpdatedFields, field, globalSettings, changeView } = this.props;
    let postType = '';
    try {
      postType = globalSettings.settings.postType;
    } catch(e){}
    if (!postType) {
      return (<div>
        <button type='button' className={`${Styles.reuseButton} ${Styles.reuseFluidButton}`} onClick={changeView.bind(this,'globalSettings')}>please select data post types from settings</button>
      </div>);
    }
    let selectboxes = [];

    if (postType.length > 0) {
      const preloadOptions = {
        item: PRELOAD,
        key: 'preloadOptions',
        updateData: (item, selectedData) => {
          this.setState({
            preload: selectedData,
            preload_item: '',
          });
          getUpdatedFields({
            preload: selectedData,
            preload_item: '',
          });
        },
        allFieldValue: { preload },
      }
      selectboxes.push(<div className={Styles.reuseDataSelect}><SelectBox {...preloadOptions} /></div>);

      const preloadItemField = PRELOAD_ITEM;
      preloadItemField.options = {};
      let opt = [];
      if (preload === 'meta_keys') {
        opt = globalSettings.response.meta_keys;
      } else {
        opt = globalSettings.response.taxonomies;
      }
      opt.forEach(option => {
        preloadItemField.options[option] =  option;
      });
      const preloadItemOptions = {
        item: preloadItemField,
        key: 'preloadItemOptions',
        updateData: (item, selectedData) => {
          this.setState({ preload_item: selectedData });
          getUpdatedFields({ preload_item: selectedData });
        },
        allFieldValue: { preload_item },
      }
      selectboxes.push(<SelectBox {...preloadItemOptions} />);
    }
    return selectboxes;
  }
  render() {
    const { currentState, preValue } = this.state;
    const { field, getUpdatedFields, showManual } = this.props;
    let reuseOption = {};
    if (currentState === 'manual') {
      const setOptions = (data) => {
        const options = setOption(data.option);
        if (JSON.stringify(options) !== JSON.stringify(this.state.options)) {
          getUpdatedFields({options});
        }
      }
      reuseOption = {
        reuseFormId: `builder-update-error`,
        fields: [ MANUAL_DATA ],
        getUpdatedFields: setOptions,
        errorMessages: {},
      };
      if (preValue) reuseOption.preValue = preValue;
    }
    const changeCurrentState = (currentState) => {
      this.setState({currentState});
    }
    let titleBar = '';
    if (showManual) {
      titleBar = <div className={Styles.scwpDataTabWrapper}><a
          className={currentState === 'manual' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
          onClick={changeCurrentState.bind(this, 'manual')}
        >
          Manual
        </a>
        <a
          className={currentState === 'loadData' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
          onClick={changeCurrentState.bind(this, 'loadData')}
        >
          Load data
        </a>
      </div>;
    }
    return(<div>
      {titleBar}
      {currentState === 'manual' ? <ReuseForm {...reuseOption} /> :
        this.preLoadComponent()
      }
    </div>);
  }
}
