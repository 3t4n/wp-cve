// #region [Imports] ===================================================================================================

import { Select } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const SelectField = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;
  const options = Object.keys(fixtures?.options ?? {}).map((key) => ({
    value: key,
    label: fixtures?.options ? fixtures?.options[key] : '',
  }));

  return (
    <div>
      <Select
        placeholder={fixtures?.placeholder}
        disabled={!editable}
        defaultValue={defaultValue}
        options={options}
        onChange={onChange}
      />
    </div>
  );
};

export default SelectField;

// #endregion [Component]
