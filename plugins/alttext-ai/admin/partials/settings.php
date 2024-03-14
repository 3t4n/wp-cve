<?php
/**
 * Provide an admin area view for the plugin settings.
 *
 * This file is used to markup the admin-facing settings of the plugin.
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
  $has_file_based_api_key = defined( 'ATAI_API_KEY' );
  $wp_kses_args = array(
    'a' => array(
        'href' => array(),
        'target' => array()
    ),
    'br' => array()
  );
?>

<?php
  $lang = get_option( 'atai_lang' );
  $supported_languages = ATAI_Utility::supported_languages();
?>

<div class="mr-5">
  <?php if ( isset( $_GET['atai_activated'] ) ) : ?>
    <script type="application/javascript">
      function onCloseAtaiWelcomePanel() {
        document.getElementById('atai-welcome-panel').style.display = 'none';
      }
    </script>

    <div id="atai-welcome-panel" class="bg-teal-700 px-4">
      <a id="atai-welcome-panel-close" class="flex justify-end w-full pr-4 items-center text-gray-300 hover:text-yellow-500" href="#" aria-label="Dismiss the welcome panel" onclick="onCloseAtaiWelcomePanel(); return false;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="ml-1">Close</span>
      </a>
      <div class="mx-auto max-w-2xl px-6 py-1 sm:py-2 lg:px-8">
        <div class="text-center">
          <p class="text-2xl font-semibold leading-7 text-gray-300">Welcome to AltText.ai</p>
          <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">Let's get set up...</h2>
        </div>
        <ol class="list-inside list-decimal text-base text-gray-300">
          <li>
            If you don't have an AltText.ai account,
            <a class="text-yellow-400 font-semibold hover:text-yellow-200" href="https://alttext.ai?utm_source=wp&utm_medium=dl" target="_blank" rel="noopener noreferrer">create a free account</a>
            on our site.
          </li>
          <li>
            Copy or create
            <a class="text-yellow-400 font-semibold hover:text-yellow-200" href="https://alttext.ai/account/api_keys" target="_blank" rel="noopener noreferrer">your API Key</a>
            from your account, and enter it below.
          </li>
        </ol>
        <p class="mt-8 text-center text-gray-300">
          See the plugin features in our short
          <a class="text-yellow-400 font-semibold hover:text-yellow-200" href="https://youtu.be/LpMXPbMds4U" target="_blank" rel="noopener noreferrer">
            Tutorial video.
          </a>
        </p>
      </div>
    </div>
  <?php endif; ?>

  <h2 class="pt-4 text-2xl font-bold"><?php esc_html_e( 'AltText.ai WordPress Settings', 'alttext-ai' ); ?></h2>
  <?php settings_errors(); ?>

  <form method="post" action="<?php echo esc_url( admin_url() . 'options.php' ); ?>">
    <?php settings_fields( 'atai-settings' ); ?>
    <?php do_settings_sections( 'atai-settings' ); ?>

    <input type="submit" name="submit" value="Save Changes" class="mt-4 inline-flex justify-center rounded-md border bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
    <div class="space-y-4 sm:space-y-6">
      <div>
        <div class="mt-4 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:border-t sm:pb-0">

          <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-4">
            <label for="username" class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"><?php esc_html_e( 'API Key', 'alttext-ai' ); ?></label>
            <div class="mt-2 sm:col-span-2 sm:mt-0">
              <div class="flex gap-x-2">
                <input
                  type="text"
                  name="atai_api_key"
                  value="<?php echo ( ATAI_Utility::get_api_key() ) ? '*********' : null; ?>"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                  <?php echo ( $has_file_based_api_key || ATAI_Utility::get_api_key() ) ? 'readonly' : null; ?>
                >
                <input
                  type="submit"
                  name="handle_api_key"
                  class="relative inline-flex items-center gap-x-1.5 rounded-md px-3 py-2 no-underline border-gray-400 text-gray-600 text-sm rounded-md border font-base shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 whitespace-nowrap <?php echo ( ATAI_Utility::get_api_key() ) ? 'bg-gray-100 hover:bg-indigo-100' : 'bg-indigo-100 hover:bg-indigo-200' ?>"
                  value="<?php echo ( ATAI_Utility::get_api_key() ) ? esc_attr__( 'Clear API Key', 'alttext-ai' ) : esc_attr__( 'Add API Key', 'alttext-ai' ); ?>"
                  <?php echo ( $has_file_based_api_key ) ? 'disabled' : null; ?>
                >
              </div>
              <div class="mt-1">
                <?php if ( ! ATAI_Utility::get_api_key() ) : ?>
                  <p>
                    <?php
                      printf (
                        wp_kses(
                          __( 'Get your API Key at <a href="%s" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">AltText.ai > Account > API Keys</a>.', 'alttext-ai' ),
                          array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
                        ),
                        esc_url( 'https://alttext.ai/account/api_keys' )
                      );
                    ?>
                  </p>
                <?php elseif ( ATAI_Utility::get_api_key() && $this->account === false ) : ?>
                  <p class="text-red-600 font-semibold">
                    <?php
                      printf (
                        wp_kses(
                          __( 'Your API key is invalid. Please check your API key or <a href="%s" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">create a new API key</a>.', 'alttext-ai' ),
                          array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
                        ),
                        esc_url( 'https://alttext.ai/account/api_keys' )
                      );
                    ?>
                  </p>
                <?php else : ?>
                  <p>
                    <?php
                      if (! $this->account['whitelabel']) {
                        printf(
                          wp_kses(
                            __( 'You\'re on the <strong>%s</strong> plan.', 'alttext-ai' ),
                            array( 'strong' => array() )
                          ),
                          esc_html( $this->account['plan'] )
                        );

                        echo '<br>';
                      }
                      printf(
                        wp_kses(
                          __( 'You have <strong>%d</strong> credits available out of <strong>%d</strong>.', 'alttext-ai' ),
                          array( 'strong' => array() )
                        ),
                        (int) $this->account['available'],
                        (int) $this->account['quota']
                      );

                      if (! $this->account['whitelabel']) {
                        echo '<br>';

                        printf(
                          wp_kses(
                            __( 'You can <a href="%s" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">upgrade your plan</a> to get more credits.', 'alttext-ai' ),
                            array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
                          ),
                          esc_url( 'https://alttext.ai/subscriptions?utm_source=wp&utm_medium=dl' )
                        );
                      }
                    ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-4">
            <label for="country" class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"><?php esc_html_e( 'Alt Text Language', 'alttext-ai' ); ?></label>
            <div class="mt-2 sm:col-span-2 sm:mt-0">
              <select id="atai_lang" name="atai_lang" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                <?php
                  foreach ( $supported_languages as $lang_code => $lang_name ) {
                    $option_str = "<option value=\"$lang_code\"";

                    if ( $lang === $lang_code ) {
                      $option_str = $option_str . " selected";
                    }

                    $option_str = $option_str . ">$lang_name</option>\n";
                    echo $option_str;
                  }
                ?>
              </select>
              <?php if ( ATAI_Utility::has_polylang() || ATAI_Utility::has_wpml() ) : ?>
                <p class="mt-1 text-gray-500"><?php esc_html_e( 'Note: Translation plugins can override this value.', 'alttext-ai' ); ?></p>
              <?php endif; ?>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'When alt text is generated for an image:', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-2">
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_update_title"
                      name="atai_update_title"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_update_title' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_update_title" class="font-medium text-gray-900"><?php esc_html_e( 'Also set the image title with the generated alt text.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_update_caption"
                      name="atai_update_caption"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_update_caption' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_update_caption" class="font-medium text-gray-900"><?php esc_html_e( 'Also set the image caption with the generated alt text.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_update_description"
                      name="atai_update_description"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_update_description' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="comments" class="font-medium text-gray-900"><?php esc_html_e( 'Also set the image description with the generated alt text.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
                <div>
                  <label for="atai_alt_prefix" class="block text-sm leading-6 text-gray-600"><?php echo __( 'Add a hardcoded string <strong>to the beginning</strong>:', 'alttext-ai' ); ?></label>
                  <div class="mt-2">
                    <input
                      type="text"
                      name="atai_alt_prefix"
                      id="atai_alt_prefix"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      value="<?php echo ( get_option( 'atai_alt_prefix' ) ); ?>"
                    >
                  </div>
                </div>
                <div>
                  <label for="atai_alt_suffix" class="block text-sm leading-6 text-gray-600"><?php echo __( 'Add a hardcoded string <strong>to the end</strong>:', 'alttext-ai' ); ?></label>
                  <div class="mt-2">
                    <input
                      type="text"
                      name="atai_alt_suffix"
                      id="atai_alt_suffix"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      value="<?php echo ( get_option( 'atai_alt_suffix' ) ); ?>"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'When new images are added:', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-2">
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_enabled"
                      name="atai_enabled"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_enabled' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_enabled" class="font-medium text-gray-900"><?php esc_html_e( 'Automatically generate alt text with AltText.ai', 'alttext-ai' ); ?></label>
                    <p class="text-gray-500"><?php esc_html_e( 'Note: You can always generate alt text using the Bulk Generate page or Update Alt Text button on an individual image.', 'alttext-ai' ); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'Image types:', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div>
                  <label for="atai_type_extensions" class="block text-sm leading-6 text-gray-600"><?php esc_html_e( 'Only process images with these file extensions:', 'alttext-ai' ); ?></label>
                  <div class="mt-2">
                    <input
                      type="text"
                      name="atai_type_extensions"
                      id="atai_type_extensions"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      value="<?php echo ( get_option( 'atai_type_extensions' ) ); ?>"
                    >
                  </div>
                  <p class="mt-1 text-gray-500">
                    <?php esc_html_e( 'Separate multiple extensions with commas. Example: jpg,webp', 'alttext-ai' ); ?>
                    <br>
                    <?php esc_html_e( 'Leave blank to process all image types.', 'alttext-ai' ); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'SEO Keywords', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-2">
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_keywords"
                      name="atai_keywords"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_keywords' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_keywords" class="font-medium text-gray-900"><?php esc_html_e( 'Generate alt text using focus keyphrases, if present.', 'alttext-ai' ); ?></label>
                    <p class="mt-1 text-gray-500">
                      AltText.ai will intelligently integrate the focus keyphrases from the associated post.
                      Compatible with Yoast SEO, AllInOne SEO, RankMath, SEOPress, and Squirrly SEO plugins for WordPress.
                      <a href="https://alttext.ai/support#faq-wordpress" target="blank" rel="noopener" class="font-medium text-indigo-600 hover:text-indigo-500">Learn more</a>.
                    </p>
                  </div>
                </div>
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_keywords_title"
                      name="atai_keywords_title"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_keywords_title' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_keywords_title" class="font-medium text-gray-900"><?php esc_html_e( 'Use post title as keywords if SEO keywords not found from plugins.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'Chat GPT:', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div>
                  <label for="atai_gpt_prompt" class="block text-sm leading-6 text-gray-600">
                    <?php esc_html_e( 'Use a ChatGPT prompt to modify any generated alt text.', 'alttext-ai' ); ?>
                    <a href="https://alttext.ai/docs/webui/adding-images/#using-chatgpt-modification" target="blank" rel="noopener" class="font-medium text-indigo-600 hover:text-indigo-500">Learn more</a>.
                  </label>
                  <div class="mt-2">
                    <textarea
                      name="atai_gpt_prompt"
                      id="atai_gpt_prompt"
                      rows="3"
                      maxlength="512"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      placeholder="example: Rewrite the following text in the style of Shakespeare: {{AltText}}"
                    ><?php echo ( get_option( 'atai_gpt_prompt' ) ); ?></textarea>
                  </div>
                  <p class="mt-1 text-gray-500">
                    <?php esc_html_e( 'Your prompt MUST include the macro {{AltText}}, which will be substituted with the generated alt text, then sent to ChatGPT.', 'alttext-ai' ); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <?php if ( ! $this->account || ! $this->account['whitelabel'] ) : ?>
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'AltText.ai Account', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div>
                  <?php
                    printf(
                      wp_kses(
                        __( '<a href="%s" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Manage your account</a> and additional settings.', 'alttext-ai' ),
                        array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
                      ),
                      esc_url( 'https://alttext.ai/account/edit?utm_source=wp&utm_medium=dl' )
                    );
                  ?>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

        </div>
      </div>

      <?php if (ATAI_Utility::has_woocommerce()) : ?>
      <div>
        <div>
          <h2 class="text-base font-semibold leading-7 text-gray-900"><?php esc_html_e( 'WooCommerce Integration', 'alttext-ai' ); ?></h2>
          <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600"><?php esc_html_e( 'Control how AltText.ai works with WooCommerce.', 'alttext-ai' ); ?></p>
        </div>

        <div class="mt-4 space-y-10 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:pb-0">
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'Ecommerce Vision™', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_ecomm"
                      name="atai_ecomm"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_ecomm' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_ecomm" class="font-medium text-gray-900"><?php esc_html_e( 'Use product name in generated alt text for WooCommerce product images.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Technical Settings</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">These settings are for more advanced technical features. Only modify these if needed.</p>

        <div class="mt-10 space-y-10 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:pb-0">
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'Miscellaneous', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_public"
                      name="atai_public"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_public' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_public" class="font-medium text-gray-900"><?php esc_html_e( 'This site is reachable over the public internet.', 'alttext-ai' ); ?></label>
                    <p class="text-gray-500">
                      Check this box to allow AltText.ai to fetch your images via URLs. If this site is private
                      then uncheck this box, and images will be uploaded to AltText.ai. Our Human Review service
                      is only available to public sites.
                    </p>
                  </div>
                </div>
                <div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input
                      id="atai_no_credit_warning"
                      name="atai_no_credit_warning"
                      type="checkbox"
                      value="yes"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 checked:bg-white"
                      <?php checked( 'yes', get_option( 'atai_no_credit_warning' ) ); ?>
                    >
                  </div>
                  <div class="-mt-1 text-sm leading-6">
                    <label for="atai_no_credit_warning" class="font-medium text-gray-900"><?php esc_html_e( 'Do not show warning when out of credits.', 'alttext-ai' ); ?></label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
            <div class="text-sm font-semibold leading-6 text-gray-900" aria-hidden="true"><?php esc_html_e( 'Error Logs', 'alttext-ai' ); ?></div>
            <div class="mt-4 sm:col-span-2 sm:mt-0">
              <div class="max-w-lg space-y-6">
                <div class="relative gap-x-3">
                  <div
                    id="atai_error_logs"
                    class="bg-white h-24 overflow-auto"
                    disabled
                  >
                    <?php echo wp_kses( ATAI_Utility::get_error_logs(), $wp_kses_args ); ?>
                  </div>
                  <a
                    href="<?php echo esc_url( add_query_arg( 'atai_action', 'clear-error-logs' ) ); ?>"
                    class="mt-2 no-underline bg-indigo-100 border-gray-400 text-gray-600 hover:bg-indigo-200 inline-flex items-center rounded-md border px-2 py-1.5 text-sm font-base shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 whitespace-nowrap"
                  >
                    <?php esc_html_e( 'Clear Logs', 'alttext-ai' ); ?>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <input type="submit" name="submit" value="Save Changes" class="mt-4 inline-flex justify-center border rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
  </form>
</div>
