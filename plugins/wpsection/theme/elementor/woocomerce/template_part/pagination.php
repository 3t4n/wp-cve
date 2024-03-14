       
 <?php
if ($enable_pagination) {
    echo '<div class="wps_pagination_priduct_area">';
    wpsection_the_pagination(array(
        'total' => $query->max_num_pages,
        'next_text' => '<i class="eicon-arrow-right"></i> ',
        'prev_text' => '<i class="eicon-arrow-left"></i>'
    ));
    echo '</div>';
}
?>
                                 