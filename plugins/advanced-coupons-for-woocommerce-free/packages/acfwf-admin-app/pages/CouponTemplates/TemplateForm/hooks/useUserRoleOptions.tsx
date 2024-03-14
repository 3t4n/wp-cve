import { useEffect, useState } from 'react';
import { IFieldOption } from '../../../../types/fields';
import axiosInstance, { axiosCancel, getCancelToken } from '../../../../helpers/axios';

declare var acfwAdminApp: any;

const useUserRoleOptions = () => {
  const [loading, setLoading] = useState(false);
  const [options, setOptions] = useState<IFieldOption[]>(acfwAdminApp.user_role_options ?? []);

  const fetchOptions = async () => {
    try {
      setLoading(true);
      const response = await axiosInstance.get('coupons/v1/templates/options', {
        params: {
          type: 'user_role',
        },
        cancelToken: getCancelToken('fetch_user_roles'),
      });

      if (response && response.data) {
        acfwAdminApp.user_role_options = response.data as IFieldOption[];
        setOptions(response.data as IFieldOption[]);
      }
      setLoading(false);
    } catch (e) {
      console.log(e);
    }
  };

  useEffect(() => {
    if (!acfwAdminApp.user_role_options) fetchOptions();

    return () => axiosCancel('fetch_user_roles');
  }, []);

  return { options, loading };
};

export default useUserRoleOptions;
