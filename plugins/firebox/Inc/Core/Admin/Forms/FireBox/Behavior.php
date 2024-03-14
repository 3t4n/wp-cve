<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin\Forms\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FireBox\Core\FB\Actions\Sounds;

class Behavior
{
	/**
	 * Holds the Behavior Settings
	 * 
	 * @return  array
	 */
	public function getSettings()
	{
		// Sounds
        $sounds = array_merge([
			'none' => 'FPF_NONE',
			'custom_file' => 'FPF_CUSTOM_SOUND_FILE',
			'custom_url' => 'FPF_CUSTOM_SOUND_URL'
		], Sounds::get());
		
		// Trigger
		global $pagenow;
		$box = $pagenow != 'post-new.php' ? get_the_ID() : 'X';
		$trigger_method_alternative_method = sprintf(firebox()->_('FB_METABOX_TRIGGER_ELEMENT_ALTERNATIVE_METHOD_DESC'), $box);
		
		$settings = [
			'title' => 'FPF_BEHAVIOR',
			'content' => [
				'base' => [
					'title' => [
						'title' => 'FPF_GENERAL',
						'description' => firebox()->_('FB_METABOX_GENERAL_DESC')
					],
					'fields' => [
						[
							'name' => 'position',
							'type' => 'Dropdown',
							'label' => firebox()->_('FB_METABOX_POSITION'),
							'description' => firebox()->_('FB_METABOX_POSITION_DESC'),
							'default' => 'center',
							'input_class' => ['medium'],
							'choices' => [
								'top-left' => firebox()->_('FB_METABOX_POSITION_TL'),
								'top-center' => firebox()->_('FB_METABOX_POSITION_TC'),
								'top-right' => firebox()->_('FB_METABOX_POSITION_TR'),
								'middle-left' => firebox()->_('FB_METABOX_POSITION_ML'),
								'center' => firebox()->_('FB_METABOX_POSITION_MC'),
								'middle-right' => firebox()->_('FB_METABOX_POSITION_MR'),
								'bottom-left' => firebox()->_('FB_METABOX_POSITION_BL'),
								'bottom-center' => firebox()->_('FB_METABOX_POSITION_BC'),
								'bottom-right' => firebox()->_('FB_METABOX_POSITION_BR')
							],
							'class' => ['large-auto']
						],
						[
							'name' => 'mode',
							'type' => 'Toggle',
							'label' => firebox()->_('FB_CAMPAIGN_MODE'),
							'description' => firebox()->_('FB_CAMPAIGN_MODE_DESC'),
							'default' => 'popup',
							'choices' => [
								'popup' => firebox()->_('FB_CLASSIC_POPUP'),
								'pageslide' => firebox()->_('FB_PAGESLIDE'),
							],
							'class' => ['large-auto']
						]
					]
				],
				'behavior' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_TRIGGER_METHOD'),
						'description' => firebox()->_('FB_METABOX_BEHAVIOR_DESC')
					],
					'fields' => [
						[
							'type' => 'CustomDiv',
							'class' => ['cell']
						],
						[
							'name' => 'triggermethod',
							'type' => 'ChoiceSelector',
							'default' => 'pageload',
							'plugin' => 'FireBox',
							'mode' => 'svg',
							'choices' => [
								'pageload' => [
									'icon' => '<svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="11" width="2" height="8" rx="1" fill="black"/><rect opacity="0.8" x="11" y="16" width="2" height="8" rx="1" fill="black"/><rect x="24" y="11" width="2" height="8" rx="1" transform="rotate(90 24 11)" fill="black"/><rect x="8" y="11" width="2" height="8" rx="1" transform="rotate(90 8 11)" fill="black"/><rect x="19.7781" y="2.80762" width="2" height="6.51985" rx="1" transform="rotate(45 19.7781 2.80762)" fill="black"/><rect x="8.46436" y="14.1213" width="2" height="8" rx="1" transform="rotate(45 8.46436 14.1213)" fill="black"/><rect x="21.1924" y="19.7782" width="2" height="8" rx="1" transform="rotate(135 21.1924 19.7782)" fill="black"/><rect x="9.87866" y="8.46448" width="2" height="8" rx="1" transform="rotate(135 9.87866 8.46448)" fill="black"/><circle cx="19" cy="5" r="5" fill="black"/><path d="M17.3562 4.79308L18.4567 6.40598L21.4816 3.68337" stroke="white" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_PL'),
								],
								'pageready' => [
									'icon' => '<svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="11" width="2" height="8" rx="1" fill="black"/><rect opacity="0.8" x="11" y="16" width="2" height="8" rx="1" fill="black"/><rect opacity="0.4" x="24" y="11" width="2" height="8" rx="1" transform="rotate(90 24 11)" fill="#333333"/><rect x="8" y="11" width="2" height="8" rx="1" transform="rotate(90 8 11)" fill="black"/><rect opacity="0.2" x="19.7781" y="2.80762" width="2" height="8" rx="1" transform="rotate(45 19.7781 2.80762)" fill="black"/><rect x="8.46436" y="14.1213" width="2" height="8" rx="1" transform="rotate(45 8.46436 14.1213)" fill="black"/><rect opacity="0.6" x="21.1924" y="19.7782" width="2" height="8" rx="1" transform="rotate(135 21.1924 19.7782)" fill="black"/><rect x="9.87866" y="8.46448" width="2" height="8" rx="1" transform="rotate(135 9.87866 8.46448)" fill="black"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_PR'),
								],
								
								'onclick' => [
									'icon' => '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.1976 13.8946C10.8711 13.0783 11.6812 12.2682 12.4975 12.5948L22.4201 16.5638C23.2189 16.8833 23.2681 17.9955 22.5006 18.3843L19.1332 20.0903C18.9434 20.1864 18.7893 20.3406 18.6931 20.5304L16.9872 23.8978C16.5984 24.6653 15.4862 24.6161 15.1666 23.8172L11.1976 13.8946Z" fill="black"/><rect x="15.9879" y="18.7993" width="2" height="9" rx="1" transform="rotate(-45 15.9879 18.7993)" fill="black"/><path d="M18.7194 7.94446C18.2908 6.669 17.5685 5.47052 16.5525 4.45452C13.0378 0.939802 7.33931 0.939802 3.82459 4.45452C0.309875 7.96924 0.309875 13.6677 3.82459 17.1824C4.84059 18.1984 6.03907 18.9207 7.31453 19.3494" stroke="black" stroke-width="2" stroke-linecap="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_OC'),
								],
								
								'elementHover' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2752 23H17.5C20.2614 23 22.5 20.7614 22.5 18V13.5479C22.5 12.8916 22.178 12.2771 21.6384 11.9035L17.8415 9.27488C16.7751 8.53662 15.3334 8.66663 14.4163 9.58373C14.0781 9.92186 13.5 9.68238 13.5 9.2042V2.75C13.5 1.7835 12.7165 1 11.75 1C10.7835 1 10 1.7835 10 2.75V13.7943C10 14.8508 8.93297 15.5732 7.95204 15.1808L6.34114 14.5365C5.56821 14.2273 4.68514 14.5248 4.25683 15.2386C3.82257 15.9624 3.98659 16.8954 4.64168 17.4276L10.1222 21.8806C11.0135 22.6047 12.1269 23 13.2752 23Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><mask id="path-2-inside-1" fill="white"><rect y="1" width="11" height="9" rx="1"/></mask><rect y="1" width="11" height="9" rx="1" stroke="black" stroke-width="4" mask="url(#path-2-inside-1)"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_EH')
								],
								
								'ondemand' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 3L10.0589 20.7444" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 17L1 12L6 7" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 7L23 12L18 17" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_OD')
								],
								
