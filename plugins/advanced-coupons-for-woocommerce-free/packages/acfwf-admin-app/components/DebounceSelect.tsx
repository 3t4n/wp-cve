// #region [Imports] ===================================================================================================

import { useState, useRef, useMemo } from 'react';
import { Select, Spin } from 'antd';
import { IFieldOption } from '../types/fields';
import debounce from 'lodash/debounce';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const DebounceSelect = ({ fetchOptions, optionMapper, debounceTimeout = 800, ...props }: any) => {
  const [fetching, setFetching] = useState(false);
  const [options, setOptions] = useState<IFieldOption[]>([]);
  const fetchRef = useRef(0);

  const debounceFetcher = useMemo(() => {
    const loadOptions = (value: string) => {
      fetchRef.current += 1;
      const fetchId = fetchRef.current;
      setOptions([]);
      setFetching(true);

      fetchOptions(value).then((newOptions: IFieldOption[]) => {
        if (fetchId !== fetchRef.current) {
          // for fetch callback order
          return;
        }

        setOptions(newOptions);
        setFetching(false);
      });
    };

    return debounce(loadOptions, debounceTimeout);
  }, [fetchOptions, debounceTimeout]);

  return (
    <Select
      labelInValue
      filterOption={false}
      onSearch={debounceFetcher}
      notFoundContent={fetching ? <Spin size="small" /> : null}
      {...props}
      options={options}
    />
  );
};

export default DebounceSelect;

// #endregion [Component]
