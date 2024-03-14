jQuery(function ($) {

  let existingIds = [];
  let itemByVariationId = {};
  let $instance = null;
  let galleryId;


  function filterByGroups(id = '') {
    if (!!!$instance) return;

    id = id * 1;

    if(!!id && inArray(id, existingIds)){
      id = id + '';
      $instance.switchGroup(id);
    }else{
      $instance.switchGroup('main');
    }

    $instance.jump(0);

    updateCaption(sirv_woo_product.mainID);
  }


  function inArray(val, arr){
    return arr.indexOf(val) !== -1;
  }


  function initializeCaption(){
    let id = sirv_woo_product.mainID;
    let isCaption = $('#sirv-woo-gallery_data_' + id).attr('data-is-caption');
    if(!!isCaption){
      let caption = getSlideCaption(id);
      if (!!!$('.sirv-woo-smv-caption_' + id).length) {
        $('#sirv-woo-gallery_' + id + ' .smv-slides-box').after('<div class="sirv-woo-smv-caption sirv-woo-smv-caption_' + id + '">'+ caption +'</div>');
      }
    }
  }


  function getSlideCaption(id){
    let $caption;

    if(!!galleryId){
      $caption = $($('#'+ galleryId +' .smv-slide.smv-shown .smv-content div')[0]);
    }else{
      $caption = $($('#sirv-woo-gallery_' + id + ' .smv-slide.smv-shown .smv-content div')[0]);
    }

    return $caption.attr('data-slide-caption') || '';
  }


  function updateCaption(id){
    $('.sirv-woo-smv-caption_' + id).html(getSlideCaption(id));
  }


/*   function getExistingIds(){
    const idsJsonStr = $("#sirv-woo-gallery_data_" + sirv_woo_product.mainID).attr('data-existings-ids');
    return JSON.parse(idsJsonStr);
  } */


  function getJSONData(key, type) {
    let data =  type === 'object' ? {} : [];
    const idsJsonStr = $("#sirv-woo-gallery_data_" + sirv_woo_product.mainID).attr(key);
    try {
      data = JSON.parse(idsJsonStr);
    } catch (error) {
      console.log(error);
    }
    return data;
  }


  function showVariation(variation_id){
    if (!!variation_id) {
      if (sirv_woo_product.variationStatus !== "allByVariation") {
        filterByGroups(variation_id);
      } else if (sirv_woo_product.variationStatus === "allByVariation" && !!itemByVariationId[variation_id]) {
        $instance.jump(itemByVariationId[variation_id]);
      }
    } else {
      if (sirv_woo_product.variationStatus === "all") {
        filterByGroups();
      } else {
        filterByGroups(sirv_woo_product.mainID);
      }
    }
  }


  $(document).ready(function () {

    existingIds = getJSONData("data-existings-ids", "array");
    itemByVariationId = getJSONData("data-item-by-variation-id", "object");
    galleryId = $('#sirv-woo-gallery_' + sirv_woo_product.mainID + ' div.smv').attr('id');


    $( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
    let variation_id = variation.variation_id || '';
      showVariation(variation_id);
    });

  /*//fire on select change
    $( '.variations_form' ).on( 'woocommerce_variation_select_change', function(event) {
      //code here
    }); */

    $('.reset_variations').on('click', function () {
      filterByGroups();
    });


    Sirv.on('viewer:ready', function (viewer) {
      $('.sirv-skeleton').removeClass('sirv-skeleton');
      $('.sirv-woo-opacity-zero').addClass('sirv-woo-opacity');
      $instance = Sirv.viewer.getInstance('#sirv-woo-gallery_' + sirv_woo_product.mainID);

      let curVariantId = $("input.variation_id").val() * 1;
      if(curVariantId > 0){
        if (sirv_woo_product.variationStatus === "allByVariation"){
          filterByGroups();
        }
          showVariation(curVariantId);
      }

      //galleryId = $('#sirv-woo-gallery_' + sirv_woo_product.mainID + ' div.smv').attr('id');
      initializeCaption();
    });


    Sirv.on('viewer:afterSlideIn', function(slide){
        let id = sirv_woo_product.mainID;
        let caption = getSlideCaption(id);

        $('.sirv-woo-smv-caption_' + id).html(caption);
    });

  }); //end dom ready
}); // end closure
