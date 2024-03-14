jQuery(document).ready(function () {
  jQuery("table.cluevo-filtered-table .cluevo-table-filter").click(function () {
    var id = jQuery(this).data("id");
    var target = jQuery(this).data("target");
    jQuery(target).val(id);
    jQuery(target).parents("form:first").submit();
  });

  jQuery('input[type="button"].cluevo-reset-filters').click(function () {
    jQuery("select.cluevo-filter-input").each(function (_, el) {
      jQuery(el).val(jQuery(el).find("option:first").val());
    });
    jQuery(this).parents("form:first").submit();
  });

  jQuery('select[name="cur-page"]').on("change", function () {
    jQuery("#cur-page").val(jQuery(this).val());
    jQuery("#cur-page").parents("form:first").submit();
  });

  jQuery(".cluevo-autocomplete-container").on("selected", function () {
    jQuery(this).parents("form:first").submit();
  });
});
