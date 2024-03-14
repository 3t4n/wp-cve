<?php

        // Add Shortcode
function bookwidget( $atts ) {

wp_enqueue_script( 'bed24-widget-script' );
	// Attributes
$availableParams=b24_widget_available_fields();
$shortcodeParams=array();

foreach($availableParams as $k=>$v){
	$lower=strtolower($k);
	$shortcodeParams[$lower]=$v;
}

$atts = shortcode_atts($shortcodeParams,$atts);

$newatts=b24_widget_convert_keys($atts);

ob_start();
$unique_id='b24_widget_'.uniqid();

$b24Attr='{';
foreach($newatts as $k => $v){
	if(strlen($v)>0){
		if($v=='false' || $v=='true'){
			$value=$v;
		}
		else{
			$value='"'.$v.'"';
		}
		$b24Attr.=$k.':'.$value.',';
	}
}
$b24Attr.='}';
?>

<div id="<?php echo $unique_id; ?>"><span style="color:#fff">Booking widget <?php echo $unique_id; ?></span></div>

<!-- jquery widget code -->
<script>
jQuery(document).ready(function() {var b24Attr=<?php echo $b24Attr;?>;jQuery('#<?php echo $unique_id; ?>').bookWidget(b24Attr);});
</script>

<?php
$output=ob_get_clean();
return $output;

}
add_shortcode( 'bookwidget', 'bookwidget' );


/* VISUAL COMPOSER VC_MAP */
add_action( 'vc_before_init', 'bookwidget_vc' );
function bookwidget_vc() {
        $beds_24_vc_params=b24_widget_vc_params();
	$vc_params=array();
	foreach($beds_24_vc_params as $k=>$v){
	$vc_params[]=$v;
	}

   vc_map( array(
      "name" => __( "Beds24 Widget", "beds24" ),
      "base" => "bookwidget",
      "class" => "",
      "category" => __( "Beds24", "beds24"),
      "params" => $vc_params
   ) );
}

/* USEFULL FUNCTIONS */

function b24_widget_convert_keys($atts){
$availableParams=b24_widget_available_fields();
$availKeys=array_keys($availableParams);
$attsKeys=array_keys($atts);
$newparams=array();

foreach($availKeys as $key){
	$lower=strtolower($key);
	$attKey=array_search($lower, $attsKeys);

	if($attKey!==false){
		$newval=str_replace("&quot;","",$atts[$lower]);
		$newparams[$key]=$newval;
	}
}

return $newparams;

}

function b24_widget_available_fields(){
	return array(
			'widgettype' => '',
			'ownerid' => '',
			'propid' => '',
			'roomid' => '',
			'alignment'=>'',
			'availableBackgroundColor' => '',
			'availableColor' => '',
			'backgroundColor' => '',
			'borderColor' => '',
			'boxShadow' => '',
			'buttonBackgroundColor' => '',
			'buttonColor' => '',
			'buttonTitle' => '',
			'color' => '',
			'dateFormat' => '',
			'dateSelection' => '',
			'dayNamesMin' => '',
			'defaultNightsAdvance' => '',
			'defaultNumAdult' => '',
			'defaultNumChild' => '',
			'defaultNumNight' => '',
			'excludeGroup' => '',
			'fontSize' => '',
			'formAction' => '',
			'formTarget' => '',
			'includeGroup' => '',
			'maxAdult' => '',
			'maxChild' => '',
			'numMonth' => '',
			'pastBackgroundColor' => '',
			'pastColor' => '',
			'peopleSelection' => '',
			'referer' => '',
			'requestBackgroundColor' => '',
			'requestColor' => '',
			'redirect' => '',
			'searchShow' => '',
			'searchLinkText' => '',
			'showLabels' => '',
			'weekFirstDay' => '',
			'widgetLang' => '',
			'widgetTitle' => '',
			'widgetType' => '',
			'width' => '',
			'unavailableBackgroundColor' => '',
			'unavailableColor' => '',
			'noExternalFonts' => ''
		);
}

