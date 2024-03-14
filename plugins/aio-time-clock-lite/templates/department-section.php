
<?php
$tax = get_taxonomy('department');
if (!current_user_can($tax->cap->assign_terms)) {
    return;
}
$terms = get_terms('department', array('hide_empty' => false));?>
<h3><?php echo esc_attr_x('Department', 'aio-time-clock-lite');?></h3>
<table class="form-table">
    <tr>
        <th><label for="department"><?php echo esc_attr_x('Select Department', 'aio-time-clock-lite');?></label></th>
        <td>
            <?php
            /* If there are any department terms, loop through them and display checkboxes. */
            if (!empty($terms)) {

                foreach ($terms as $term) {?>
                    <input type="radio" name="department" id="department-<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>" <?php checked(true, is_object_in_term($user->ID, 'department', $term));?> /> <label for="department-<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></label> <br />
                <?php 
                }
            }
            /* If there are no department terms, display a message. */
            else {
                echo esc_attr_x('There are no departments available', 'aio-time-clock-lite');
            }
            ?>
        </td>
    </tr>
</table>