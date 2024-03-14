// #region [Imports] ===================================================================================================

import IStoreCreditEntry from '../components/StoreCreditsHistory/type';

// #endregion [Imports]

// #region [Functions] ==================================================================================================

/**
 * Get the prefix (+/-) for store credit entry.
 *
 * @param {IStoreCreditEntry} record
 * @returns
 */
export function getStoreCreditEntryPrefix(record: IStoreCreditEntry) {
  return 'increase' === record.type ? '+' : '-';
}

// #endregion [Functions]
