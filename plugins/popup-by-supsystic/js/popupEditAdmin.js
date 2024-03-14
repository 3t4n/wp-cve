
jQuery(document).ready(function(){
  jQuery('#ppsPopupPreviewFrame').on('load',function(){
    if(typeof(ppsHidePreviewUpdating) === 'function')
      ppsHidePreviewUpdating();
    var contentDoc = jQuery(this).contents()
    ,	popupShell = contentDoc.find('.ppsPopupShell')
    ,	paddingSize = 40
    ,	newWidth = (jQuery(this).get(0).contentWindow.document.body.scrollWidth + paddingSize)
    ,	newHeight = (jQuery(this).get(0).contentWindow.document.body.scrollHeight + paddingSize)
    ,	parentWidth = jQuery('#ppsPopupPreview').width()
    ,	widthMeasure = jQuery('#ppsPopupEditForm').find('[name="params[tpl][width_measure]"]:checked').val();

    if(widthMeasure == '%') {
      newWidth = parentWidth;
    } else {
      if(newWidth > parentWidth) {
        newWidth = parentWidth;
      }
    }
    jQuery(this).width( newWidth+ 'px' );
    jQuery(this).height( newHeight+ 'px' );

    if (1 == 0) { //if(in_array($this->popup['type'], array(PPS_FB_LIKE))
        jQuery(this).height( '500px' );
    }

    var top = 15
    ,	left = 0;
    if(typeof(ppsPopup) !== 'undefined') {
      var addMovePopUps = [				// Additional preview PopUps movements
        {id: 11, top: 30}				// START popup
      ,	{id: 16, left: 45}				// Pink popup
      ,	{id: 20, left: 40, top: 30}		// Discount popup
      ,	{id: 54, top: 50}				// Bump! popup
      ,	{id: 55, top: 70}				// Subscribe Me Bar popup
      ,	{id: 57, top: 20}				// Pyramid popup
      ];
      for(var i = 0; i < addMovePopUps.length; i++) {
        if(ppsPopup.id == addMovePopUps[i].id
          || ppsPopup.original_id == addMovePopUps[i].id
        ) {
          if(addMovePopUps[i].top) {
            top = addMovePopUps[i].top;
          }
          if(addMovePopUps[i].left) {
            left = addMovePopUps[i].left;
          }
        }
      }
    }
    popupShell.css({
      'position': 'fixed'
    ,	'top': top+ 'px'
    ,	'left': left+ 'px'
    });
    contentDoc.click(function(){
      return false;
    });
  }).attr('src', PPS_PEA_DATA.previewUrl);
});