function b24_widget_vc_params(){
	$params=array(
			'widgettype' => array(
				  "type" => "dropdown",
				  "heading" => __( "Widget Type", "beds24" ),
				  "param_name" => "widgettype",
				  "value" => array(
					  'BookingBox'=>'BookingBox',
					  'BookingBoxMini'=>'BookingBoxMini',
					  'BookingStrip'=>'BookingStrip',
					  'AvailabilityCalendar'=>'AvailabilityCalendar',
				  ),
				  "description" => __( "Type of widget.", "beds24" ),
				  'group'=>'Basic'
			),

			'widgettitle' => array(
				  "type" => "textfield",
				  "heading" => __( "Widget title", "beds24" ),
				  "param_name" => "widgettitle",
				  "value" => '',
				  "description" => __( "Title of the widget.", "beds24" ),
				  'group'=>'Basic'
			),
			'propid' => array(
				  "type" => "textfield",
				  "heading" => __( "Property ID", "beds24" ),
				  "param_name" => "propid",
				  "value" => '',
				  "description" => __( "Id number of property. The page will open showing this property", "beds24" ),
				  'group'=>'Basic'
			),
			'roomid' => array(
				  "type" => "textfield",
				  "heading" => __( "Room ID", "beds24" ),
				  "param_name" => "room",
				  "value" => '',
				  "description" => __( "Id number of room. The page will open showing this room", "beds24" ),
				  'group'=>'Basic'
			),
			'ownerid' => array(
				  "type" => "textfield",
				  "heading" => __( "Owner id", "beds24" ),
				  "param_name" => "ownerid",
				  "value" => '',
				  "description" => __( "The page will open showing all properties and rooms for this owner.", "beds24" ),
				  'group'=>'Basic'
			),
			'width' => array(
				  "type" => "dropdown",
				  "heading" => __( "Width", "beds24" ),
				  "param_name" => "width",
				  "value" => array(
					  'auto' => 'auto',
					  '100%' => '100%',
					  '200px' => '200px',
					  '210px' => '210px',
					  '220px' => '220px',
					  '230px' => '230px',
					  '240px' => '240px',
					  '250px' => '250px',
					  '260px' => '260px',
					  '270px' => '270px',
					  '280px' => '280px',
					  '290px' => '290px',
					  '300px' => '300px',
					  '310px' => '310px',
					  '320px' => '320px',
					  '330px' => '330px',
					  '340px' => '340px',
					  '350px' => '350px',
					  '360px' => '360px',
					  '370px' => '370px',
					  '380px' => '380px',
					  '390px' => '390px',
					  '400px' => '400px',
					  '410px' => '410px',
					  '420px' => '420px',
					  '430px' => '430px',
					  '440px' => '440px',
					  '450px' => '450px',
					  '460px' => '460px',
					  '470px' => '470px',
					  '480px' => '480px',
					  '490px' => '490px',
					  '500px' => '500px',
					  '510px' => '510px',
					  '520px' => '520px',
					  '530px' => '530px',
					  '540px' => '540px',
					  '550px' => '550px',
					  '560px' => '560px',
					  '570px' => '570px',
					  '580px' => '580px',
					  '590px' => '590px',
					  '600px' => '600px',
					  '610px' => '610px',
					  '620px' => '620px',
					  '630px' => '630px',
					  '640px' => '640px',
					  '650px' => '650px',
					  '660px' => '660px',
					  '670px' => '670px',
					  '680px' => '680px',
					  '690px' => '690px',
					  '700px' => '700px',
					  '710px' => '710px',
					  '720px' => '720px',
					  '730px' => '730px',
					  '740px' => '740px',
					  '750px' => '750px',
					  '760px' => '760px',
					  '770px' => '770px',
					  '780px' => '780px',
					  '790px' => '790px',
					  '800px' => '800px',
					  '810px' => '810px',
					  '820px' => '820px',
					  '830px' => '830px',
					  '840px' => '840px',
					  '850px' => '850px',
					  '860px' => '860px',
					  '870px' => '870px',
					  '880px' => '880px',
					  '890px' => '890px',
					  '900px' => '900px',
					  '910px' => '910px',
					  '920px' => '920px',
					  '930px' => '930px',
					  '940px' => '940px',
					  '950px' => '950px',
					  '960px' => '960px',
					  '970px' => '970px',
					  '980px' => '980px',
					  '990px' => '990px',
					  '1000px' => '1000px',
					  '1010px' => '1010px',
					  '1020px' => '1020px',
					  '1030px' => '1030px',
					  '1040px' => '1040px',
					  '1050px' => '1050px',
					  '1060px' => '1060px',
					  '1070px' => '1070px',
					  '1080px' => '1080px',
					  '1090px' => '1090px',
					  '1100px' => '1100px',
					  '1110px' => '1110px',
					  '1120px' => '1120px',
					  '1130px' => '1130px',
					  '1140px' => '1140px',
					  '1150px' => '1150px',
					  '1160px' => '1160px',
					  '1170px' => '1170px',
					  '1180px' => '1180px',
					  '1190px' => '1190px',
					  '1200px' => '1200px',
				  ),
				  "description" => __( "Width of the widget.", "beds24" ),
				  'group'=>'Layout'
			),
			'alignment'=>array(
				  "type" => "dropdown",
				  "heading" => __( "Alignment", "beds24" ),
				  "param_name" => "alignment",
				  "value" => array(
					  'Left'=>'left',
					  'Right'=>'right',
					  'Center'=>'center',
				  ),
				  "description" => __( "Alignment of the widget content.", "beds24" ),
				  'group'=>'Layout'
			),
			'fontsize' => array(
				  "type" => "dropdown",
				  "heading" => __( "Font size", "beds24" ),
				  "param_name" => "fontsize",
				  "value" => array(
					  '8px',
					  '9px',
					  '10px',
					  '11px',
					  '12px',
					  '13px',
					  '14px',
					  '15px',
					  '16px',
					  '17px',
					  '18px',
					  '19px',
					  '20px',
					  '21px',
					  '22px',
					  '23px',
					  '24px',
				  ),
				  "description" => __( "Text size.", "beds24" ),
				  'group'=>'Style'
			),
			'backgroundcolor' =>array(
				  "type" => "colorpicker",
				  "heading" => __( "Background color", "beds24" ),
				  "param_name" => "backgroundcolor",
				  "value" => '',
				  "description" => __( "Widget background color.", "beds24" ),
				  'group'=>'Style'
			),
			'bordercolor' => array(
				  "type" => "colorpicker",
				  "heading" => __( "Border color", "beds24" ),
				  "param_name" => "bordercolor",
				  "value" => '',
				  "description" => __( "Widget border color.", "beds24" ),
				  'group'=>'Style'
			),
			'boxshadow' => array(
				  "type" => "checkbox",
				  "heading" => __( "Box shadow", "beds24" ),
				  "param_name" => "boxshadow",
				  "value" => 'true',
				  "description" => __( "Shadow around the widget.", "beds24" ),
				  'group'=>'Layout'
			),
			'buttonbackgroundcolor' => array(
				  "type" => "colorpicker",
				  "heading" => __( "Button background color", "beds24" ),
				  "param_name" => "buttonbackgroundcolor",
				  "value" => '',
				  "description" => __( "Button background color.", "beds24" ),
				  'group'=>'Style'
			),
			'buttoncolor' => array(
				  "type" => "colorpicker",
				  "heading" => __( "Button text color", "beds24" ),
				  "param_name" => "buttoncolor",
				  "value" => '',
				  "description" => __( "Button text color.", "beds24" ),
				  'group'=>'Style'
			),
			'buttontitle' => array(
				  "type" => "textfield",
				  "heading" => __( "Button title", "beds24" ),
				  "param_name" => "buttontitle",
				  "value" => '',
				  "description" => __( "Button text ", "beds24" ),
				  'group'=>'Layout'
			),
			'color' => array(
				  "type" => "colorpicker",
				  "heading" => __( "Button text color", "beds24" ),
				  "param_name" => "color",
				  "value" => '',
				  "description" => __( "Text color.", "beds24" ),
				  'group'=>'Style'
			),
			'showlabels' =>  array(
				  "type" => "checkbox",
				  "heading" => __( "Show labels", "beds24" ),
				  "param_name" => "showlabels",
				  "value" => 'true',
				  "description" => __( "Show form labels.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'dateformat' => array(
				  "type" => "dropdown",
				  "heading" => __( "Date format", "beds24" ),
				  "param_name" => "dateformat",
				  "value" => array(
					  'dd.mm.y'=>'dd.mm.y',
					  'dd.mm.yy'=>'dd.mm.yy',
					  'dd/mm/y'=>'dd/mm/y',
					  'dd/mm/yy'=>'dd/mm/yy',
					  'mm/dd/y'=>'mm/dd/y',
					  'mm/dd/yy'=>'mm/dd/yy',
					  'mmm/dd/yy'=>'mmm/dd/yy',
					  'd mm yy'=>'d mm yy',
					  'M D Y'=>'M D Y',
					  'M D, Y'=>'M D, Y',
					  'MM D YY'=>'MM D YY',
					  'MM D, YY'=>'MM D, YY',
				  ),
				  "description" => __( "Formate of displayed dates.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'dateselection' => array(
				  "type" => "dropdown",
				  "heading" => __( "Date selection", "beds24" ),
				  "param_name" => "dateselection",
				  "value" => array(
					  'check-in only'=>'0',
					  'check in and check out'=>'1',
					  'check in and number of nights'=>'2',
					  'check in and check out and number of nights '=>'3',
				  ),
				  "description" => __( "Type of date selection.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'daynamesmin' => array(
				  "type" => "textfield",
				  "heading" => __( "Day Names Min", "beds24" ),
				  "param_name" => "daynamesmin",
				  "value" => '["SO", "MO", "DI", "MI", "DO", "FR", "SA"]',
				  "description" => __( "headings for the days of week ", "beds24" ),
				  'group'=>'Form Elements'
			),

			'defaultnightsadvance' => array(
				  "type" => "dropdown",
				  "heading" => __( "Default nights advance ", "beds24" ),
				  "param_name" => "defaultnightsadvance",
				  "value" => array(
					  ''=>'0',
					  '1'=>'1',
					  '2'=>'2',
					  '3'=>'3',
					  '4'=>'4',
					  '5'=>'5',
					  '6'=>'6',
					  '7'=>'7',
					  '8'=>'8',
					  '9'=>'9',
					  '10'=>'10',
					  '11'=>'11',
					  '12'=>'12',
					  '13'=>'13',
					  '14'=>'14',
					  '15'=>'15',
					  '16'=>'16',
					  '17'=>'17',
					  '18'=>'18',
					  '19'=>'19',
					  '20'=>'20',
				  ),
				  "description" => __( "How many days in advance are shown.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'defaultnumnight' => array(
				  "type" => "dropdown",
				  "heading" => __( "Default num night", "beds24" ),
				  "param_name" => "defaultnumnight",
				  "value" => array(
					  ''=>'0',
					  '1'=>'1',
					  '2'=>'2',
					  '3'=>'3',
					  '4'=>'4',
					  '5'=>'5',
					  '6'=>'6',
					  '7'=>'7',
					  '8'=>'8',
					  '9'=>'9',
					  '10'=>'10',
					  '11'=>'11',
					  '12'=>'12',
					  '13'=>'13',
					  '14'=>'14',
					  '15'=>'15',
					  '16'=>'16',
					  '17'=>'17',
					  '18'=>'18',
					  '19'=>'19',
					  '20'=>'20',
				  ),
				  "description" => __( "Preselection for the number ob booked nights.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'formaction' => array(
				  "type" => "textfield",
				  "heading" => __( "Form action", "beds24" ),
				  "param_name" => "formaction",
				  "value" => '',
				  "description" => __( "Text for the action parameter of the form.", "beds24" ),
				  'group'=>'Basic'
			),
			'formtarget' => array(
				  "type" => "dropdown",
				  "heading" => __( "Form target", "beds24" ),
				  "param_name" => "formtarget",
				  "value" => array(
					  '_self',
					  '_blank',
					  '_parent',
					  '_top'
				  ),
				  "description" => __( "Text size.", "beds24" ),
				  'group'=>'Basic'
			),
			'excludegroup' => array(
				  "type" => "textfield",
				  "heading" => __( "Exclude group", "beds24" ),
				  "param_name" => "excludegroup",
				  "value" => '',
				  "description" => __( "Group of properties not to include.", "beds24" ),
				  'group'=>'Basic'
			),

			'includegroup' => array(
				  "type" => "textfield",
				  "heading" => __( "Include group", "beds24" ),
				  "param_name" => "includegroup",
				  "value" => '',
				  "description" => __( "Group of properties to include.", "beds24" ),
				  'group'=>'Basic'
			),
			'defaultnumadult' => array(
				  "type" => "dropdown",
				  "heading" => __( "Default num adult", "beds24" ),
				  "param_name" => "defaultnumadult",
				  "value" => array(
					  '0',
					  '1',
					  '2',
					  '3',
					  '4',
					  '5',
					  '6',
					  '7',
					  '8',
					  '9',
					  '10',
					  '11',
					  '12',
					  '13',
					  '14',
					  '15',
					  '16',
					  '17',
					  '18',
					  '19',
					  '20',
					  '21',
					  '22',
					  '23',
					  '24',
					  '25',
					  '26',
					  '27',
					  '28',
					  '29',
					  '30',
				  ),
				  "description" => __( "Default number of adults.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'defaultnumchild' => array(
				  "type" => "dropdown",
				  "heading" => __( "Default num child", "beds24" ),
				  "param_name" => "defaultnumchild",
				  "value" => array(
					  '0',
					  '1',
					  '2',
					  '3',
					  '4',
					  '5',
					  '6',
					  '7',
					  '8',
					  '9',
					  '10',
					  '11',
					  '12',
					  '13',
					  '14',
					  '15',
					  '16',
					  '17',
					  '18',
					  '19',
					  '20',
					  '21',
					  '22',
					  '23',
					  '24',
					  '25',
					  '26',
					  '27',
					  '28',
					  '29',
					  '30',
				  ),
				  "description" => __( "Default number of children.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'maxadult' => array(
				  "type" => "dropdown",
				  "heading" => __( "Max adults", "beds24" ),
				  "param_name" => "maxadult",
				  "value" => array(
					  '0',
					  '1',
					  '2',
					  '3',
					  '4',
					  '5',
					  '6',
					  '7',
					  '8',
					  '9',
					  '10',
					  '11',
					  '12',
					  '13',
					  '14',
					  '15',
					  '16',
					  '17',
					  '18',
					  '19',
					  '20',
					  '21',
					  '22',
					  '23',
					  '24',
					  '25',
					  '26',
					  '27',
					  '28',
					  '29',
					  '30',
					  '31',
					  '32',
					  '33',
					  '34',
					  '35',
					  '36',
					  '37',
					  '38',
					  '39',
					  '40',
					  '41',
					  '42',
					  '43',
					  '44',
					  '45',
					  '46',
					  '47',
					  '48',
					  '49',
					  '50',
				  ),
				  "description" => __( "Maximum number of adults.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'maxchild' => array(
				  "type" => "dropdown",
				  "heading" => __( "Max children", "beds24" ),
				  "param_name" => "maxchild",
				  "value" => array(
					  '0',
					  '1',
					  '2',
					  '3',
					  '4',
					  '5',
					  '6',
					  '7',
					  '8',
					  '9',
					  '10',
					  '11',
					  '12',
					  '13',
					  '14',
					  '15',
					  '16',
					  '17',
					  '18',
					  '19',
					  '20',
					  '21',
					  '22',
					  '23',
					  '24',
					  '25',
					  '26',
					  '27',
					  '28',
					  '29',
					  '30',
					  '31',
					  '32',
					  '33',
					  '34',
					  '35',
					  '36',
					  '37',
					  '38',
					  '39',
					  '40',
					  '41',
					  '42',
					  '43',
					  '44',
					  '45',
					  '46',
					  '47',
					  '48',
					  '49',
					  '50',
				  ),
				  "description" => __( "Maximum number of children.", "beds24" ),
				  'group'=>'Form Elements'
			),			
			'peopleselection' => array(
				  "type" => "dropdown",
				  "heading" => __( "People selection", "beds24" ),
				  "param_name" => "peopleselection",
				  "value" => array(
					  'none' => '0',
					  'Guests' => '1',
					  'Adults and Children' => '2',

				  ),
				  "description" => __( "Selection number of guests.", "beds24" ),
				  'group'=>'Form Elements'
			),
			'referer' => array(
				  "type" => "textfield",
				  "heading" => __( "Referer", "beds24" ),
				  "param_name" => "referer",
				  "value" => '',
				  "description" => __( "This text will be recorded with any bookings originating from this widget allowing tracking of booking sources.", "beds24" ),
				  'group'=>'Basic'
			),
			'redirect' => array(
				  "type" => "textfield",
				  "heading" => __( "Redirect", "beds24" ),
				  "param_name" => "redirect",
				  "value" => '',
				  "description" => __( "Text for the redirect parameter of the form.", "beds24" ),
				  'group'=>'Basic'
			),
			'weekfirstday' => array(
				  "type" => "dropdown",
				  "heading" => __( "Week first day", "beds24" ),
				  "param_name" => "weekfirstday",
				  "value" => array(
					  'Sunday' => '0',
					  'Monday' => '1',
					  'Tuesday' => '2',
					  'Wedneday' => '3',
					  'Thursday' => '4',
					  'Friday' => '5',
					  'Saturday' => '6',

				  ),
				  "description" => __( "First day of the week.", "beds24" ),
				  'group'=>'Basic'
			),


			'availablebackgroundcolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Available background color", "beds24" ),
				  "param_name" => "availablebackgroundcolor",
				  "value" => '',
				  "description" => __( "Background color of available dates.", "beds24" ),
				  'group'=>'Basic',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('true'),
				  		)
			),
			'availablecolor' =>   array(
				  "type" => "colorpicker",
				  "heading" => __( "Available color", "beds24" ),
				  "param_name" => "availablecolor",
				  "value" => '',
				  "description" => __( "Text color of available dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('true'),
				  		)
			),
			'nummonth' =>  array(
				  "type" => "dropdown",
				  "heading" => __( "Num month", "beds24" ),
				  "param_name" => "nummonth",
				  "value" => array(
					  '1',
					  '2',
					  '3',
					  '4',
					  '5',
					  '6',
					  '7',
					  '8',
					  '9',
					  '10',
					  '11',
					  '12',
				  ),
				  "description" => __( "Number of calendars.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'pastbackgroundcolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Past background color", "beds24" ),
				  "param_name" => "pastbackgroundcolor",
				  "value" => '',
				  "description" => __( "Background color of past dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'pastcolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Past color", "beds24" ),
				  "param_name" => "pastcolor",
				  "value" => '',
				  "description" => __( "Text color of past dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'requestbackgroundcolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Request background color", "beds24" ),
				  "param_name" => "requestbackgroundcolor",
				  "value" => '',
				  "description" => __( "Background color of request dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'requestcolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Request color", "beds24" ),
				  "param_name" => "requestcolor",
				  "value" => '',
				  "description" => __( "Text color of request dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'unavailablebackgroundcolor' => array(
				  "type" => "colorpicker",
				  "heading" => __( "Unavailable background color", "beds24" ),
				  "param_name" => "unavailablebackgroundcolor",
				  "value" => '',
				  "description" => __( "Background color of unavailable dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'unavailablecolor' =>  array(
				  "type" => "colorpicker",
				  "heading" => __( "Unavailable color", "beds24" ),
				  "param_name" => "unavailablecolor",
				  "value" => '',
				  "description" => __( "Text color of unavailable dates.", "beds24" ),
				  'group'=>'Calendar',
				  'dependency'=>array(
				  		'element'=>'widgettype',
				  		'value'=>array('AvailabilityCalendar'),
				  		)
			),
			'searchshow' => array(
				  "type" => "checkbox",
				  "heading" => __( "Search show", "beds24" ),
				  "param_name" => "searchshow",
				  "value" => "true",
				  "description" => __( "Seach criteria open with link or are always visible.", "beds24" ),
				  'group'=>'Multiple Property Search',
				  'dependency'=>array(
				  		'element'=>'propid',
				  		'value'=>array(''),
				  		)

			),
			'searchlinktext' => array(
				  "type" => "textfield",
				  "heading" => __( "Search link text", "beds24" ),
				  "param_name" => "searchlinktext",
				  "value" => '',
				  "description" => __( "Text of the link which opens the search criteria.", "beds24" ),
				  'group'=>'Multiple Property Search',
				  'dependency'=>array(
				  		'element'=>'propid',
				  		'value'=>array(''),
				  		)
			),
			'widgetlang' => array(
				  "type" => "textfield",
				  "heading" => __( "Widget language", "beds24" ),
				  "param_name" => "widgetlang",
				  "value" => '',
				  "description" => __( "Language of the widget.", "beds24" ),
				  'group'=>'Basic'
			),

		);


	return $params;
}


function b24_no_tex( $shortcodes ) {
    $shortcodes[] = 'bookwidget';
    return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'b24_no_tex' );

?>
