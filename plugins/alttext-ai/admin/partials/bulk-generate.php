<?php
/**
 * This file is used to markup the bulk generate page of the plugin.
 *
 * @link       https://alttext.ai
 * @since      1.0.0
 *
 * @package    ATAI
 * @subpackage ATAI/admin/partials
 */
?>

<?php  if ( ! defined( 'WPINC' ) ) die; ?>

<?php
  $cannot_bulk_update = ( ! $this->account || ! $this->account['available'] );
  $subscriptions_url = esc_url( 'https://alttext.ai/subscriptions' );
  $action = $_REQUEST['atai_action'] ?? 'normal';

  /* Variables used only for bulk-action selected images */
  $batch_id = $_REQUEST['atai_batch_id'] ?? null;
  $selected_images = ( $action === 'bulk-select-generate' ) ? get_transient( 'alttext_bulk_select_generate_' . $batch_id ) : null;

  if ( $action === 'bulk-select-generate' && $selected_images === false ) {
    $action = 'normal';
  }

  if ( $action === 'normal' ) {
    global $wpdb;
    $atai_asset_table = $wpdb->prefix . ATAI_DB_ASSET_TABLE;
    $mode = isset( $_GET['atai_mode'] ) && $_GET['atai_mode'] === 'all' ? 'all' : 'missing';
    $mode_url = admin_url( sprintf( 'admin.php?%s', http_build_query( $_GET ) ) );
    $only_attached_url = $only_new_url = $mode_url;

    if ( $mode !== 'all' ) {
      $mode_url = add_query_arg( 'atai_mode', 'all', $mode_url );
    } else {
      $mode_url = remove_query_arg( 'atai_mode', $mode_url );
    }

    $only_attached = isset( $_GET['atai_attached'] ) && $_GET['atai_attached'] === '1' ? '1' : '0';
    if ( $only_attached !== '1' ) {
      $only_attached_url = add_query_arg( 'atai_attached', '1', $only_attached_url );
    } else {
      $only_attached_url = remove_query_arg( 'atai_attached', $only_attached_url );
    }

    $only_new = isset( $_GET['atai_only_new'] ) && $_GET['atai_only_new'] === '1' ? '1' : '0';
    if ( $only_new !== '1' ) {
      $only_new_url = add_query_arg( 'atai_only_new', '1', $only_new_url );
    } else {
      $only_new_url = remove_query_arg( 'atai_only_new', $only_new_url );
    }

    // Count of all images in the media gallery
    $all_images_query = <<<SQL
SELECT COUNT(*) as total_images
FROM {$wpdb->posts}
WHERE ({$wpdb->posts}.post_mime_type LIKE 'image/%')
  AND {$wpdb->posts}.post_type = 'attachment'
  AND (({$wpdb->posts}.post_status = 'inherit'))
SQL;

    if ($only_attached === '1') {
      $all_images_query = $all_images_query . " AND {$wpdb->posts}.post_parent > 0";
    }

    if ($only_new === '1') {
      $all_images_query = $all_images_query . " AND NOT EXISTS(SELECT 1 FROM {$atai_asset_table} WHERE wp_post_id = {$wpdb->posts}.ID)";
    }

    $all_images_count = $images_count = (int) $wpdb->get_results( $all_images_query )[0]->total_images;
    $images_missing_alt_text_count = 0;

    // Images without alt text
    $images_without_alt_text_sql = <<<SQL
SELECT COUNT(DISTINCT {$wpdb->posts}.ID) as total_images
FROM {$wpdb->posts}
  LEFT JOIN {$wpdb->postmeta}
    ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt')
  LEFT JOIN {$wpdb->postmeta} AS mt1 ON ({$wpdb->posts}.ID = mt1.post_id)
WHERE ({$wpdb->posts}.post_mime_type LIKE 'image/%')
  AND ({$wpdb->postmeta}.post_id IS NULL OR (mt1.meta_key = '_wp_attachment_image_alt' AND mt1.meta_value = ''))
  AND {$wpdb->posts}.post_type = 'attachment'
  AND (({$wpdb->posts}.post_status = 'inherit'))
SQL;

    if ($only_attached === '1') {
      $images_without_alt_text_sql = $images_without_alt_text_sql . " AND {$wpdb->posts}.post_parent > 0";
    }

    if ($only_new === '1') {
      $images_without_alt_text_sql = $images_without_alt_text_sql . " AND NOT EXISTS(SELECT 1 FROM {$atai_asset_table} WHERE wp_post_id = {$wpdb->posts}.ID)";
    }

    $images_missing_alt_text_count = (int) $wpdb->get_results( $images_without_alt_text_sql )[0]->total_images;

    if ( $mode === 'missing' ) {
      $images_count = $images_missing_alt_text_count;
    }
  } elseif ( $action === 'bulk-select-generate' ) {
    $all_images_count = $images_count = count( $selected_images );
  }
