<?php

class WP3CXW_Editor {

	private $webinar_form;
	private $panels = array();

	public function __construct( WP3CXW_WebinarForm $webinar_form ) {
		$this->webinar_form = $webinar_form;
	}

	public function add_panel( $id, $title, $callback ) {
		if ( wp3cxw_is_name( $id ) ) {
			$this->panels[$id] = array(
				'title' => $title,
				'callback' => $callback,
			);
		}
	}

	public function display() {
		if ( empty( $this->panels ) ) {
			return;
		}

		echo '<ul id="webinar-form-editor-tabs">';

		foreach ( $this->panels as $id => $panel ) {
			echo sprintf( '<li id="%1$s-tab"><a href="#%1$s">%2$s</a></li>',
				esc_attr( $id ), esc_html( $panel['title'] ) );
		}

		echo '</ul>';

		foreach ( $this->panels as $id => $panel ) {
			echo sprintf( '<div class="webinar-form-editor-panel" id="%1$s">',
				esc_attr( $id ) );

			if ( is_callable( $panel['callback'] ) ) {
				$this->notice( $id, $panel );
				call_user_func( $panel['callback'], $this->webinar_form );
			}

			echo '</div>';
		}
	}

	public function notice( $id, $panel ) {
		echo '<div class="config-error"></div>';
	}
}

function wp3cxw_editor_panel_config( $post ) {
	wp3cxw_editor_box_config( $post );
}

