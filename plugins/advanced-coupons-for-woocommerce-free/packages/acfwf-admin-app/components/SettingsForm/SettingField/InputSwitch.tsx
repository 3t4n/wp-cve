// #region [Imports] ===================================================================================================

// Libraries
import React, { useState } from 'react';
import { bindActionCreators, Dispatch } from 'redux';
import { connect } from 'react-redux';
import { Input, InputNumber, Switch, Select, Radio, Space, message, TimePicker } from 'antd';
import moment from 'moment';

// Components
import PremiumModule from './PremiumModule';

// Types
import { IStore } from '../../../types/store';
import { ISectionField } from '../../../types/section';

// Actions
import { SettingActions } from '../../../store/actions/setting';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

const { updateSetting, setStoreSettingItem } = SettingActions;
const { action_notices } = acfwAdminApp;

// #endregion [Variables]

// #region [Interfaces] ================================================================================================

interface IActions {
  updateSetting: typeof updateSetting;
  setStoreSettingItem: typeof setStoreSettingItem;
}

interface IProps {
  field: ISectionField;
  setShowSpinner: any;
  validateInput: any;
  value: any;
  actions: IActions;
  order: number;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const InputSwitch = (props: IProps) => {
  const { field, setShowSpinner, validateInput, value: savedValue, actions, order } = props;
  const { id, title, type, placeholder, default: defaultValue, suffix, format, min, max } = field;
  const [saveTimeout, setSaveTimeout]: [any, any] = useState(null);
  const value = savedValue !== undefined && savedValue !== false ? savedValue : defaultValue;

  const handleValueChange = (inputValue: unknown, needTimeout: boolean = false) => {
    const updateValue = () => {
      // validate value
      if (!validateInput(inputValue)) return;

      // set state early to prevent rerenders.
      actions.setStoreSettingItem({ data: { id: id, value: inputValue } });

      // update setting value via api
      actions.updateSetting({
        data: { id: id, value: inputValue, type: type },
        processingCB: () => setShowSpinner(true),
        successCB: () => {
          message.success(
            <>
              <strong>{field.title}</strong> {action_notices.success}
            </>
          );
          setShowSpinner(false);
        },
        failCB: () => {
          message.error(
            <>
              <strong>{field.title}</strong> {action_notices.fail}
            </>
          );
          setShowSpinner(false);
        },
      });
    };

    // we add timeout for fields that requires users to update value by typing.
    if (needTimeout) {
      // clear timeout when user is still editing
      if (saveTimeout) {
        clearTimeout(saveTimeout);
        setSaveTimeout(null);
      }

      // set 1-second delay before updating value.
      setSaveTimeout(setTimeout(updateValue, 1000));
    } else updateValue();
  };

  if ('checkbox' === type || 'module' === type) {
    return (
      <Switch
        key={id}
        checked={value === 'yes'}
        defaultChecked={value === 'yes'}
        onChange={(inputValue) => handleValueChange(inputValue ? 'yes' : '')}
      />
    );
  }

  if ('premiummodule' === type) {
    return <PremiumModule field={field} />;
  }

  if ('textarea' === type) {
    return (
      <Input.TextArea
        key={id}
        rows={3}
        placeholder={placeholder}
        defaultValue={value}
        onChange={(event) => handleValueChange(event.target.value, true)}
      />
    );
  }

  if ('radio' === type) {
    const { options } = field;
    return (
      <Radio.Group onChange={(event) => handleValueChange(event.target.value)} value={value}>
        <Space direction="vertical">
          {options?.length &&
            options.map(({ key, label }) => (
              <Radio key={key.toString()} value={key.toString()}>
                {label}
              </Radio>
            ))}
        </Space>
      </Radio.Group>
    );
  }

  if ('select' === type) {
    let { options } = field;
    let select_options: any = options ? options : [];
    select_options = select_options.map((option: any) => {
      return option.label.label ? option.label : { value: option.key, label: option.label };
    });
    return (
      <Select
        key={id}
        defaultValue={value.toString()}
        style={{ width: `50%` }}
        placeholder={placeholder}
        options={select_options}
        onSelect={(inputvalue: any) => handleValueChange(inputvalue)}
      />
    );
  }

  if ('timepicker' === type) {
    let defaultFormat = format ? format : 'HH:mm:ss'; // Use format if exists or use default format.
    let defaultValue = value ? value : field.default; // Use value if exists or use default value.
    defaultValue = defaultValue ? defaultValue : '00:00:00'; // Avoid undefined value.
    defaultValue = moment(defaultValue, defaultFormat); // Convert to moment object.
    return (
      <TimePicker
        key={id}
        defaultValue={defaultValue}
        format={defaultFormat}
        onChange={(inputvalue: any) => handleValueChange(inputvalue.format('HH:mm:ss'))}
      />
    );
  }

  if (['text', 'url', 'permalink', 'customurl'].indexOf(type) > -1) {
    let is_similar_to_text = ['permalink', 'customurl'];
    return (
      <Input
        key={id}
        type={is_similar_to_text.indexOf(type) > -1 ? 'text' : type}
        name={id}
        placeholder={placeholder}
        defaultValue={value}
        onChange={(event) => handleValueChange(event.target.value, true)}
        suffix={suffix}
      />
    );
  }

  if ('number' === type) {
    console.log(min, max);
    return (
      <InputNumber
        key={id}
        name={id}
        placeholder={placeholder}
        defaultValue={value}
        onChange={(value) => handleValueChange(value, true)}
        min={min ?? undefined}
        max={max ?? undefined}
      />
    );
  }

  if ('price' === type)
    return (
      <Input
        type="text"
        className="wc_input_price"
        name={id}
        placeholder={placeholder}
        defaultValue={value}
        onChange={(event: any) => handleValueChange(event.target.value, true)}
      />
    );

  if ('acfwflicense' === type) {
    const { licenseContent } = field;
    return (
      <div className="acfw-license-field">
        {licenseContent?.map((text) => (
          <p dangerouslySetInnerHTML={{ __html: text }} />
        ))}
      </div>
    );
  }

  // display subtitle field
  if ('subtitle' === type) {
    return (
      <div className={`form-heading ${order > 0 ? 'spacer' : ''}`}>
        <h3>{title}</h3>
      </div>
    );
  }

  return null;
};

const mapStateToProps = (store: IStore, props: any) => {
  const { id } = props.field;
  const index = store.settingValues.findIndex((i: any) => i.id === id);
  const value = index > -1 ? store.settingValues[index].value : '';

  return { value: value };
};

const mapDispatchToProps = (dispatch: Dispatch) => ({
  actions: bindActionCreators({ updateSetting, setStoreSettingItem }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(InputSwitch);

// #endregion [Component]
