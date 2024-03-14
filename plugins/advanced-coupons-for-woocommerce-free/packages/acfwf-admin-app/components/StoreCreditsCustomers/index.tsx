// #region [Imports] ===================================================================================================

// Libraries
import { useState, useEffect, useContext } from 'react';
import { Card, Table, Pagination, Button, Modal, Input } from 'antd';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { isNull } from 'lodash';

// Contexts
import { StoreCreditsCustomersContext } from '../../contexts/StoreCreditsCustomersQuery';

// Actions
import { StoreCreditsCustomersActions } from '../../store/actions/storeCreditsCustomers';

// Components
import StoreCreditsSingleCustomer from '../StoreCreditsSingleCustomer';
import AdjustCustomerBalance from './AdjustCustomerBalance';
import RemindButton from '../StoreCreditsSingleCustomer/Button/Remind';

// Types
import { IStore } from '../../types/store';
import { IStoreCreditCustomer } from '../../types/storeCredits';

// Helpers
import { axiosCancel } from '../../helpers/axios';

// SCSS
import './index.scss';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;
declare var acfwp_store_credit_reminder: any;

const { readStoreCreditsCustomers, adjustCustomerStoreCredits } = StoreCreditsCustomersActions;

// #endregion [Variables]

// #region [Interfaces] ================================================================================================

interface IActions {
  readCustomers: typeof readStoreCreditsCustomers;
  adjustCustomerStoreCredits: typeof adjustCustomerStoreCredits;
}

interface IProps {
  customers: IStoreCreditCustomer[];
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const StoreCreditsCustomers = (props: IProps) => {
  const { customers, actions } = props;
  const {
    store_credits_page: { labels },
  } = acfwAdminApp;
  const { params, dispatchParams } = useContext(StoreCreditsCustomersContext);
  const [loading, setLoading] = useState(false);
  const [total, setTotal] = useState(0);
  const [search, setSearch] = useState(params.search);
  const [searchTimeout, setSearchTimeout]: [any, any] = useState(null);
  const [selectedCustomer, setSelectedCustomer]: [IStoreCreditCustomer | null, any] = useState(null);
  const [adjustCustomer, setAdjustCustomer]: [IStoreCreditCustomer | null, any] = useState(null);

  const columns = [
    {
      title: labels.customer_name,
      dataIndex: 'first_name',
      key: 'first_name',
      sorter: true,
      render: (text: string, record: IStoreCreditCustomer) => `${record.first_name} ${record.last_name}`,
    },
    {
      title: labels.email,
      dataIndex: 'email',
      key: 'email',
      sorter: true,
    },
    {
      title: labels.balance,
      dataIndex: 'balance',
      key: 'balance',
      sorter: true,
    },
    {
      title: '',
      dataIndex: 'id',
      key: 'id',
      render: (id: number, record: IStoreCreditCustomer) => {
        return (
          <>
            <Button onClick={() => setSelectedCustomer(record)}>{labels.view_stats}</Button>
            <Button onClick={() => setAdjustCustomer(record)}>{labels.adjust}</Button>
            <RemindButton customer={record} />
          </>
        );
      },
    },
  ];

  /**
   * Handle search input change.
   */
  const handleSearch = (value: string) => {
    setSearch(value);
    if (searchTimeout) {
      axiosCancel('customer_search');
      clearTimeout(searchTimeout);
    }

    setSearchTimeout(setTimeout(() => dispatchParams({ type: 'SET_SEARCH', value }), 1000));
  };

  /**
   * Handle pagination click.
   */
  const handlePaginationClick = (page: number) => {
    dispatchParams({ type: 'SET_PAGE', value: page });
  };

  const handleTableChange = (pagination: any, filters: any, sorter: any) => {
    if (!sorter.columnKey) {
      return;
    }

    switch (sorter.columnKey) {
      case 'first_name':
        dispatchParams({ type: 'SET_META_KEY', value: 'billing_first_name' });
        dispatchParams({ type: 'SET_SORT_BY', value: 'meta_value' });
        break;
      case 'balance':
        dispatchParams({ type: 'SET_META_KEY', value: 'acfw_store_credit_balance' });
        dispatchParams({ type: 'SET_SORT_BY', value: 'meta_value_num' });
        break;
      default:
        dispatchParams({ type: 'SET_SORT_BY', value: sorter.columnKey });
        break;
    }

    dispatchParams({ type: 'SET_SORT_ORDER', value: sorter.order === 'descend' ? 'desc' : 'asc' });
  };

  /**
   * Initialize loading customers data.
   */
  useEffect(() => {
    setLoading(true);
    actions.readCustomers({
      params,
      successCB: (response) => {
        setTotal(response.headers['x-total']);
        setLoading(false);
      },
    });
  }, [params]);

  return (
    <>
      <Card title={labels.customers_list}>
        <div className="customer-search">
          <label>{labels.search_label}</label>
          <Input.Search allowClear value={search} onChange={(e: any) => handleSearch(e.target.value)} />
        </div>
        <Table
          className="customers-list-table"
          loading={loading}
          pagination={false}
          dataSource={customers}
          columns={columns}
          onChange={handleTableChange}
        />
        {0 < total && (
          <Pagination
            className="customers-list-pagination"
            defaultCurrent={params.page}
            current={params.page}
            hideOnSinglePage={true}
            disabled={loading}
            total={total}
            pageSize={params.per_page ?? 10}
            showSizeChanger={false}
            onChange={handlePaginationClick}
          />
        )}
      </Card>
      <StoreCreditsSingleCustomer customer={selectedCustomer} setCustomer={setSelectedCustomer} />
      <Modal
        width={500}
        visible={!isNull(adjustCustomer)}
        footer={false}
        onCancel={() => setAdjustCustomer(null)}
        onOk={() => setAdjustCustomer(null)}
      >
        <AdjustCustomerBalance customer={adjustCustomer} setAdjustCustomer={setAdjustCustomer} />
      </Modal>
    </>
  );
};

const mapStateToProps = (store: IStore) => ({ customers: store.storeCreditsCustomers });

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ readCustomers: readStoreCreditsCustomers, adjustCustomerStoreCredits }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(StoreCreditsCustomers);

// #endregion [Component]
