/**
 * Synonymous fields manager
 *
 * @package
 * @since 2.0.0
 */

const YWCAS_Synonymous_Field = () => {
  const fieldTemplate = wp.template('ywcas-synonymous');
  const mainWrapper   = document.querySelector('.ywcas-synonymous-main-wrapper');
  const addNewRow = (e) => {
    e.preventDefault();
    const newField = fieldTemplate({
      id: Date.now(),
      edit: false,
    });
    jQuery(mainWrapper).append(newField);
    refresh();
  }

  const deleteRow = (e) => {
    e.preventDefault();
    e.target.closest('.ywcas-synonymous').remove();
    refresh();
  }

  const refresh = () =>{
    checkTrashButton();
    jQuery(document).trigger('yith-plugin-fw-tips-init');
  }

  const checkTrashButton = () => {
    const synFields =  jQuery(mainWrapper).find('.option');
    if (synFields.length == 1) {
      document.querySelector('#tiptip_holder').remove();
      jQuery('.action__trash').hide();
    } else {
      jQuery('.action__trash').show();
    }
  };

  const init = () =>{
    checkTrashButton();

    const addRow = document.querySelector('.ywcas-add-synonymous a');
    if( addRow ){
      addRow.addEventListener('click', addNewRow );
      jQuery(document).on('click', '.action__trash', deleteRow);
    }

  };
  init();
}

export default YWCAS_Synonymous_Field;