// #region [Imports] ===================================================================================================

import { DatePicker } from 'antd';
import moment, { Moment } from 'moment';
import { IFieldComponentProps } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const DateTimeField = (props: IFieldComponentProps) => {
  const { defaultValue, editable, onChange } = props;

  const dateValue = moment(defaultValue ?? '');

  const handleDateChange = (date: Moment | null) => {
    onChange(date?.format('YYYY-MM-DD HH:mm:ss') ?? '');
  };

  return <DatePicker showTime={true} defaultValue={dateValue} disabled={!editable} onChange={handleDateChange} />;
};

export default DateTimeField;

// #endregion [Component]
