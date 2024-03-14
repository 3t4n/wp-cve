// #region [Imports] ===================================================================================================

// Libraries
import { useState, useEffect } from 'react';

// Helpers
import axiosInstance from '../../helpers/axios';

// Types
import type IStoreCreditEntry from './type';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwStoreCredits: any;

// #endregion [Variables]

// #region [Custom Hook] ===============================================================================================

function useStoreCreditEntries() {
  const [entries, setEntries] = useState<IStoreCreditEntry[]>([]);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const [total, setTotal] = useState(0);

  useEffect(() => {
    setLoading(true);

    const fetchEntries = async () => {
      const response = await axiosInstance.get('store-credits/v1/entries/current-user', {
        params: {
          page: currentPage,
          currency: acfwStoreCredits.currency,
        },
      });

      setEntries(response.data);
      setLoading(false);
      setTotal(response.headers['x-total']);
    };
    fetchEntries();
  }, [currentPage]);

  return { entries, loading, currentPage, total, setCurrentPage };
}

export default useStoreCreditEntries;

// #endregion [Custom Hook]
