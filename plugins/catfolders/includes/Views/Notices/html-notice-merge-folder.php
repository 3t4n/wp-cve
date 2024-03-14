<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="notice notice-info is-dismissible" id="catf-merge-folder-notice">
	<span class="catf-logo-cover">
		<img src="<?php echo esc_url( CATF_PLUGIN_URL . 'assets/img/logo.svg' ); ?>" width="40" alt="logo">
	</span>
	<p class="catf-notice-wrap">
		<span class="label"><?php esc_html_e( 'Action Required: Merge your folders before March 1st, 2024', 'catfolders' ); ?></span>
		<?php
		esc_html_e(
			'The user-based folder mode will be deprecated in the next version of CatFolders. Therefore, we strongly suggest that you disable "User-Based Folders" in CatFolders settings screen, and make sure to have your correct folder structure as common folders. From version 2.4.2, your user folders will be automatically merged.',
			'catfolders'
		);
		?>
		<a href="https://wpmediafolders.com/docs/settings/merge-folders/" target="blank" rel="noopener noreferrer">
			<strong><?php esc_html_e( 'Learn more', 'catfolders' ); ?></strong>
		</a>
	</p>
	<p>
		<a href="javascript:;" data="rateNow" class="button button-primary" id="catf-merge-download"><?php esc_html_e( 'Download CSV', 'catfolders' ); ?></a>
		<a href="javascript:;" data="later" class="button" id="catf-merge-cancel"><?php esc_html_e( "Got it, but I don't use that feature", 'catfolders' ); ?></a>
	</p>
</div>

<style>
#catf-merge-folder-notice {
  border-left: 4px solid #ea60d5;
  position: relative;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}
#catf-merge-folder-notice .catf-logo-cover {
  padding-right: 10px;
}
#catf-merge-folder-notice .catf-notice-wrap .label {
  display: block;
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 5px;
}
#catf-merge-folder-notice .catf-notice-wrap {
  margin: 0.7em 0;
  flex: 1;
}

#catf-merge-folder-notice p:nth-of-type(2){
	flex: 1 0 100%;
}
</style>

<script>
jQuery(function(){
	function convertArrayOfObjectsToCSV(args) {
		const data = args.data;
		if (!data || !data.length) return;

		const columnDelimiter = args.columnDelimiter || ",";
		const lineDelimiter = args.lineDelimiter || "\n";

		const keys = Object.keys(data[0]);

		let result = "";
		result += keys.join(columnDelimiter);
		result += lineDelimiter;

		data.forEach((item) => {
			let ctr = 0;
			keys.forEach((key) => {
			if (ctr > 0) result += columnDelimiter;
			result += item[key];
			ctr++;
			});
			result += lineDelimiter;
		});

		return result;
	}

	const generateDownloadCSV = (args) => {
		const csv = convertArrayOfObjectsToCSV({ data: args.data });
		const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });

		if (!csv) return;
		const filename = args.filename || "export.csv";
		const link = document.createElement("a");
		link.setAttribute("href", URL.createObjectURL(blob));
		link.setAttribute("download", filename);
		link.click();
	};

	jQuery('#catf-merge-download').on('click', async function(){
		jQuery(this).addClass('updating-message')
		jQuery(this).off('click')

		try {
			const foldersExport = await jQuery.ajax({
				url: `${catfData.apiSettings.rest_url}/export-csv`,
				method: "GET",
				dataType: "json",
				contentType: "application/json",
				headers: {
					"X-WP-Nonce": catfData.apiSettings.rest_nonce
				}
			})

			const prepareData = foldersExport.folders.map((folder) => ({
				id: folder.id,
				title: folder.title,
				parent: folder.parent,
				type: folder.type,
				ord: folder.ord,
				created_by: folder.created_by,
				attachments: `"${folder.attachments.join(',')}"`
			}))

			generateDownloadCSV({
				filename: "cat_folder.csv",
				data: prepareData,
			});

			jQuery('#catf-merge-download').removeClass('updating-message')
			jQuery('#catf-merge-download').attr('disabled', 'disabled')
		} catch (error) {
			alert(catfData.i18n.global.fail_download_csv)
			console.log(error)
		}
	})

	jQuery(document).on('click', '#catf-merge-cancel, #catf-merge-folder-notice .notice-dismiss', function(e){
		jQuery(this).attr('disabled', 'disabled')
		jQuery(this).addClass('updating-message')
		jQuery(this).off('click')
		
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'catf_merge_folder',
				nonce: catfData.nonce,
			},
			success: function (response) {
				if (response.success) {
					jQuery('#catf-merge-folder-notice').hide(200)
				}
			},
			fail: function (response) {
				alert(catfData.i18n.global.fail_download_csv)
				console.log(response)
			}
		})
	})
})
</script>


