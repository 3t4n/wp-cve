.gform_wrapper.gravity-theme <?php echo $form_id ?>  .gf_step_number {
	background: <?php echo $inactive_number_bg ?>;
    color: <?php echo $inactive_number_color ?>;
    border-color: <?php echo $inactive_border ?>;
}

.gform_wrapper.gravity-theme <?php echo $form_id ?> .gf_step_label {
	color: <?php echo $inactive_text_color ?>;
}


.gform_wrapper.gravity-theme <?php echo $form_id ?>  .gf_step_active .gf_step_number {
	background: <?php echo $active_number_bg ?>;
    color: <?php echo $active_number_color ?>;
    border-color: <?php echo $active_border ?>;
}

.gform_wrapper.gravity-theme <?php echo $form_id ?>  .gf_step_active .gf_step_label {
	color: <?php echo $active_text_color ?>;
}

.gform_wrapper.gravity-theme <?php echo $form_id ?> .gf_step_completed .gf_step_number:before {
	background: <?php echo $completed_number_bg ?>;
    color: <?php echo $completed_number_color ?>;
    border-color: <?php echo $completed_border ?>;
}

.gform_wrapper.gravity-theme <?php echo $form_id ?> .gf_step_completed .gf_step_number:after {
	color: <?php echo $completed_number_color ?>;
}

.gform_wrapper.gravity-theme <?php echo $form_id ?>  .gf_step_completed .gf_step_label {
	color: <?php echo $completed_text_color ?>;
}