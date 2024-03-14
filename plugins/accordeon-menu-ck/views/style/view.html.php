<?php
/**
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */
Namespace Accordeonmenuck;

// No direct access
defined('CK_LOADED') or die;

/**
 * View to edit
 */
class CKViewStyle extends CKView {

	protected $view = 'style';

	protected $item;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		// check if the user has the rights to access this page
		if (! CKFof::userCan('manage')) {
			CKFof::_die();
		}

		$id = $this->input->get('id', 0, 'int');
		$this->item = $this->get('style', 'Data', $id);
		$this->imagespath = ACCORDEONMENUCK_MEDIA_URL . '/images/interface/';
		$this->interface = new CKInterfaceLight();
		$this->input = new CKInput();

		parent::display($tpl);
	}

	protected function renderPreviewMenu() {
		?>
		<div class="accordeonck ">
			<ul class="" id="accordeonck_previewmodule">
				<li id="item-1" class="accordeonck item1 parent first parent level1 " data-level="1">
					<span class="accordeonck_outer toggler toggler_1">
						<span class="toggler_icon"></span>
						<a class="accordeonck " href="javascript:void(0)">Lorem
							<span class="accordeonck_desc">A little description</span>
						</a>
					</span>
					<ul class="content_1" style="display:none;">
						<li id="item-2" class="accordeonck item2 parent parent level2 " data-level="2">
							<span class="accordeonck_outer toggler toggler_2">
								<span class="toggler_icon"></span>
								<a class="accordeonck " href="javascript:void(0)">Curabitur</a>
							</span>
							<ul class="content_2" style="display:none;">
								<li id="item-3" class="accordeonck item2 level3 " data-level="3">
									<span class="accordeonck_outer ">
										<a class="accordeonck " href="javascript:void(0)">Elementum</a>
									</span>
								</li>
								<li id="item-4" class="accordeonck item2 level3 " data-level="3">
									<span class="accordeonck_outer ">
										<a class="accordeonck " href="javascript:void(0)">Lobortis nec</a>
									</span>
								</li>
							</ul>
						</li>

						<li id="item-5" class="accordeonck item3 level2 " data-level="2">
							<span class="accordeonck_outer ">
								<a class="accordeonck " href="javascript:void(0)">Dictum nisi</a>
							</span>
						</li>

						<li id="item-6" class="accordeonck item4 level2 " data-level="2">
							<span class="accordeonck_outer ">
								<a class="accordeonck " href="javascript:void(0)">Semper orci</a>
							</span>
						</li>
					</ul>
				</li>

				<li id="item-7" class="accordeonck item5 level1 " data-level="1">
					<span class="accordeonck_outer ">
						<a class="accordeonck " href="javascript:void(0)">Ipsum</a>
					</span>
				</li>

				<li id="item-8" class="accordeonck item6 parent parent level1 " data-level="1">
					<span class="accordeonck_outer toggler toggler_1">
						<span class="toggler_icon"></span>
							<a class="accordeonck " href="javascript:void(0)">Dolor sit</a>
						</span>
						<ul class="content_1" style="display:none;">
							<li id="item-10" class="accordeonck item8 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Cras massa</a></span></li>

							<li id="item-11" class="accordeonck item9 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Faucibus</a></span></li>

							<li id="item-12" class="accordeonck item10 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Dapibus ligula</a></span></li>

							<li id="item-14" class="accordeonck item12 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Eu placerat</a></span></li>

							<li id="item-15" class="accordeonck item13 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Felis posuere</a></span></li>

							<li id="item-16" class="accordeonck item14 level2 " data-level="2"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Adipiscing</a></span></li></ul></li>

				<li id="item-17" class="accordeonck item15 level1 " data-level="1"><span class="accordeonck_outer "><a class="accordeonck " href="javascript:void(0)">Consectetur</a></span></li>
			</ul>
		</div>

<?php
$js = "<script>
	jQuery(document).ready(function(){
		jQuery('#accordeonck_previewmodule').accordeonmenuck({"
		. "fadetransition : false,"
		. "eventtype : 'click',"
		. "transition : 'linear',"
		. "menuID : 'accordeonck_previewmodule',"
		. "imageplus : '". plugins_url() ."/accordeon-menu-ck/assets/plus.png',"
		. "imageminus : '". plugins_url() ."/accordeon-menu-ck/assets/minus.png',"
		. "defaultopenedid : '0',"
		. "activeeffect : 'false',"
		. "duree : 500"
		. "});
}); </script>";

echo($js);
?>
<script src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/accordeonmenuck.js" type="text/javascript"></script>
		<?php
	}
}