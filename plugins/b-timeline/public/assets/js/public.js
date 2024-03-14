jQuery(document).ready(function ($) {
  let tlDataId = $(".bp_titleline");

  // console.log(timelineData);
  Object.values(tlDataId).map((timeline_item, index) => {
    const timelineData = $(timeline_item).data("timeline");
    if (!timelineData) return false;

    const { timeline_type, date_location, item_datas, start_item, move_item, visible_items, vertica_trigger, rtl_mode } = timelineData;

    $(timelineData).removeAttr("data-timeline");

    $(timeline_item).timeline({
      mode: timeline_type || "vertical",
      horizontalStartPosition: date_location,
      forceVerticalMode: 600,
      verticalStartPosition: date_location,
      verticalTrigger: `${vertica_trigger}%`,
      moveItems: parseInt(move_item),
      startIndex: parseInt(start_item),
      visibleItems: parseInt(visible_items),
      rtlMode: rtl_mode === "1",
    });
  });
});
