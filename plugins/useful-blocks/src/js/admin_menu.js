const { __ } = wp.i18n;

(function ($) {
	// ajax処理
	const ajaxToClearData = (actionName) => {
		// pbVars 正常に取得できるか
		const pbVars = window.pbVars;
		if (pbVars === undefined) return;

		// ajaxURL
		const ajaxUrl = pbVars.ajaxUrl;
		if (ajaxUrl === undefined) return;

		// nonceキー
		const ajaxNonce = pbVars.ajaxNonce;
		if (ajaxNonce === undefined) return;

		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				action: actionName,
				nonce: ajaxNonce,
			},
		})
			.done(function (returnData) {
				// リクエスト成功時
				alert(returnData);
				location.reload();
			})
			.fail(function () {
				// リクエスト失敗時
				alert(__('Failed.', 'useful-blocks'));
			});
	};

	// 設定のリセット
	(function () {
		const resetBtn = document.getElementById('pb-btn--reset');
		if (null !== resetBtn) {
			resetBtn.addEventListener('click', function (e) {
				e.preventDefault();

				/* eslint no-alert: 0 */
				if (window.confirm(__('Do you really want to reset it?', 'useful-blocks'))) {
					ajaxToClearData('pb_reset_settings');
				}
			});
		}
	})();

	// カラーパレット
	$(function () {
		$('.pb-colorpicker').wpColorPicker({
			change(event, ui) {
				// change イベントを発火させる。（setTimeout でちょっと遅らせないと選択した色が反映されない）
				const $this = $(this);
				setTimeout(function () {
					$this.trigger('change');
				}, 10);
			},
			clear() {
				// クリア時にも change イベントを発火させる。
				const $this = $(this);
				const $colorPicker = $this.prev().find('input');
				setTimeout(function () {
					$colorPicker.trigger('change');
				}, 10);
			},
		});
	});

	// 設定タブの切替処理
	(function () {
		// ページ上部へ
		window.scrollTo(0, 0);

		const tabNavs = document.querySelectorAll('.nav-tab');
		const tabContents = document.querySelectorAll('.tab-contents');
		const refererInput = document.querySelector('[name="_wp_http_referer"]');

		const locationHash = location.hash;
		// console.log('locationHash', locationHash);

		if (locationHash) {
			const hashTarget = document.querySelector(locationHash);
			const hashTab = document.querySelector('[href="' + locationHash + '"]');
			const actTabNav = document.querySelector('.nav-tab.act_');
			const actTabContent = document.querySelector('.tab-contents.act_');
			if (hashTarget && hashTab && actTabNav && actTabContent) {
				actTabNav.classList.remove('act_');
				actTabContent.classList.remove('act_');
				hashTarget.classList.add('act_');
				hashTab.classList.add('act_');
			}

			//_wp_http_refererをセット
			refererInput.value = '/wp-admin/admin.php?page=useful_blocks' + locationHash;
		}

		for (let i = 0; i < tabNavs.length; i++) {
			tabNavs[i].addEventListener('click', function (e) {
				e.preventDefault();
				const targetHash = e.target.getAttribute('href');

				// History APIでURLを書き換える
				history.replaceState(null, null, targetHash);

				if (!tabNavs[i].classList.contains('act_')) {
					document.querySelector('.nav-tab.act_').classList.remove('act_');
					tabNavs[i].classList.add('act_');

					document.querySelector('.tab-contents.act_').classList.remove('act_');
					tabContents[i].classList.add('act_');
				}

				//_wp_http_refererをセット
				refererInput.value = '/wp-admin/admin.php?page=useful_blocks' + targetHash;
			});
		}
	})();

	/**
	 * カラーセットの変更を反映させる
	 */
	(function () {
		const $colorSetting = $('.-pb-colset');
		const $colorInput = $colorSetting.find('.pb-colorpicker');

		$colorInput.on('change', function () {
			const $this = $(this);
			const thisVal = $this.val();
			const thisKey = $this.attr('data-key');

			document.documentElement.style.setProperty('--pb_' + thisKey, thisVal);
		});
	})();
})(window.jQuery);
