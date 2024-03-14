import React, { Component } from 'react';
import escape from 'lodash.escape';
import uniqueID from 'lodash.uniqueid';
import { classNames, spacers, gygData } from '../config';

export const addSearchStyle = () => {
  const id = 'gyg-search-style';
  if (document.getElementById(id)) {
    return;
  }
  document.head.insertAdjacentHTML(
    'beforeend',
    `<style id="${id}">@font-face {
        font-family: "GT Eesti";
        font-style: normal;
        font-display:fallback;
        font-weight: 400;
        src: url("https://cdn.getyourguide.com/static/49829f4a1f87/customer/desktop/cached/fonts/GT-Eesti/GT-Eesti-Pro-Display-Regular.woff2");
      }</style>`,
  );
};

export default class Search extends Component {
  constructor(props) {
    super(props);
    this.formRef = React.createRef();
    this.id = `gyg-search-input-${uniqueID()}`;
  }

  state = {
    q: '',
  };

  componentDidMount() {
    addSearchStyle();
  }

  handleSubmit = (e) => {
    e.preventDefault();
    this.setState({ q: escape(e.target.value) });
    this.formRef.current.submit();
  };

  render() {
    const { formRef, handleSubmit, id } = this;
    const { partnerID: partner_id } = gygData; // eslint-disable-line camelcase
    const { q } = this.state;
    const didSubmit = q !== '';
    return (
      <form
        ref={formRef}
        action="https://www.getyourguide.com/s"
        className={classNames.searchForm}
        onSubmit={handleSubmit}
        style={{
          backgroundColor: '#1a2b49',
          borderRadius: '2px',
          fontFamily: '"GT Eesti",Arial,sans-serif',
          marginBottom: spacers.standard,
          padding: '24px',
        }}
      >
        <input
          className={classNames.searchQueryInput}
          type="hidden"
          name={classNames.searchQueryInput}
          value={q}
        />
        <input
          className={classNames.partnerId}
          type="hidden"
          name={classNames.partnerId}
          value={partner_id} // eslint-disable-line camelcase
        />
        <label htmlFor={id} style={{ flex: '1 0 auto' }}>
          <h2
            style={{
              color: '#fff',
              fontSize: '24px',
              margin: '0 0 12px',
              padding: 0,
            }}
          >
            Search GetYourGuide
          </h2>
        </label>
        <span style={{ display: 'flex', maxHeight: '48px' }}>
          <input
            id={id}
            placeholder="Where are you going?"
            style={{
              border: 0,
              borderRadius: '2px',
              fontSize: '16px',
              marginRight: '16px',
              padding: '8px',
              width: '100%',
            }}
            className={classNames.searchInput}
          />
          <button
            style={{
              appearance: 'none',
              backgroundColor: didSubmit ? '#0079e1' : '#1593ff',
              color: '#fff',
              border: 0,
              borderRadius: '5rem',
              fontSize: '16px',
              padding: 0,
              textTransform: 'none',
            }}
            type="submit"
          >
            <span style={{ display: 'flex', alignItems: 'center', padding: '8px' }}>
              {!didSubmit && (
                <svg
                  style={{
                    fill: '#fff',
                    marginRight: '8px',
                    maxHeight: '16px',
                    maxWidth: '16px',
                  }}
                  width="1792"
                  height="1792"
                  viewBox="0 0 1792 1792"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path d="M1216 832q0-185-131.5-316.5t-316.5-131.5-316.5 131.5-131.5 316.5 131.5 316.5 316.5 131.5 316.5-131.5 131.5-316.5zm512 832q0 52-38 90t-90 38q-54 0-90-38l-343-342q-179 124-399 124-143 0-273.5-55.5t-225-150-150-225-55.5-273.5 55.5-273.5 150-225 225-150 273.5-55.5 273.5 55.5 225 150 150 225 55.5 273.5q0 220-124 399l343 343q37 37 37 90z" />
                </svg>
              )}
              {didSubmit ? 'Loading...' : 'Search'}
            </span>
          </button>
        </span>
      </form>
    );
  }
}
