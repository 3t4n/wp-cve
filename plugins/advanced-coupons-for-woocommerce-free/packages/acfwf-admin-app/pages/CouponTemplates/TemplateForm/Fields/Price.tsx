// #region [Imports] ===================================================================================================

import { InputNumber } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';
import { parsePrice } from '../../../../helpers/currency';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Price = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;
  const { currency } = acfwAdminApp.store_credits_page;

  return (
    <div>
      <InputNumber
        placeholder={fixtures?.placeholder}
        defaultValue={defaultValue}
        readOnly={!editable}
        decimalSeparator={currency.decimal_separator}
        parser={(value) => parsePrice(value ?? '').toString()}
        onChange={onChange}
      />
    </div>
  );
};

export default Price;

// #endregion [Component]