function wp3cxw_editor_box_config( $post, $args = '' ) {
	$args = wp_parse_args( $args, array(
		'id' => 'wp3cxw-config',
		'name' => 'config',
		'title' => __( 'Configure from which 3CX Webinars shall be loaded and how long data shall be cached by the server.', '3cx-webinar' ),
		'use' => null,
	) );

	$id = esc_attr( $args['id'] );

	$config = wp_parse_args( $post->prop( $args['name'] ), array(
		'active' => false,
		'apitoken' => '',
		'cache_expiry' => 5,
		'portalfqdn' => '',
		'extension' => '',
		'country' => '',
		'maxparticipants' => 0,
		'subject' => '',
		'days' => 0
	) );

?>
<div class="webinar-form-editor-box-config" id="<?php echo $id; ?>">
<h2><?php echo esc_html( $args['title'] ); ?></h2>
<table class="form-table">
<tbody>
<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-active"><?php echo esc_html( __( 'Enabled', '3cx-webinar' ) ); ?>&nbsp;
	</th>
	<td>
		<input type="checkbox" id="<?php echo $id; ?>-active" name="<?php echo $id; ?>[active]" class="" <?php echo ($config['active'] ? "checked" : "");?> data-config-field="<?php echo sprintf( '%s.active', esc_attr( $args['name'] ) ); ?>" />
	</td>
	</tr>

	<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-portalfqdn"><?php echo esc_html( __( '3CX Public HTTPS URL', '3cx-webinar' ) ); ?>&nbsp;
	</th>
	<td>
		<input type="text" id="<?php echo $id; ?>-portalfqdn" name="<?php echo $id; ?>[portalfqdn]" placeholder="<?php echo __('https://3cx.example.com:5001', '3cx-webinar');?>" class="large-text code" size="70" value="<?php echo esc_attr( $config['portalfqdn'] ); ?>" data-config-field="<?php echo sprintf( '%s.portalfqdn', esc_attr( $args['name'] ) ); ?>" />
	</td>
	</tr>
	
	<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-apitoken"><?php echo esc_html( __( '3CX API Key', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" id="<?php echo $id; ?>-apitoken" name="<?php echo $id; ?>[apitoken]" placeholder="<?php echo __('Copy/Paste API Key from 3CX Settings -> Conferencing Settings', '3cx-webinar');?>"class="large-text code" size="70" value="<?php echo esc_attr( $config['apitoken'] ); ?>" data-config-field="<?php echo sprintf( '%s.apitoken', esc_attr( $args['name'] ) ); ?>" />
	</td>
	</tr>	

	<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-cache_expiry"><?php echo esc_html( __( 'Cache Settings', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" maxlength="2" id="<?php echo $id; ?>-cache_expiry" name="<?php echo $id; ?>[cache_expiry]" class="code" size="5" value="<?php echo esc_attr( $config['cache_expiry'] ); ?>" data-config-field="<?php echo sprintf( '%s.cache_expiry', esc_attr( $args['name'] ) ); ?>" />
    <span><?php echo esc_html( __( '1-60 Minutes', '3cx-webinar' ) ); ?></span>
	</td>
	</tr>	  
	
	<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-country"><?php echo esc_html( __( 'Webinar country/language', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<select id="<?php echo $id; ?>-country" name="<?php echo $id; ?>[country]" class="large-text code" style="width:500px" data-config-field="<?php echo sprintf( '%s.country', esc_attr( $args['name'] ) ); ?>">
      <option value='' data-image="<?php echo wp3cxw_plugin_url('images/blank.png');?>">None</option>
		<?php
			foreach(wp3cxw_get_countries() as $code=>$name){?>
			
			<option value="<?php echo $code;?>" <?php if ($code==$config['country']) echo 'selected ';?> data-imagecss="flags <?php echo $code;?>" data-image="<?php echo wp3cxw_plugin_url('images/blank.png');?>" data-title="<?php echo $name;?>"/><?php echo $name;?></option>
		<?php
			} 
		?>	
    </select>		
	</td>
	</tr>			
	
	<tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-maxparticipants"><?php echo esc_html( __( 'Limit subscribers to', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" maxlength="4" id="<?php echo $id; ?>-maxparticipants" name="<?php echo $id; ?>[maxparticipants]" class="code" size="5" value="<?php echo esc_attr( $config['maxparticipants'] ); ?>" data-config-field="<?php echo sprintf( '%s.maxparticipants', esc_attr( $args['name'] ) ); ?>" />
    <span><?php echo esc_html( __( '0 = No Restriction', '3cx-webinar' ) ); ?></span>
	</td>
	</tr>			
</tbody>
</table>
<hr class="tcx-config-separator">
<h2><?php echo esc_html( __( 'Filter which Webinars shall be displayed, leave blank for no filtering.', '3cx-webinar' ) ); ?></h2>
<table class="form-table">
<tbody>

  <tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-extension"><?php echo esc_html( __( '3CX Extension Number', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" maxlength="5" id="<?php echo $id; ?>-extension" name="<?php echo $id; ?>[extension]" class="code" size="5" value="<?php echo esc_attr( $config['extension'] ); ?>" data-config-field="<?php echo sprintf( '%s.extension', esc_attr( $args['name'] ) ); ?>" />
    <span><?php echo esc_html( __( 'Empty = all 3CX Extensions', '3cx-webinar' ) ); ?></span>
	</td>
	</tr>		

  <tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-subject"><?php echo esc_html( __( 'Subject Contains', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" id="<?php echo $id; ?>-subject" name="<?php echo $id; ?>[subject]" class="large-text code" size="70" value="<?php echo esc_attr( $config['subject'] ); ?>" data-config-field="<?php echo sprintf( '%s.subject', esc_attr( $args['name'] ) ); ?>" />
	</td>
	</tr>		

  <tr>
	<th scope="row">
		<label for="<?php echo $id; ?>-days"><?php echo esc_html( __( 'Days from Today onwards', '3cx-webinar' ) ); ?>
	</th>
	<td>
		<input type="text" id="<?php echo $id; ?>-days" name="<?php echo $id; ?>[days]" class="code" size="5" value="<?php echo esc_attr( $config['days'] ); ?>" data-config-field="<?php echo sprintf( '%s.days', esc_attr( $args['name'] ) ); ?>" />
    <span><?php echo esc_html( __( '0 = No Date Restriction', '3cx-webinar' ) ); ?></span>
	</td>
	</tr>		  
  </tbody>
  </table>

<?php

  if (!empty($_REQUEST['apimessage'])){
    $apimessage=$_REQUEST['apimessage'];
    $json = get_transient($apimessage);
    if ($json){
      delete_transient($apimessage);
?>
<hr class="tcx-config-separator">
<h2><?php echo esc_html( __( 'API Request Result', '3cx-webinar' ) ); ?></h2>
<table class="form-table">
<tbody>
<tr><td colspan="2">
  <?php
      echo sprintf( '<textarea id="apimessage" readonly class="large-text code tcxapimessage">%s</textarea>', json_encode($json,JSON_PRETTY_PRINT) );
    ?>
    </td></tr>
<?php      
    }
  }
?>
  </tbody>
  </table>
</div>
<?php
}

