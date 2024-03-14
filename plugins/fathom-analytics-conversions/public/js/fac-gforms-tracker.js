jQuery(document).on("gform_confirmation_loaded", function (e, form_id) {
    const event_id = gforms_data[form_id];
    if (event_id) fathom.trackGoal(event_id, 0);
});
