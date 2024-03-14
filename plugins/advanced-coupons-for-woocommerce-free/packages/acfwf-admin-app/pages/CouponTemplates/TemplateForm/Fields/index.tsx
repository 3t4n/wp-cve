import React from 'react';

// Components;
import CouponCode from './CouponCode';
import Number from './Number';
import Text from './Text';
import Price from './Price';
import Select from './Select';
import TextArea from './TextArea';
import Checkbox from './Checkbox';
import UserRoles from './UserRoles';
import SelectProducts from './SelectProducts';
import SelectProductCategories from './SelectProductCategories';
import DateTimeField from './DateTimeField';
import SelectCoupons from './SelectCoupons';
import SelectCustomers from './SelectCustomers';

const componentMap: Record<string, any> = {
  coupon_code: CouponCode,
  number: Number,
  text: Text,
  price: Price,
  select: Select,
  textarea: TextArea,
  checkbox: Checkbox,
  user_roles: UserRoles,
  products: SelectProducts,
  product_categories: SelectProductCategories,
  date: DateTimeField,
  coupons: SelectCoupons,
  customers: SelectCustomers,
};

export default function (type: string): (props: any) => JSX.Element {
  if (componentMap[type]) {
    return componentMap[type];
  }

  return () => <div>Field type not found</div>;
}
