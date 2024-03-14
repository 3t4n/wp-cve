// #region [Imports] ===================================================================================================

// Libraries
import { useMemo, useState, useEffect } from 'react';
import { Pagination } from 'antd';

// Types
import { ICouponTemplateListItem } from '../../types/couponTemplates';

// Components
import CouponTemplateCard from '../../components/CouponTemplateCard';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  templates: ICouponTemplateListItem[];
  isReview?: boolean;
  showClose?: boolean;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const TemplatesList = (props: IProps) => {
  const { templates, isReview, showClose } = props;
  const { labels } = acfwAdminApp.coupon_templates_page;
  const [page, setPage] = useState(1);
  const pageSize = 9;
  const displayedTemplates = useMemo(() => templates.slice((page - 1) * pageSize, page * pageSize), [templates, page]);

  // Reset page to 1 when templates change.
  useEffect(() => {
    setPage(1);
  }, [templates]);

  if (!templates.length) {
    return <div className="no-templates-message">{labels.no_templates_found}</div>;
  }

  return (
    <>
      <div className="queried-templates-list coupon-templates-list">
        {displayedTemplates.map((template: ICouponTemplateListItem) => (
          <CouponTemplateCard key={template.id} template={template} isReview={isReview} showClose={showClose} />
        ))}
      </div>
      <div className="templates-list-pagination">
        <Pagination
          defaultCurrent={1}
          pageSize={pageSize}
          current={page}
          total={templates.length}
          onChange={(value) => setPage(value)}
          hideOnSinglePage
        />
      </div>
    </>
  );
};

export default TemplatesList;

// #endregion [Component]
