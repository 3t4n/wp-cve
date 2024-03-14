/**
 * Search fields manager
 *
 * @package
 * @since 2.0.0
 */
import {scrollTo} from './functions';


const YWCAS_Search_Fields = () => {

  const addFieldButton = document.querySelector('.ywcas-add-field');
  const searchFieldTableBody = document.querySelector('#search-fields tbody');
  const fieldTemplate = wp.template('ywcas-search-fields');
  const allPossibleOptions = [];
  let usedSearchFields = [];
  let availableFields = [];
  let maxPriority = 1;
  const fieldToCheck = [
    'product_tag',
    'product_category',
    'product_attribute',
    'product_custom_field'];

  const setAllPossibleOptions = () => {
    const firstSearchFields = document.querySelector(
        '.ywcas-search-field-type');
    const options = firstSearchFields.querySelectorAll('option');
    options.forEach(option => allPossibleOptions.push(option.value));
  };

  const getUsedSearchFields = () => {
    usedSearchFields = [];
    document.querySelectorAll('.ywcas-search-field-type').forEach((item) => {
      usedSearchFields.push(item.value);
    });

  };

  const disableUsedOption = (selectItem) => {
    jQuery(selectItem).find('option').attr('disabled', false);
    usedSearchFields.forEach(usedField => {
      if (selectItem.value !== usedField) {
        jQuery(selectItem).
            find(`option[value="${usedField}"]`).
            attr('disabled', true);
      }
    });
    jQuery(document.body).trigger('wc-enhanced-select-init');
    jQuery(selectItem).selectWoo();
  };

  const checkTrashButton = () => {
    const searchFields = jQuery('.ywcas-search-field');
    if (searchFields.length == 1) {
      jQuery('.action__trash').hide();
    } else {
      jQuery('.action__trash').show();
    }

  };

  const refreshSearchFieldsOptions = () => {
    getUsedSearchFields();
    checkTrashButton();
    jQuery(document).trigger('yith-plugin-fw-tips-init');
    document.querySelectorAll('.ywcas-search-field-type').forEach((item) => {
      disableUsedOption(item);
    });
  };

  const addField = (e) => {
    e.preventDefault();
    getUsedSearchFields();

    availableFields = allPossibleOptions.filter(
        x => !usedSearchFields.includes(x));

    const newField = fieldTemplate({
      id: Date.now(),
      edit: false,
    });

    const row = document.createElement('tr');
    row.innerHTML = newField;
    row.classList.add('ywcas-search-field');

    row.querySelector('.ywcas-search-field-type').value = availableFields[0];
    row.querySelector('.ywcas-search-priority').value = getMaxPriority();
    searchFieldTableBody.appendChild(row);
    jQuery(row).find('.ywcas-search-field-type').trigger('change');

    updateAddFieldButton();

    jQuery(document.body).trigger('wc-enhanced-select-init');
    jQuery(document.body).trigger('yith-framework-enhanced-select-init');
    refreshSearchFieldsOptions();

  };

  const updateAddFieldButton = () => {

    if (usedSearchFields.length === allPossibleOptions.length) {
      jQuery(addFieldButton).hide();
    } else {
      jQuery(addFieldButton).show();
    }
  };

  const checkFieldType = (e) => {
    const currentElement = e.target;
    const typeSelected = currentElement.value;
    const wrapper = currentElement.closest('.ywcas-search-field');
    const conditions = jQuery(wrapper).find(`[data-type="${typeSelected}"]`);

    jQuery(wrapper).
        find('.search-field-type-condition,.search-field-type-list').
        hide();
    if (conditions) {
      conditions.css({display: 'block'});
    }

    refreshSearchFieldsOptions();
  };

  const getMaxPriority = () => {
    maxPriority = 1;
    document.querySelectorAll('.ywcas-search-priority').forEach(priority => {
      maxPriority = priority.value > maxPriority ? priority.value : maxPriority;
    });

    return ++maxPriority;
  };

  const checkSubCondition = (e) => {
    const currentElement = e.target;
    const valueSelected = currentElement.value;
    const row = currentElement.closest('tr');
    const wrapper = currentElement.closest('.search-field-type-condition');
    const typeSelected = wrapper.dataset.type;

    if (valueSelected !== 'all') {
      jQuery(row).find(`[data-subtype="${typeSelected}"]`).show();
    } else {
      jQuery(row).find(`[data-subtype="${typeSelected}"]`).hide();
    }
  };

  const handleClickTrash = (e) => {
    e.preventDefault();
    const trashElement = e.target;
    const searchFields = document.querySelectorAll('.ywcas-search-field');
    if (searchFields.length <= 1) {
      return;
    }
    yith.ui.confirm({
      title: ywcas_admin_params.message_alert.title,
      message: ywcas_admin_params.message_alert.desc,
      confirmButton: ywcas_admin_params.message_alert.confirmButton,
      closeAfterConfirm: true,
      classes: {
        wrap: 'ywcas-warning-popup'
      },
      onConfirm: function onConfirm() {
        const currentRow = trashElement.closest('tr');
        currentRow.remove();
        checkTrashButton();
        getUsedSearchFields();
        updateAddFieldButton();
        jQuery('#tiptip_holder').hide();
      }
    });

  };

  const handleClickSave = (e) => {
    e.preventDefault();
    const fields = document.querySelectorAll('.ywcas-search-field');

    let firstErrorElement = false;
    fields.forEach(field => {
      const type = jQuery(field).find('select.ywcas-search-field-type');
      let group = type.val();
      if (!fieldToCheck.includes(group)) {
        return;
      }
      let condition = null;
      if (group === 'product_tag' || group === 'product_category') {
        condition = jQuery(field).find(`.${group}-condition`);
      }

      let list = jQuery(field).find(`.select-${group}`);
      let wrapper = list.closest('.yith-plugin-fw-select2-wrapper');
      if ((!condition || condition.val() != 'all') && list.val()?.length ===
          0 && !wrapper.find('.select2').hasClass('is-empty')) {
        if (!firstErrorElement) {
          firstErrorElement = list;
        }
        wrapper.append(jQuery(
            `<span class='empty-field description'>${ywcas_admin_params.emptyField}</span>`));
        wrapper.find('.select2').addClass('is-empty');
      }
    });

    if (firstErrorElement) {
      scrollTo(firstErrorElement);
    } else {
      jQuery('#plugin-fw-wc').submit();
    }

  };

  const removeError = (e) => {
    const wrapper = jQuery(e.target).closest('.yith-plugin-fw-select2-wrapper');
    wrapper.find('.empty-field').remove();
    wrapper.find('.select2').removeClass('is-empty');
  };



  const init = () => {
    setAllPossibleOptions();
    checkTrashButton();
    getUsedSearchFields();
    updateAddFieldButton();

    jQuery(document).on('click', '.ywcas-add-field', addField);
    jQuery(document).on('change', '.ywcas-search-field-type', checkFieldType);
    jQuery(document).
        on('change', '.search-field-type-condition select', checkSubCondition);
    jQuery(document).on('click', '.action__trash', handleClickTrash);
    jQuery(document).on('click', '#main-save-button', handleClickSave);
    jQuery(document).on('change', '.yith-term-search', removeError);

    jQuery(document).find('.ywcas-search-field-type').trigger('change');
    jQuery(document).find('.search-field-type-condition select').trigger('change');
  };

  init();
};

YWCAS_Search_Fields();