// #region [Imports] ===================================================================================================

import { Input } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Text = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;

  return (
    <div>
      <Input
        placeholder={fixtures?.placeholder}
        defaultValue={defaultValue}
        readOnly={!editable}
        onChange={(e) => onChange(e.target.value)}
      />
    </div>
  );
};

export default Text;

// #endregion [Component]
