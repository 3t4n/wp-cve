// #region [Imports] ===================================================================================================

import { InputNumber } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Number = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;

  return (
    <InputNumber
      placeholder={fixtures?.placeholder}
      defaultValue={defaultValue}
      readOnly={!editable}
      min={fixtures?.min}
      max={fixtures?.max}
      step={fixtures?.step}
      onChange={onChange}
    />
  );
};

export default Number;

// #endregion [Component]