?>

<div class="mr-5">

  <div class="mb-4">
    <h2 class="text-2xl font-bold"><?php esc_html_e( 'Bulk Generate Alt Text', 'alttext-ai' ); ?></h2>

    <?php if ( $action === 'bulk-select-generate' ) : ?>
      <dl class="grid grid-cols-1 gap-8 sm:grid-cols-2 max-w-2xl">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-2 shadow sm:p-4">
          <dt class="truncate text-lg font-medium text-teal-700"><?php esc_html_e( 'Selected Images to Update', 'alttext-ai' ); ?></dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900"><?php echo $all_images_count; ?></dd>
        </div>
      </dl>
    <?php else : ?>
      <dl class="grid grid-cols-1 gap-8 sm:grid-cols-2 max-w-2xl">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-2 shadow sm:p-4">
          <dt class="truncate text-lg font-medium text-gray-500"><?php esc_html_e( 'Total Images', 'alttext-ai' ); ?></dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900"><?php echo $all_images_count; ?></dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-2 shadow sm:p-4">
          <dt class="truncate text-lg font-medium text-teal-700"><?php esc_html_e( 'Images Missing Alt Text', 'alttext-ai' ); ?></dt>
          <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900"><?php echo $images_missing_alt_text_count; ?></dd>
        </div>
      </dl>
    <?php endif; ?>
  </div>

  <?php if ( $cannot_bulk_update ) : ?>
    <div class="-mt-2 rounded-md bg-yellow-50 p-4">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-yellow-700">
            You have no more credits left.
            <?php if ( $this->account && !$this->account['whitelabel'] ) : ?>
              To bulk update your library, please
              <a href="<?php echo $subscriptions_url; ?>" target="_blank" class="font-medium text-yellow-700 underline hover:text-yellow-600">
                <?php esc_html_e( 'purchase more credits.', 'alttext-ai' ); ?>
              </a>
              <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
    <?php return; ?>
  <?php else : ?>
    <div class="-mt-2 rounded-md bg-blue-50 p-2">
      <div class="flex">
        <div class="flex-1 md:flex md:justify-between">
          <p class="text-sm text-blue-700 font-medium">
            <?php printf( esc_html__( 'Available credits: %d', 'alttext-ai' ), (int) $this->account['available'] ); ?>
            <?php if ( !$this->account['whitelabel'] ) : ?>
              (
              <a href="<?php echo $subscriptions_url; ?>" target="_blank" class="whitespace-nowrap underline text-xs text-blue-700 hover:text-blue-600">
                <?php esc_html_e( 'Get more credits', 'alttext-ai' ); ?>
              </a>
              )
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div id="bulk-generate-form">
    <div class="px-4 mt-2 border border-4 border-dashed border-gray-300 rounded-md">
      <h3 class="text-lg font-semibold text-gray-700">Keywords</h3>
      <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
        <div>
          <label for="keywords">
            <span class="text-sm leading-6 text-gray-900">[optional] SEO Keywords</span>
            <span class="text-xs text-gray-500">(try to include these in the generated alt text)</span>
          </label>

          <div class="mt-1 max-w-md">
            <input data-bulk-generate-keywords type="text" size="60" maxlength="512" name="keywords" id="bulk-generate-keywords" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
          </div>
          <p class="mt-1 text-xs text-gray-500">Separate with commas. Maximum of 6 keywords or phrases.</p>
        </div>
        <div>
          <label for="negative-keywords">
            <span class="text-sm leading-6 text-gray-900">[optional] Negative keywords</span>
            <span class="text-xs text-gray-500">(do not include these in the generated alt text)</span>
          </label>
          <div class="mt-1 max-w-md">
            <input data-bulk-generate-negative-keywords type="text" size="60" maxlength="512" name="negative-keywords" id="bulk-generate-negative-keywords" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
          </div>
          <p class="mt-1 text-xs text-gray-500">Separate with commas. Maximum of 6 keywords or phrases.</p>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <?php if ($images_count === 0) : ?>
      <button
        type="button"
        class="disabled pointer-events-none border rounded-md bg-indigo-200 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        Generate Alt Text
      </button>

      <?php else : ?>
      <button
        data-bulk-generate-start
        type="button"
        class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        <?php
          printf( esc_html( _n( 'Generate Alt Text: %d image', 'Generate Alt Text: %d images', $images_count, 'alttext-ai' ) ), $images_count );
        ?>
      </button>
      <?php endif; ?>
    </div>

    <?php if ( $action === 'normal' ) : ?>
      <fieldset class="mt-4">
        <legend class="sr-only">Bulk Generation Modes</legend>
        <div class="space-y-2">
          <div class="relative flex items-start">
            <div class="flex h-6 items-center">
              <input
                type="checkbox"
                id="atai_bulk_generate_all"
                data-bulk-generate-mode-all
                data-url="<?php echo $mode_url; ?>"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                <?php if ( isset( $_GET['atai_mode'] ) && $_GET['atai_mode'] === 'all' ) echo 'checked'; ?>
              >
            </div>
            <div class="-mt-1 ml-2 text-xs leading-6">
              <label for="atai_bulk_generate_all" class="text-gray-900"><?php esc_html_e( 'Include images that already have alt text (overwrite existing alt text).', 'alttext-ai' ); ?></label>
            </div>
          </div>
          <div class="relative flex items-start">
            <div class="flex h-6 items-center">
              <input
                type="checkbox"
                id="atai_bulk_generate_only_attached"
                data-bulk-generate-only-attached
                data-url="<?php echo $only_attached_url; ?>"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                <?php if ( $only_attached === '1' ) echo 'checked'; ?>
              >
            </div>
            <div class="-mt-1 ml-2 text-xs leading-6">
              <label for="atai_bulk_generate_only_attached" class="text-gray-900"><?php esc_html_e( 'Only process images that are attached to posts.', 'alttext-ai' ); ?></label>
            </div>
          </div>
          <div class="relative flex items-start">
            <div class="flex h-6 items-center">
              <input
                type="checkbox"
                id="atai_bulk_generate_only_new"
                data-bulk-generate-only-new
                data-url="<?php echo $only_new_url; ?>"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                <?php if ( $only_new === '1' ) echo 'checked'; ?>
              >
            </div>
            <div class="-mt-1 ml-2 text-xs leading-6">
              <label for="atai_bulk_generate_only_new" class="text-gray-900"><?php esc_html_e( 'Skip images already processed by AltText.ai', 'alttext-ai' ); ?></label>
            </div>
          </div>
        </div>
      </fieldset>
    <?php endif; ?>
  </div> <!-- bulk generate form -->

  <div data-bulk-generate-progress-wrapper class="hidden mt-4 p-6 border border-4 space-y-4">
    <h3 data-bulk-generate-progress-heading class="text-xl font-semibold">
      <?php esc_html_e( 'Update in progress (please keep this page open until the update completes)', 'alttext-ai' ); ?>
    </h3>

    <div data-bulk-generate-progress-bar-wrapper>
      <div class="flex justify-between mb-1">
        <span class="text-base font-medium text-indigo-700">Progress</span>
        <span data-bulk-generate-progress-percent class="text-base font-medium text-indigo-700">0%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-4">
        <div
          data-bulk-generate-progress-bar
          data-max="<?php echo $images_count; ?>"
          data-current="0"
          data-successful="0"
          class="bg-indigo-600 h-4 rounded-full" style="width: 0.5%"
        ></div>
      </div>
    </div>

    <p class="text-lg">
      <?php
        printf(
          esc_html__( '%s / %d images processed (%s successful)', 'alttext-ai' ),
          '<span data-bulk-generate-progress-current>0</span>',
          $images_count,
          '<span data-bulk-generate-progress-successful>0</span>'
        );
      ?>
    </p>

    <p>
      <button
        data-bulk-generate-cancel
        class="border rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        onclick="window.location = '<?php echo admin_url( 'admin.php?page=atai-bulk-generate' ); ?>';"
      >
        <?php esc_html_e( 'Cancel', 'alttext-ai' ); ?>
      </button>
    </p>

    <p>
      <button
        data-bulk-generate-finished
        class="hidden border rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        onclick="window.location = window.atai.redirectUrl;"
      >
        <?php esc_html_e( $action === 'bulk-select-generate' ? 'Back to Media Library' : 'Done', 'alttext-ai' ); ?>
      </button>
    </p>
  </div>
</div>
