<?php
if(!defined('ABSPATH'))
	exit;
	$api_key = esc_html(get_option('copymatic_apikey'));
	$website_key = esc_html(get_option('copymatic_website_key'));
?>
<div class="wrap">
	<input type="hidden" value="<?php echo $api_key; ?>" id="api_key">
	<input type="hidden" value="load_articles" id="copymatic_action">
	<div class="copymatic-heading">
		<div>
			<h1 class="wp-heading-inline">My Copymatic Content</h1>
			<button type="button" class="page-title-action" id="refresh-articles">Refresh</button>
		</div>
		<?php if(empty($website_key)){ ?>
		<div>
			<p>This website doesn't seem to be connected to your Copymatic account</p><button id="connect-website-copymatic" class="button button-primary">Connect Website</button>
		</div>
		<?php } ?>
	</div>
	<table class="widefat fixed striped copymatic-articles-table" cellspacing="0">
		<thead>
		<tr>
				<th id="columnname" class="manage-column column-columnname" scope="col">Blog Title</th>
				<th id="columnname" class="manage-column column-columdate word_count" scope="col" width="160px">Word Count</th>
				<th id="columnname" class="manage-column column-columdate date" scope="col"  width="200px">Date</th>
				<th id="columnname" class="manage-column column-columnactions actions" scope="col">Actions</th>

		</tr>
		</thead>
		<tbody>
			<tr class="alternate">
				<td colspan="4"><div class="loading-row"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>Loading your articles from Copymatic...</div></td>
			</tr>
		</tbody>	
		<tfoot>
		<tr>
				<th id="columnname" class="manage-column column-columnname" scope="col">Blog Title</th>
				<th id="columnname" class="manage-column column-columdate word_count" scope="col">Word Count</th>
				<th id="columnname" class="manage-column column-columdate date" scope="col">Date</th>
				<th id="columnname" class="manage-column column-columnactions actions" scope="col">Actions</th>
		</tr>
		</tfoot>
	</table>
	<div class="copymatic-explainer">
		<h2>How it works</h2>
		<p>This tool lets you import your blog posts created on Copymatic in one click.</p>
		<ul>
			<li><strong>Import:</strong> Will create your Copymatic article as a draft, ready to be edited or published.</li>
			<li><strong>Edit in Copymatic:</strong> Will let you edit the article in Copymatic so you can generate more content or let the AI complete your text (Write more function).</li>
			<li><strong>Delete:</strong> Will delete your article from Copymatic, not from your Wordpress website.</li>
		</ul>
	</div>
</div>
<script>
	const api_key = '<?php echo esc_js($api_key); ?>';
</script>