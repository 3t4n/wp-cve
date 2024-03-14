const configDefaults = {
  partnerID: '',
  locale_code: 'en-US',
  cmp: '',
  currency: 'USD',
};
export const gygData = window.gygData ? { ...configDefaults, ...window.gygData } : configDefaults;

const { cmp, currency: defaultCurrency, locale_code } = gygData; // eslint-disable-line camelcase
const currency = defaultCurrency === 'automatic' ? 'USD' : defaultCurrency;
export const activitiesDefaultAttributes = {
  iata: '',
  cmp,
  currency,
  locale_code,
  number_of_items: '3',
  q: '',
};

export const cityDefaultAttributes = {
  iata: 'SYD',
  locale_code,
  cmp,
};

export const selectors = {
  iframeContainer: '.gyg-iframe-container',
};

export const classNames = {
  searchForm: 'gyg-search',
  searchQueryInput: 'q',
  searchInput: 'gyg-search-input',
  partnerId: 'partner_id',
};

export const spacers = {
  standard: '1.5em',
};
