// Global variables.
declare var wp: any;

/**
 * Simple accordion component.
 *
 * @since 4.5.9
 *
 * @return {JSX.Element} Accordion component.
 * */
const Accordion = (props: any) => {
  const { useState } = wp.element;
  const { title, children, caret_img_src } = props;

  /**
   * Handle Accordion Click Toggle.
   *
   * @since 4.5.9
   * @param event
   * */
  const handleAccordionClickToggle = (event: any) => {
    // Toggle Accordion.
    setVisibile(!visible);

    /**
     * Toggle the inner content.
     * - This is due to transition needs precise height for animation.
     * */
    let content = event.currentTarget.nextElementSibling;
    let maxHeight = content.style.maxHeight;
    maxHeight = parseInt(maxHeight.replace('px', ''));
    maxHeight = maxHeight > 0 ? 0 : content.scrollHeight;
    content.style.maxHeight = `${maxHeight}px`;
  };

  /**
   * Get carret element.
   *
   * @since 4.5.9
   *
   * @return {JSX.Element} Carret element.
   * */
  const getCarret = () => {
    if (caret_img_src) {
      return (
        <span className="caret">
          <img src={caret_img_src} />
        </span>
      );
    }
  };

  const [visible, setVisibile] = useState(false);
  return (
    <div className="acfw-accordion">
      <div className={`acfw-accordion acfw-store-credits-checkout-ui ${visible ? 'show' : ''}`}>
        <h3 onClick={handleAccordionClickToggle}>
          <span className="acfw-accordion-title">{title}</span>
          {getCarret()}
        </h3>
        <div className="acfw-accordion-inner">
          <div className="acfw-accordion-content">{children}</div>
        </div>
      </div>
    </div>
  );
};

export default Accordion;