								'pageheight_free' => [
									'icon' => '<svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="1" width="15" height="22" rx="7" stroke="black" stroke-width="2"/><line x1="12.5" y1="2.18557e-08" x2="12.5" y2="10" stroke="black"/><line x1="12.5" y1="7.5" x2="12.5" y2="10.5" stroke="black" stroke-width="3" stroke-linecap="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_PH'),
									'pro' => true
								],
								'element_free' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5146 12.438C21.6205 13.1524 20.0939 14.2866 18.3231 15.2484C16.4824 16.2482 14.4888 17 12.7164 17H12.7144H12.7124H12.7104H12.7083H12.7063H12.7043H12.7023H12.7002H12.6982H12.6962H12.6941H12.6921H12.6901H12.688H12.686H12.6839H12.6819H12.6798H12.6778H12.6757H12.6737H12.6716H12.6696H12.6675H12.6654H12.6634H12.6613H12.6592H12.6572H12.6551H12.653H12.6509H12.6489H12.6468H12.6447H12.6426H12.6405H12.6384H12.6363H12.6342H12.6321H12.63H12.6279H12.6258H12.6237H12.6216H12.6195H12.6174H12.6153H12.6132H12.6111H12.609H12.6068H12.6047H12.6026H12.6005H12.5983H12.5962H12.5941H12.592H12.5898H12.5877H12.5855H12.5834H12.5813H12.5791H12.577H12.5748H12.5727H12.5705H12.5684H12.5662H12.5641H12.5619H12.5597H12.5576H12.5554H12.5532H12.5511H12.5489H12.5467H12.5446H12.5424H12.5402H12.538H12.5359H12.5337H12.5315H12.5293H12.5271H12.5249H12.5227H12.5205H12.5183H12.5162H12.514H12.5118H12.5096H12.5073H12.5051H12.5029H12.5007H12.4985H12.4963H12.4941H12.4919H12.4897H12.4874H12.4852H12.483H12.4808H12.4786H12.4763H12.4741H12.4719H12.4696H12.4674H12.4652H12.4629H12.4607H12.4585H12.4562H12.454H12.4517H12.4495H12.4472H12.445H12.4427H12.4405H12.4382H12.436H12.4337H12.4314H12.4292H12.4269H12.4247H12.4224H12.4201H12.4179H12.4156H12.4133H12.411H12.4088H12.4065H12.4042H12.4019H12.3996H12.3974H12.3951H12.3928H12.3905H12.3882H12.3859H12.3836H12.3813H12.379H12.3768H12.3745H12.3722H12.3699H12.3676H12.3653H12.3629H12.3606H12.3583H12.356H12.3537H12.3514H12.3491H12.3468H12.3445H12.3421H12.3398H12.3375H12.3352H12.3329H12.3305H12.3282H12.3259H12.3235H12.3212H12.3189H12.3165H12.3142H12.3119H12.3095H12.3072H12.3049H12.3025H12.3002H12.2978H12.2955H12.2931H12.2908H12.2884H12.2861H12.2837H12.2814H12.279H12.2767H12.2743H12.272H12.2696H12.2672H12.2649H12.2625H12.2602H12.2578H12.2554H12.253H12.2507H12.2483H12.2459H12.2436H12.2412H12.2388H12.2364H12.2341H12.2317H12.2293H12.2269H12.2245H12.2221H12.2198H12.2174H12.215H12.2126H12.2102H12.2078H12.2054H12.203H12.2006H12.1982H12.1958H12.1934H12.191H12.1886H12.1862H12.1838H12.1814H12.179H12.1766H12.1742H12.1718H12.1694H12.167H12.1646H12.1622H12.1597H12.1573H12.1549H12.1525H12.1501H12.1477H12.1452H12.1428H12.1404H12.138H12.1355H12.1331H12.1307H12.1283H12.1258H12.1234H12.121H12.1185H12.1161H12.1137H12.1112H12.1088H12.1064H12.1039H12.1015H12.099H12.0966H12.0942H12.0917H12.0893H12.0868H12.0844H12.0819H12.0795H12.077H12.0746H12.0721H12.0697H12.0672H12.0648H12.0623H12.0599H12.0574H12.055H12.0525H12.05H12.0476H12.0451H12.0427H12.0402H12.0377H12.0353H12.0328H12.0303H12.0279H12.0254H12.0229H12.0205H12.018H12.0155H12.0131H12.0106H12.0081H12.0056H12.0032H12.0007H11.9982H11.9957H11.9932H11.9908H11.9883H11.9858H11.9833H11.9808H11.9784H11.9759H11.9734H11.9709H11.9684H11.9659H11.9634H11.961H11.9585H11.956H11.9535H11.951H11.9485H11.946H11.9435H11.941H11.9385H11.936H11.9335H11.931H11.9285H11.926H11.9235H11.921H11.9185H11.916H11.9135H11.911H11.9085H11.906H11.9035H11.901H11.8985H11.896H11.8935H11.891H11.8885H11.886H11.8835H11.881H11.8785H11.8759H11.8734H11.8709H11.8684H11.8659H11.8634H11.8609H11.8583H11.8558H11.8533H11.8508H11.8483H11.8458H11.8432H11.8407H11.8382H11.8357H11.8332H11.8306H11.8281H11.8256H11.8231H11.8205H11.818H11.8155H11.813H11.8105H11.8079H11.8054H11.8029H11.8003H11.7978H11.7953H11.7928H11.7902H11.7877H11.7852H11.7826H11.7801H11.7776H11.775H11.7725H11.77H11.7675H11.7649H11.7624H11.7598H11.7573H11.7548H11.7522H11.7497H11.7472H11.7446H11.7421H11.7396H11.737H11.7345H11.7319H11.7294H11.7269H11.7243H11.7218H11.7192H11.7167H11.7142H11.7116H11.7091H11.7065H11.704H11.7015H11.6989H11.6964H11.6938H11.6913H11.6887H11.6862H11.6837H11.6811H11.6786H11.676H11.6735H11.6709H11.6684H11.6658H11.6633H11.6607H11.6582H11.6556H11.6531H11.6506H11.648H11.6455H11.6429H11.6404H11.6378H11.6353H11.6327H11.6302H11.6276H11.6251H11.6225H11.62H11.6174H11.6149H11.6123H11.6098H11.6072H11.6047H11.6021H11.5996H11.597H11.5945H11.5919H11.5894H11.5868H11.5842H11.5817H11.5791H11.5766H11.574H11.5715H11.5689H11.5664H11.5638H11.5613H11.5587H11.5562H11.5536H11.5511H11.5485H11.546H11.5434H11.5409H11.5383H11.5357H11.5332H11.5306H11.5281H11.5255H11.523H11.5204H11.5179H11.5153H11.5128H11.5102H11.5077H11.5051H11.5026H11.5C9.68954 17 7.65462 16.2464 5.7785 15.2473C3.97334 14.286 2.41564 13.1524 1.50128 12.4366C2.39711 11.6032 3.96014 10.2485 5.77953 9.09527C7.67629 7.89297 9.71258 7 11.5 7H12.1662H12.8435C14.5248 7 16.4842 7.88571 18.3342 9.09285C20.1054 10.2485 21.6375 11.6058 22.5146 12.438Z" stroke="black" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="2" stroke="black" stroke-width="2"/><rect x="11" width="2" height="4" rx="1" fill="black"/><rect x="17.134" y="0.767944" width="2" height="4" rx="1" transform="rotate(30 17.134 0.767944)" fill="black"/><rect x="5.13379" y="1.76794" width="2" height="4" rx="1" transform="rotate(-30 5.13379 1.76794)" fill="black"/><line x1="1" y1="23" x2="23" y2="23" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_EL'),
									'pro' => true
								],
								'userleave_free' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12L21 12" stroke="black" stroke-width="2" stroke-linecap="round"/><path d="M20 14L22 12L20 10" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 18V21C18 22.1046 17.1046 23 16 23H3C1.89543 23 1 22.1046 1 21V3C1 1.89543 1.89543 1 3 1H16C17.1046 1 18 1.89543 18 3V5.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_UL'),
									'pro' => true
								],
								'onexternallink_free' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 2H22V8" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="20.5" y1="3.41421" x2="7.06497" y2="16.8492" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 5H1V23H19V14" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_ELC'),
									'pro' => true
								],
								'onAdBlockDetect_free' => [
									'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.62925 1.68892L16.315 1.68892L22.4567 7.83064L22.4567 16.5163L16.315 22.6581L7.62925 22.6581L1.48753 16.5163L1.48753 7.83064L7.62925 1.68892Z" stroke="black" stroke-width="2"/><path d="M17.1271 15.3091V15.0366C17.1271 14.6591 17.1421 14.2964 17.1271 13.9561V9.52054C17.1271 9.29745 17.0359 9.09458 16.8888 8.94739C16.7419 8.80051 16.539 8.70901 16.316 8.70901C15.832 8.70901 15.4364 9.10498 15.4364 9.58856V11.8547C15.2863 11.8117 15.1228 11.7723 14.9453 11.7375V7.10006C14.9453 6.86755 14.8503 6.65622 14.6969 6.50283C14.5438 6.34977 14.3325 6.25471 14.1 6.25471C13.635 6.25471 13.2546 6.63505 13.2546 7.10006V11.5607C13.0974 11.5548 12.9339 11.5506 12.7636 11.5483V5.85221C12.7636 5.38359 12.3799 5 11.9114 5H11.8977C11.4441 5 11.0725 5.37122 11.0725 5.82517V11.6362C10.9012 11.6606 10.7377 11.6906 10.5818 11.7251V6.49987C10.5818 6.03487 10.2014 5.65452 9.73642 5.65452H9.70905C9.259 5.65452 8.8907 6.02282 8.8907 6.47287V12.0364C8.8907 12.0364 8.99982 14.7637 8.1271 13.4002C8.0913 13.3439 8.05904 13.2898 8.03072 13.2381L8.01277 13.2138C7.92937 13.1013 7.84052 12.9815 7.74544 12.8548C6.9271 11.7639 6 12.4184 6 13.1274C6 13.7448 7.77767 16.0154 8.23746 16.5928C9.00632 17.7394 10.5977 18.5274 12.4363 18.5274C14.7838 18.5274 16.7292 17.2441 17.0734 15.5684C17.1076 15.4886 17.1271 15.4013 17.1271 15.3091Z" fill="black"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_ONADBLOCKDETECT'),
									'pro' => true
								],
								'onIdle_free' => [
									'icon' => '<svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="11" stroke="black" stroke-width="2"/><path d="M12 7V12L17 17" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
									'label' => firebox()->_('FB_METABOX_TRIGGER_METHOD_ONIDLE'),
									'pro' => true
								],
								'floatingbutton_free' => [
									'icon' => '<svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 18H3C1.89543 18 1 17.1046 1 16V8C1 6.89543 1.89543 6 3 6H23C24.1046 6 25 6.89543 25 8V9V12" stroke="black" stroke-width="2"/><rect x="7" y="11" width="12" height="2" fill="black"/><path d="M15.9769 16.2768C15.6504 15.4605 16.4605 14.6504 17.2768 14.9769L23.4251 17.4363C24.2239 17.7558 24.2731 18.868 23.5056 19.2568L21.6414 20.2012C21.4516 20.2974 21.2974 20.4516 21.2012 20.6414L20.2568 22.5057C19.868 23.2731 18.7558 23.2239 18.4363 22.4251L15.9769 16.2768Z" fill="black"/><rect x="19.2578" y="19.2944" width="1.46623" height="6.59805" rx="0.733117" transform="rotate(-45 19.2578 19.2944)" fill="black"/></svg>',
									'label' => firebox()->_('FB_FLOATING_BUTTON'),
									'pro' => true
								],
								
							]
						],
						[
							'type' => 'CustomDiv',
							'position' => 'end'
						],
						[
							'name' => 'triggerelement',
							'type' => 'Text',
							'label' => firebox()->_('FB_METABOX_TRIGGER_ELEMENT'),
							'description' => firebox()->_('FB_METABOX_TRIGGER_ELEMENT_DESC'),
							'placeholder' => '#comments',
							'input_class' => ['large'],
							'showon' => '[triggermethod]:element,elementHover,onclick'
						],
						[
							'type' => 'Label',
							'text' => $trigger_method_alternative_method,
							'class' => ['margin-top-0', 'fpf-field-descrption-text'],
							'showon' => '[triggermethod]:element,elementHover,onclick'
						],
						[
							'name' => 'preventdefault',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_METABOX_PREVENTDEFAULT'),
							'description' => firebox()->_('FB_METABOX_PREVENTDEFAULT_DESC'),
							'checked' => true,
							'showon' => '[triggermethod]:onclick'
						],
						
						[
							'name' => 'triggerdelay',
							'type' => 'Slider',
							'label' => firebox()->_('FB_METABOX_TRIGGER_DELAY'),
							
							'description' => firebox()->_('FB_METABOX_TRIGGER_DELAY_DESC_FREE'),
							
							
							'default' => 0,
							'min' => 0,
							'max' => 120,
							'step' => 2,
							'addon' => 'sec',
							'showon' => '[triggermethod]:pageload,pageready,pageheight,element,onclick,elementHover'
						],
						
						[
							'type' => 'Heading',
							'heading_type' => 'h4',
							'description_class' => ['bottom'],
							'title' => firebox()->_('FB_FLOATING_BUTTON'),
							'description' => firebox()->_('FB_FLOATING_BUTTON_SECTION_DESC'),
							'showon' => '[triggermethod]!:onexternallink,floatingbutton'
						],
						
						
						[
							'type' => 'Pro',
							'plugin' => 'FireBox',
							'feature_label' => firebox()->_('FB_FLOATING_BUTTON'),
							'showon' => '[triggermethod]!:onexternallink,floatingbutton'
						]
						
					]
				],
				// opening behavior
				'opening_behavior' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_OPENING_BEHAVIOR'),
						'description' => firebox()->_('FB_METABOX_OPENING_BEHAVIOR_DESC')
					],
					'fields' => [
						// Show Frequency
						
						// Opening Sound
						[
							'name' => 'opening_sound.source',
							'type' => 'Openingsound',
							'input_class' => ['fb-opening-sound-field', 'medium'],
							'label' => firebox()->_('FB_OPENING_SOUND'),
							'description' => firebox()->_('FB_OPENING_SOUND_DESC'),
							'default' => 'none',
							'choices' => $sounds
						],
						[
							'name' => 'opening_sound.file',
							'type' => 'MediaUploader',
							'media' => 'url',
							'label' => firebox()->_('FB_CUSTOM_OPENING_SOUND'),
							'description' => firebox()->_('FB_CUSTOM_OPENING_SOUND_FILE_DESC'),
							'filter' => 'esc_url_raw',
							'showon' => '[opening_sound][source]:custom_file'
						],
						[
							'name' => 'opening_sound.url',
							'type' => 'Text',
							'input_class' => ['xxlarge'],
							'label' => firebox()->_('FB_CUSTOM_OPENING_SOUND'),
							'placeholder' => firebox()->_('FB_CUSTOM_OPENING_SOUND_HINT'),
							'description' => firebox()->_('FB_CUSTOM_OPENING_SOUND_URL_DESC'),
							'showon' => '[opening_sound][source]:custom_url'
						],
						[
							'type' => 'Alert',
							'text' => firebox()->_('FB_OPENING_SOUND_NOTICE'),
							'class' => ['margin-top-0'],
							'showon' => '[opening_sound][source]!:none'
						],
						
						[
							'type' => 'Pro',
							'plugin' => 'FireBox',
							'label' => 'FPF_SHOW_FREQUENCY',
							'description' => firebox()->_('FB_SHOW_FREQUENCY_DESC')
						]
						
					]
				],
				// closing behavior
				'closing_behavior' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_CLOSING_BEHAVIOR'),
						'description' => firebox()->_('FB_METABOX_CLOSING_BEHAVIOR_DESC')
					],
					'fields' => [
						// behavior
						[
							'name' => 'assign_cookietype',
							'type' => 'Radio',
							'label' => firebox()->_('FB_METABOX_CLOSING_BEHAVIOR_BEHAVIOR_TITLE'),
							'default' => 'never',
							'choices' => [
								'never' => firebox()->_('FB_KEEP_SHOWING_CAMPAIGN'),
								'ever' => firebox()->_('FB_DO_NOT_SHOW_CAMPAIGN_AGAIN'),
								'session' => firebox()->_('FB_DO_NOT_SHOW_CAMPAIGN_AGAIN_SESSION'),
								'custom' => firebox()->_('FB_DO_NOT_SHOW_CAMPAIGN_AGAIN_PERIOD')
							]
						],
						[
							'type' => 'CustomDiv',
							'class' => ['cell', 'margin-top-0'],
							'showon' => '[assign_cookietype]:custom'
						],
						[
							'type' => 'CustomDiv',
							'class' => ['fpf-side-by-side-items']
						],
						[
							'type' => 'Label',
							'text' => 'FPF_DONT_SHOW_AGAIN_FOR',
							'render_group' => false
						],
						[
							'type' => 'Number',
							'input_class' => ['xsmall'],
							'name' => 'assign_cookietype_param_custom_period_times',
							'default' => 1,
							'min' => 1,
							'render_group' => false
						],
						[
							'name' => 'assign_cookietype_param_custom_period',
							'type' => 'Dropdown',
							'render_group' => false,
							'default' => 'days',
							'input_class' => ['text-lowercase', 'small'],
							'choices' => [
								'days' => 'FPF_DAYS',
								'hours' => 'FPF_HOURS',
								'minutes' => 'FPF_MINUTES'
							]
						],
						[
							'type' => 'CustomDiv',
							'position' => 'end'
						],
						[
							'type' => 'CustomDiv',
							'position' => 'end'
						],
						
					]
				],
				// accessibility
				'accessibility' => [
					'title' => [
						'title' => firebox()->_('FB_ACCESSIBILITY'),
						'description' => firebox()->_('FB_ACCESSIBILITY_DESC')
					],
					'fields' => [
						[
							'name' => 'autofocus',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_AUTO_FOCUS'),
							'description' => firebox()->_('FB_AUTO_FOCUS_DESC')
						]
					]
				],
				
			]
		];

		return apply_filters('firebox/box/settings/behavior/edit', $settings);
	}
}