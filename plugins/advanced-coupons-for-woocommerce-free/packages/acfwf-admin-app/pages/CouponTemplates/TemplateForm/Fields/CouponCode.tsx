// #region [Imports] ===================================================================================================

// Libraries
import { Input, Space, Button } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';
import { useState } from 'react';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const CouponCode = (props: IFieldComponentProps) => {
  const { defaultValue, onChange } = props;
  const [couponCode, setCouponCode] = useState(defaultValue ?? '');

  const handleInputChange = (e: any) => {
    setCouponCode(e.target.value);
    onChange(e.target.value);
  };

  const handleGenerateCouponCode = () => {
    const chars = 'ABCDEFGHJKMNPQRSTUVWXYZ123456789';
    const length = 10;

    let couponCode = '';
    for (let i = 0; i < length; i++) {
      couponCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    setCouponCode(couponCode);
    onChange(couponCode);
  };

  return (
    <Space direction="horizontal" size="middle">
      <Space.Compact>
        <Input allowClear value={couponCode} onChange={handleInputChange} />
      </Space.Compact>
      <Space.Compact>
        <Button onClick={() => handleGenerateCouponCode()}>Generate</Button>
      </Space.Compact>
    </Space>
  );
};

export default CouponCode;

// #endregion [Component]
