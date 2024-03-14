import YWCAS_Synonymous_Field from './synonymous-field';

const YWCAS_Admin_Panel = () => {
  const handleDeps = () => {
    const documentDeps = document.querySelectorAll('[data-deps]');

    documentDeps.forEach(item => {
      const wrap = jQuery(item).closest('.yith-plugin-fw__panel__option');
      const deps = item.dataset.deps.split(',');
      const values = item.dataset.deps_value.split(',');
      const conditions = [];

      deps.forEach((dep, index) => {
        jQuery('#' + dep ).on('change', function() {
          let value = this.value;
          let   check_values = '';

          // exclude radio if not checked
          if (this.type == 'radio' && !this.checked) {
            return;
          }

          if (this.type == 'checkbox') {
            value = this.checked ? 'yes' : 'no';
          }

          check_values = String(values[index]); // force to string
          check_values = check_values.split('|');
          conditions[index] = check_values.includes(value);
          if (!conditions.includes(false)) {
            wrap.show('slow');
          } else {
            wrap.hide();
          }
        }).change();
      });
    });
  };
  const checkFuzzySlider = () => {
    jQuery('.ywcas-slider-wrapper .ui-slider-horizontal input').on('change', function(e){
      let sliderValue = jQuery('#yith_wcas_fuzzy_level').val();
      sliderValue = '' === sliderValue ? 0 : sliderValue;

      jQuery('#yith_wcas_fuzzy_level-preview').val(sliderValue);
    }).change();
  }

  const init = () => {
    handleDeps();
    checkFuzzySlider();

    if( typeof document.querySelector('.ywcas-synonymous')!== 'undefined' ){
      YWCAS_Synonymous_Field();
    }

  };
  init();
};

YWCAS_Admin_Panel();