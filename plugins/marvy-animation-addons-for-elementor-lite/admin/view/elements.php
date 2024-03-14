<?php
$marvyOptions = get_option('marvy_option_settings');
$marvyOptions = !empty($marvyOptions) ? $marvyOptions : [];
$checkElements = array_keys($marvyOptions);
$elements = [
    'content-elements'  => [
        'title' => __( 'Basic Animation', 'marvy-animation-addons-for-elementor-lite'),
        'elements'  => [
            [
                'key'   => 'drop_animation',
                'title' => __( 'Drop Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/drop-2/'
            ],
            [
                'key'   => 'fancy_rotate',
                'title' => __( 'Fancy Rotate Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/fancy-rotate/'
            ],
            [
                'key'   => 'flying_object',
                'title' => __( 'Flying Object Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/flying-object/'
            ],
            [
                'key'   => 'ripples_animation',
                'title' => __( 'Ripples Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/ripples/'
            ],
            [
                'key' =>  'waves_animation',
                'title' => __( 'Waves Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/waves/'
            ],
            [
                'key' =>  'rings_animation',
                'title' => __( 'Rings Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/rings/'
            ],
            [
                'key' =>  'topology_animation',
                'title' => __( 'Topology Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/topology/'
            ],
            [
                'key' =>  'gradient_animation',
                'title' => __( 'Gradient Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/gradient/'
            ],
            [
                'key' =>  'snow_animation',
                'title' => __( 'Snow Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/snow/'
            ],
            [
                'key' =>  'firework_animation',
                'title' => __( 'Firework Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/firework/'
            ],
            [
                'key' =>  'cloud_animation',
                'title' => __( 'Cloud Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => false,
                'demo' => ''
            ],
            [
                'key' =>  'birds_animation',
                'title' => __( 'Birds Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/birds/'
            ],
            [
                'key' =>  'cells_animation',
                'title' => __( 'Cells Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/cells/'
            ],
            [
                'key' =>  'dots_animation',
                'title' => __( 'Dots Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/dots/'
            ],
            [
                'key' =>  'fog_animation',
                'title' => __( 'Fog Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/fog/'
            ],
            [
                'key' =>  'globe_animation',
                'title' => __( 'Globe Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/globe/'
            ],
            [
                'key' =>  'halo_animation',
                'title' => __( 'Halo Animation', 'marvy-animation-addons-for-elementor-lite'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/halo/'
            ],
            [
                'key' =>  'net_animation',
                'title' => __( 'Net Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/net/'
            ],
            [
                'key' =>  'trunk_animation',
                'title' => __( 'Trunk Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/trunk/'
            ],
            [
                'key' =>  'fluid_animation',
                'title' => __( 'Fluid Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/fluid/'
            ],
            [
                'key' =>  'digitalStream_animation',
                'title' => __( 'Digital Stream Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/digital-stream/'
            ],
            [
                'key' =>  'floating_heart_animation',
                'title' => __( 'Floating Heart Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/floating-heart/'
            ],
            [
                'key' =>  'particles_wave_animation',
                'title' => __( 'Particles Wave Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/particle-wave/'
            ],
            [
                'key' =>  'dna_animation',
                'title' => __( 'DNA Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/dna-animation/'
            ],
            [
                'key' =>  'beyblade_animation',
                'title' => __( 'Beyblade Animation', 'marvy-lang'),
                'is_pro' => true,
                'demo' => 'https://wordpress.iqonic.design/product/plugin/marvy/beyblade/'
            ]
          ]
      ]
 ];
?>

<?php foreach ($elements as $element) { ?>
    <div class="row">
        <h1><?php esc_html_e($element['title']) ?></h1>
    </div>
    <div class="row">

        <?php  foreach($element['elements'] as $key => $item) { ?>
            <div class="col-3">
                <div class="marvy-checkbox">
                    <div class="info">
                        <p><a href="<?= esc_html__($item['demo']) ?>" target="_blank"> <?php esc_html_e($item['title']) ?></a></p>
                    </div>
                    <?php if ($item['is_pro']) { ?>
                        <label class="pro-status">Pro</label>
                    <?php }
                    if ((boolean) get_transient('marvy_animation_pro') === true) {
                        $disabled = true;
                    } else {
                        if ($item['is_pro'] === true) {
                            $disabled = false;
                        } else {
                            $disabled = true;
                        }
                    }
                    ?>
                    <label class="switch">
                        <input type="checkbox" id="switch" name="<?php echo esc_attr($item['key']); ?>"  <?php echo checked( 1, $disabled === true && !is_array(marvy_get_setting($item['key'])) ? marvy_get_setting($item['key']) : false, false ) ?> <?php echo ($disabled === true ? '' : 'disabled') ?> >
                        <small></small>
                    </label>
                </div>
            </div>
        <?php } ?>

    </div>
<?php } ?>
<div class="text-right">
    <button type="button" class="btn marvy-setting-save"><?php esc_html_e('Save settings', 'marvy-lang'); ?></button>
</div>
