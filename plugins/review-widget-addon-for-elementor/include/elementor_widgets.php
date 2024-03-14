<?php
namespace Elementor;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use function Webmozart\Assert\Tests\StaticAnalysis\true;
if ( ! defined( 'ABSPATH' ) ) exit;
class Trustindex_Elementor_Widget extends Widget_Base {
public function get_name() {
return 'trustindex-addon';
}
public function get_title() {
return 'Trustindex';
}
public function get_icon() {
return 'eicon-check-circle';
}
public function get_categories() {
return [ 'general' ];
}
public function get_ti_admin_widgets(){
if (!check_ti_active())
{
return array('' => 'Please activate Trustindex core plugin!');
}
$request = new \WP_REST_Request( 'GET', '/trustindex/v1/get-widgets' );
$response = rest_do_request( $request );
$data = rest_get_server()->response_to_data( $response, true );
if (empty($data))
{
return array('' => 'Please connect your Trustindex account!');
}
$widgets = array_column($data, 'widgets');
$result = array();
foreach ($widgets as $widget)
{
$result = array_merge($result, array_combine(array_column($widget, "id"), array_column($widget, "name")));
}
return $result;
}
public function get_ti_wp_widgets(){
$request = new \WP_REST_Request( 'GET', '/trustindex/v1/setup-complete' );
$response = rest_do_request( $request );
$data = rest_get_server()->response_to_data( $response, true );
$result = [];
if (isset($data['result']))
{
foreach ($data['result'] as $platform => $value)
{
if ($value)
{
$result[$platform] = ucfirst($platform);
}
}
}
if (empty($result))
{
$result = ['' => "Please set up your Trustindex widget!"];
}
return $result;
}
protected function _register_controls() {
$this->start_controls_section(
'ti-select',
[
'label' => 'Add review widget',
]
);
$this->add_control(
'selector',
[
'label' => 'Select widget',
'type' => Controls_Manager::SELECT,
'label_block' => true,
'options' => [
'admin' => 'From connected Trustindex account',
'free' => 'From Free Widget Configurator',
'custom_widget' => 'By Trustindex widget ID',
],
]
);
$this->add_control(
'admin_widget',
[
'label' => 'Select widget',
'type' => Controls_Manager::SELECT,
'label_block' => true,
'options' => $this->get_ti_admin_widgets(),
'condition' => [
'selector' => 'admin',
],
]
);
$this->add_control(
'wp_widgets',
[
'label' => 'Select widget',
'type' => Controls_Manager::SELECT,
'label_block' => true,
'options' => $this->get_ti_wp_widgets(),
'condition' => [
'selector' => 'free',
],
]
);
$this->add_control(
'custom_widget',
[
'label' => 'Widget ID',
'type' => Controls_Manager::TEXT,
'condition' => [
'selector' => 'custom_widget',
],
]
);
$this->end_controls_section();
}
protected function render() {
$settings = $this->get_settings_for_display();
$display = "";
$message = "Please select your widget!";
if ($settings['selector'] == 'admin')
{
if (!check_ti_active())
{
$message = 'Please activate Trustindex core plugin!';
}
else
{
$message = "Please connect your Trustindex account!";
}
$display = wp_get_script_tag(
array(
'src'=> esc_url( "https://cdn.trustindex.io/loader.js?{$settings['admin_widget']}" ),
'defer' => true,
'async' => true
)
);
}
else if ($settings['selector'] == 'custom_widget')
{
$message = "Please paste your widget id!";
$display = wp_get_script_tag(
array(
'src'=> esc_url( "https://cdn.trustindex.io/loader.js?{$settings['custom_widget']}" ),
'defer' => true,
'async' => true
)
);}
else if ($settings['selector'] == 'free')
{
$message = "Please set up your Trustindex widget!";
$this->add_render_attribute( 'shortcode', 'no-registration', $settings['wp_widgets'] );
$display = do_shortcode(sprintf( '[trustindex %s]', $this->get_render_attribute_string( 'shortcode' ) ));
}
?>
<div>
<?php
if( !empty( $settings['admin_widget'] ) || !empty( $settings['custom_widget'] ) || !empty( $settings['wp_widgets'] ) ){
echo $display;
}
else
{
echo '<div class="form_no_select">'.$message.'</div>';
}
?>
</div>
<?php
}
}
$manager = Plugin::instance()->widgets_manager;
if(method_exists($manager, 'register'))
{
$manager->register( new Trustindex_Elementor_Widget() );
}
else
{
$manager->register_widget_type( new Trustindex_Elementor_Widget() );
}