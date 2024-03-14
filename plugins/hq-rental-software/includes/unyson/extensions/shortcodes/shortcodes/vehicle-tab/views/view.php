<?php if (!defined('FW')) {
    die('Forbidden');
} ?>


<?php
//fw_print( $atts );
$id = uniqid('tab-cont-');
$vehicle = uniqid('vehicle-');
$select = uniqid('select-');
if (empty($atts['vehicle_models'])) {
    return;
}
?>

<div id="vehicles" class="container">
    <div class="row">
        <div class="col-md-12">

            <h2 class="title wow fadeInDown" data-wow-offset="200"><?php echo sprintf($atts['vehicle_title'], '<span class="subtitle">', '</span>') ?></h2>
        </div>

        <!-- Vehicle nav start -->
        <div class="col-md-3 vehicle-nav-row wow fadeInUp" data-wow-offset="100">
            <div id="<?php echo $vehicle ?>-nav-container" class="vehicle-container <?php echo $select; ?>">
                <ul class="<?php echo $vehicle ?>-nav vehicle-tab-nav">
                    <?php
                    $counter = 1;
                    foreach ($atts['vehicle_models'] as $tab) :
                        ?>
                        <li <?php echo ($counter == 1) ? 'class="active"' : ''; ?>>
                            <a href="#<?php echo $id . '-' . $counter; ?>"><?php echo fw_theme_translate(esc_attr($tab['model_name'])); ?></a>
                            <span class="active">&nbsp;</span>
                        </li>
                        <?php
                        $counter++;
                    endforeach;
                    ?>
                </ul>
            </div>
            <div class="<?php echo $vehicle ?>-nav-control vehicle-scroll hidden-sm">
                <a class="<?php echo $vehicle ?>-nav-scroll vehicle-scroll" data-direction="up" href="#"><i class="fa fa-chevron-up"></i></a>
                <a class="<?php echo $vehicle ?>-nav-scroll vehicle-scroll" data-direction="down" href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
        </div>
        <!-- Vehicle nav end -->
        <?php
        $cnt = 1;
        foreach ($atts['vehicle_models'] as $tab) :
            ?>
            <!-- Vehicle 1 data start -->
            <div class="<?php echo $vehicle ?>-data" id="<?php echo $id . '-' . $cnt; ?>">
                <div class="col-md-6 ">
                    <div class="vehicle-img">
                        <?php if (!empty($tab['image'])) : ?>
                            <img class="img-responsive" src="<?php echo esc_url($tab['image']['url']); ?>" alt="Vehicle">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="vehicle-price"><?php echo esc_attr($tab['rent_rate']); ?>
                        <span class="info">
                            <?php echo esc_attr($tab['perday']); ?>
                        </span>
                    </div>
                    <table class="table vehicle-features">
                        <?php
                        $details = $tab['vehicle_details'];
                        if (!empty($details)) :
                            foreach ($details as $feature) :
                                ?>
                                <tr>
                                    <td><?php echo wp_kses_post($feature['feature']) ?></td>
                                    <td><?php echo esc_attr($feature['feature_details']) ?></td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                    </table>
                    <a href="#teaser" class="reserve-button scroll-to"><span class="glyphicon glyphicon-calendar"></span> <?php _e('Reserve now', 'fw') ?></a>
                </div>
            </div>
            <!-- Vehicle 1 data end -->
            <?php
            $cnt++;
        endforeach;
        ?>


    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {


        $(".<?php echo $vehicle ?>-data").hide();
        var activeVehicleData = $(".<?php echo $vehicle ?>-nav .active a").attr("href");
        $(activeVehicleData).show();
        $('.<?php echo $vehicle ?>-nav-scroll').click(function () {
            var topPos = 0;
            var direction = $(this).data('direction');
            var scrollHeight = $('.<?php echo $vehicle ?>-nav li').height() + 1;
            var navHeight = $('#<?php echo $vehicle ?>-nav-container').height() + 1;
            var actTopPos = $(".<?php echo $vehicle ?>-nav").position().top;
            var navChildHeight = $('#<?php echo $vehicle ?>-nav-container').find('.<?php echo $vehicle ?>-nav').height();
            var x = -(navChildHeight - navHeight);
            var fullHeight = 0;
            $('.<?php echo $vehicle ?>-nav li').each(function () {
                fullHeight += scrollHeight;
            });
            navHeight = fullHeight - navHeight + scrollHeight;
            // Scroll Down
            if ((direction == 'down') && (actTopPos > x) && (-navHeight <= (actTopPos - (scrollHeight * 2)))) {
                topPos = actTopPos - scrollHeight;
                $(".<?php echo $vehicle ?>-nav").css('top', topPos);
            }
            // Scroll Up
            if (direction == 'up' && 0 > actTopPos) {
                topPos = actTopPos + scrollHeight;
                $(".<?php echo $vehicle ?>-nav").css('top', topPos);
            }
            return false;
        });

        $(".<?php echo $vehicle ?>-nav li").on("click", function () {

            $(".<?php echo $vehicle ?>-nav .active").removeClass("active");
            $(this).addClass('active');

            $(activeVehicleData).fadeOut("slow", function () {
                activeVehicleData = $(".<?php echo $vehicle ?>-nav .active a").attr("href");
                $(activeVehicleData).fadeIn("slow", function () {
                });
            });

            return false;
        });

        // Vehicles Responsive Nav  
        //-------------------------------------------------------------
        var windowWidth = $(window).width();
        if (windowWidth < 990) {
            $("<div />").appendTo(".<?php echo $select ?>").addClass("<?php echo $select ?>select-vehicle-data");
            $("<select />").appendTo(".<?php echo $select ?>").addClass("<?php echo $select ?>-data-select");
            $(".<?php echo $select ?> a").each(function () {
                var el = $(this);
                $("<option />", {
                    "value": el.attr("href"),
                    "text": el.text()
                }).appendTo(".<?php echo $select ?> select");
            });

            $(".<?php echo $select ?>-data-select").change(function () {
                $(activeVehicleData).fadeOut("slow", function () {
                    activeVehicleData = $(".<?php echo $select ?>-data-select").val();
                    $(activeVehicleData).fadeIn("slow", function () {
                    });
                });

                return false;
            });
        }



    });


</script>