<?php
/**
 * This file is used to markup the CSV import page of the plugin.
 *
 * @link       https://alttext.ai
 * @since      1.1.0
 *
 * @package    ATAI
 * @subpackage ATAI/admin/partials
 */
?>

<?php  if ( ! defined( 'WPINC' ) ) die; ?>

<?php
  $message = '';

  if ( isset( $_POST['submit'] ) && isset( $_FILES['csv'] ) ) {
    $attachment = new ATAI_Attachment();
    $response = $attachment->process_csv();

    if ($response['status'] === 'success') {
      // Generate a success message
      $message = '<div class="mt-2 ml-0 p-4 border border-4 border-dashed rounded-md notice notice-success is-dismissible">';
      $message .= '<p>' . esc_html($response['message']) . '</p>';
      $message .= '</div>';
    } elseif ($response['status'] === 'error') {
      // Generate an error message
      $message = '<div class="mt-2 ml-0 p-4 border border-4 border-dashed rounded-md notice notice-error is-dismissible">';
      $message .= '<p>' . esc_html($response['message']) . '</p>';
      $message .= '</div>';
    }
  }
?>

<div class="mr-5 max-w-2xl">
  <div class="mb-4">
    <h2 class="text-2xl font-bold"><?php esc_html_e( 'Sync Alt Text Library', 'alttext-ai' ); ?></h2>

    <p class="mt-2">
      Synchronize any changes or edits from your online AltText.ai image library to WordPress.
      Any matching images in WordPress will be updated with the corresponding alt text
      from your library.
    </p>

    <?php echo $message; ?>

    <div class="mt-6">
      <p class="block mb-2 text-base font-medium text-gray-900">Step 1: Export your online library</p>
      <ul class="ml-4 list-inside list-disc">
        <li>Go to your <a href="https://alttext.ai/images" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">AltText.ai Image Library</a></li>
        <li>Click the Export button.</li>
        <li>Start the export, then download the CSV file when it's done.</li>
      </ul>
    </div>

    <div class="mt-8">
      <p class="block mb-2 text-base font-medium text-gray-900">Step 2: Upload your CSV</p>
      <form method="post" enctype="multipart/form-data">
        <div class="items-center w-full sm:flex">
          <div class="w-3/4">
            <input
              id="file_input"
              type="file"
              name="csv"
              accept=".csv"
              required
              class="file:bg-black file:text-gray-200 file:text-sm file:rounded-l file:font-medium file:mr-2 file:py-1 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none"
            >
          </div>
        </div>
        <div class="mt-4">
          <input type="submit" name="submit" value="Import" class="mt-4 inline-flex justify-center border rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        </div>
      </form>
    </div>

  <div class="mt-12">

    <div class="rounded-md bg-teal-50 opacity-80 p-4">
      <div class="flex">
        <div>
          <h3 class="text-sm font-medium text-teal-800">Do you like AltText.ai? Leave us a review!</h3>
          <div class="mt-2 text-sm text-teal-700">
            <p>Help spread the word on WordPress.org. We'd really appreciate it!</p>
          </div>
          <div class="mt-4">
            <div class="flex">
              <a
                href="https://wordpress.org/support/plugin/alttext-ai/reviews/?filter=5"
                target="_blank"
                rel="noopenner noreferrer"
                class="font-medium text-indigo-600 hover:text-indigo-500"
              >Leave your review</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
