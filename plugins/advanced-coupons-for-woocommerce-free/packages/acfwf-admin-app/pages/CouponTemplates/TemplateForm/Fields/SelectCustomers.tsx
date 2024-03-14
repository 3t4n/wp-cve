// #region [Imports] ===================================================================================================

import { useState } from 'react';
import { IFieldComponentProps } from '../../../../types/couponTemplates';
import { IFieldOption } from '../../../../types/fields';
import DebounceSelect from '../../../../components/DebounceSelect';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var jQuery: any;
declare var ajaxurl: string;
declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const SelectCoupons = (props: IFieldComponentProps) => {
  const { fixtures, onChange } = props;
  const [selected, setSelected] = useState<IFieldOption[]>([]);

  const searchProducts = async (value: string) => {
    const response = await jQuery.ajax({
      url: ajaxurl,
      type: 'GET',
      data: {
        action: 'woocommerce_json_search_customers',
        term: value,
        security: acfwAdminApp.nonces.search_customers,
        exclude: selected.map((item) => item.value),
      },
    });

    const customers = Object.keys(response).map((key) => ({
      label: response[key].replace('&ndash;', '–'),
      value: key,
    }));

    return customers;
  };

  const handleOnChange = (value: IFieldOption[]) => {
    setSelected(value);
    onChange(value);
  };

  return (
    <DebounceSelect
      mode="multiple"
      placeholder={fixtures?.placeholder}
      fetchOptions={searchProducts}
      onChange={handleOnChange}
      fixtures={fixtures}
      style={{ width: '100%' }}
      value={selected}
    />
  );
};

export default SelectCoupons;

// #endregion [Component]
