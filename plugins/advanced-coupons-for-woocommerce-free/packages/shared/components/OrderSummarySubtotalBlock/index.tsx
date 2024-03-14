/**
 * Simple order summary subtotal block.
 *
 * @since 4.6.0
 *
 * @return {JSX.Element} Accordion component.
 * */
const OrderSummarySubtotalBlock = (props: any) => {
  const { label, value, children } = props;

  return (
    <div className="wp-block-woocommerce-checkout-order-summary-subtotal-block wc-block-components-totals-wrapper">
      <div className="wc-block-components-totals-item">
        <span className="wc-block-components-totals-item__label">{label}</span>
        <span className="wc-block-components-totals-item__value">
          <div
            dangerouslySetInnerHTML={{
              __html: value,
            }}
          />
        </span>
        <div className="wc-block-components-totals-item__description">{children}</div>
      </div>
    </div>
  );
};

export default OrderSummarySubtotalBlock;
