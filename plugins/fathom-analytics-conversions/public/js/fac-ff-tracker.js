jQuery(document).on("fluentform_submission_success", function (e, form) {
    const this_form = form.form,
        event_f = this_form.find('input[name="_fac_ff_event_id"]');
    if (event_f.length) { // if event field exist.
        event_id = event_f.val(); // get value.
        if (event_id) fathom.trackGoal(event_id, 0);
    }
});
