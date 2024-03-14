// #region [Imports] ===================================================================================================

// Libraries
import { useEffect, useState } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Spin } from 'antd';

// Types
import { ICouponTemplateCategory } from '../../types/couponTemplates';
import { IStore } from '../../types/store';

// Actions
import { CouponTemplatesActions } from '../../store/actions/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

const { readCouponTemplateCategories, readCouponTemplateCategory, setCouponTemplatesLoading, readCouponTemplates } =
  CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  readCouponTemplateCategories: typeof readCouponTemplateCategories;
  readCouponTemplateCategory: typeof readCouponTemplateCategory;
  setCouponTemplatesLoading: typeof setCouponTemplatesLoading;
  readCouponTemplates: typeof readCouponTemplates;
}

interface IProps {
  categories: ICouponTemplateCategory[];
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Sidebar = (props: IProps) => {
  const { categories, actions } = props;
  const { labels } = acfwAdminApp.coupon_templates_page;
  const [loading, setLoading] = useState(false);
  const [selectedCategory, setSelectedCategory] = useState('all');

  const handleCategoryClick = (slug: string) => {
    // Skip if the current category is selected.
    if (slug === selectedCategory) {
      return;
    }

    setSelectedCategory(slug);

    if (slug === 'all') {
      actions.readCouponTemplates({
        processingCB: () => setCouponTemplatesLoading({ loading: true }),
        successCB: () => setCouponTemplatesLoading({ loading: false }),
      });
      return;
    }

    actions.readCouponTemplateCategory({
      slug,
      processingCB: () => setCouponTemplatesLoading({ loading: true }),
      successCB: () => setCouponTemplatesLoading({ loading: false }),
    });
  };

  useEffect(() => {
    setLoading(true);
    actions.readCouponTemplateCategories({
      successCB: () => setLoading(false),
    });
  }, []);

  return (
    <div className="coupon-templates-sidebar">
      <div className="sidebar-inner">
        <h3>{labels.categories}</h3>
        <ul className="categories-list">
          {loading ? (
            <Spin />
          ) : (
            <>
              <li className={selectedCategory === 'all' ? 'current' : ''} onClick={() => handleCategoryClick('all')}>
                <span>{labels.all_templates}</span>
              </li>
              {categories.map((category: ICouponTemplateCategory) => (
                <li
                  className={selectedCategory === category.slug ? 'current' : ''}
                  key={category.slug}
                  onClick={() => handleCategoryClick(category.slug)}
                >
                  <span>{category.name}</span>
                </li>
              ))}
            </>
          )}
        </ul>
      </div>
    </div>
  );
};

const mapStateToProps = (state: IStore) => ({
  categories: state.couponTemplates?.categories ?? [],
});

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators(
    { readCouponTemplateCategories, readCouponTemplateCategory, setCouponTemplatesLoading, readCouponTemplates },
    dispatch
  ),
});

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar);

// #endregion [Component]
