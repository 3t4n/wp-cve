// #region [Imports] ===================================================================================================

import { Skeleton } from 'antd';
import { SkeletonParagraphProps } from 'antd/lib/skeleton/Paragraph';

// #endregion [Imports]

// #region [Variables] =================================================================================================

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  className?: string;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const TemplatesSkeleton = (props: IProps) => {
  const { className } = props;
  const paragraphProps = { rows: 2, width: 100 } as SkeletonParagraphProps;

  return (
    <div className={`coupon-templates-list ${className}`}>
      {[...Array(9)].map((_, key) => (
        <div key={key} className="coupon-template-card">
          <div className="template-image"></div>
          <div className="template-content">
            <Skeleton active paragraph={paragraphProps} />
          </div>
        </div>
      ))}
    </div>
  );
};

export default TemplatesSkeleton;

// #endregion [Component]
