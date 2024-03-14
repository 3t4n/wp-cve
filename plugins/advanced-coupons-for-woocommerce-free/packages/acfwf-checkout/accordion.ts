// Global variables.
declare var jQuery: any;

const $ = jQuery;

/**
 * Sets up accordion events for the checkout UI block.
 *
 * @since 4.6.0
 */
export default function accordionEvents() {
  $('.acfw-checkout-ui-block').on('click', '.acfw-accordion > h3', toggleAccordionContent);
}

/**
 * Toggles the visibility of the accordion content.
 *
 * @since 4.6.0
 * @param this {JQuery} The element that triggered the event.
 */
function toggleAccordionContent(this: JQuery) {
  const $accordions = $('#acfw-checkout-ui-block').find('.acfw-accordion');
  const $accordion = $(this).parent();
  const isShown = $accordion.hasClass('show');

  $accordions.removeClass('show');

  if (!isShown) {
    $accordion.addClass('show');
  } else {
    $accordion.removeClass('show');
  }

  calculateAccordionContentMaxHeight.call(this);
}

/**
 * Calculates and sets the maximum height for accordion content.
 *
 * @since 4.6.0
 * @param this {JQuery} The element that triggered the event.
 */
function calculateAccordionContentMaxHeight(this: JQuery) {
  const $block = $('.acfw-checkout-ui-block');

  /**
   * Calculates the maximum height based on the height of the inner content.
   * The calculation takes into account the font size of the accordion content for accurate padding.
   */
  $block.find('.acfw-accordion').each(function (this: HTMLElement) {
    const $accordion = $(this);
    const $content = $accordion.find('.acfw-accordion-content');
    const $accordionInner = $accordion.find('.acfw-accordion-inner');
    const padding = 2 * parseInt(getComputedStyle($content[0]).fontSize) + 10;

    let contentHeight = $content.height() ?? 0;
    let accordionInnerHeight = ($accordionInner.height() ?? 0) - padding;

    contentHeight = accordionInnerHeight > 0 ? 0 : contentHeight + padding;
    // Override contentHeight if the accordion should not be displayed.
    if (!$accordion.hasClass('show')) {
      contentHeight = 0;
    }

    $accordionInner.css({ maxHeight: contentHeight });
  });
}
