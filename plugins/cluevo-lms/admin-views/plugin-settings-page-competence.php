<?php
if (is_admin()) {
  function cluevo_init_competence_page()
  {
    // development version
    // wp_register_script(
    // "vue-js",
    // "https://cdn.jsdelivr.net/npm/vue/dist/vue.js",
    // "",
    // "",
    // true
    // );

    // production version
    wp_register_script(
      "vue-js",
      plugins_url("/js/vue.min.js", plugin_dir_path(__FILE__)),
      "",
      CLUEVO_VERSION,
      true
    );
    wp_enqueue_script('vue-js');

    wp_register_script(
      'cluevo-admin-competence-utilities',
      plugins_url('/js/competence.utilities.js', plugin_dir_path(__FILE__)),
      "",
      CLUEVO_VERSION,
      true
    );
    wp_localize_script(
      'cluevo-admin-competence-utilities',
      'compApiSettings',
      array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
      )
    );
    wp_enqueue_script('cluevo-admin-competence-utilities');
  }

  function cluevo_render_competences_tab()
  {
    wp_register_script(
      'cluevo-admin-competence-view',
      plugins_url('/js/competence.admin.js', plugin_dir_path(__FILE__)),
      array("vue-js"),
      CLUEVO_VERSION,
      true
    );
    wp_localize_script(
      'cluevo-admin-competence-view',
      'compApiSettings',
      array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
      )
    );
    if (function_exists('wp_set_script_translations')) {
      wp_set_script_translations('cluevo-admin-competence-view', 'cluevo', plugin_dir_path(__DIR__) . 'lang');
    }
    wp_enqueue_script('cluevo-admin-competence-view');
?>
    <?php cluevo_display_notice(__("Hint", "cluevo"), __("Welcome to the competence system. Think of competences as an additional way to organize your modules. You can assign modules to competences and set how much of a competence each module covers.\nCompetences in turn can be grouped into competence areas. Your users can score points in each competence and area by completing modules that teach these competences.", "cluevo"), "info", "cluevo-competence-intro"); ?>
    <script type="text/x-template" id="competence-app-header-template">
      <tr>
      <th>#</th>
      <th class="left"><?php esc_html_e("Name", "cluevo"); ?></th>
      <th><?php esc_html_e("Competence Groups", "cluevo"); ?></th>
      <th><?php esc_html_e("Modules", "cluevo"); ?></th>
      <th><?php esc_html_e("Coverage", "cluevo"); ?></th>
      <th class="left"><?php esc_html_e("Created by", "cluevo"); ?></th>
      <th><?php esc_html_e("Date Created", "cluevo"); ?></th>
      <th class="left"><?php esc_html_e("Modified by", "cluevo"); ?></th>
      <th><?php esc_html_e("Date Modified", "cluevo"); ?></th>
    </tr>
  </script>
    <script type="text/x-template" id="competence-editor-template">
      <transition name="modal">
    <div class="modal-mask" @click.self="$emit('close')">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <h3 v-once v-if="!creating"><?php esc_html_e("Edit Competence", "cluevo"); ?>: {{ competence.competence_name }}</h3>
            <h3 v-if="creating"><?php esc_html_e("Create Competence", "cluevo"); ?></h3>
            <button type="button" class="close" @click="$emit('close')"><span class="dashicons dashicons-no-alt"></span></button>
          </div>

          <div class="modal-body">
            <div class="competence-editor">
              <table class="name">
                <tr>
                  <td><label><?php esc_html_e("Name", "cluevo"); ?></label></td>
                  <td class="input">
                    <input
                      type="text"
                      name="competence_name"
                      v-model="competence.competence_name"
                      placeholder="<?php esc_html_e('Enter a name here', 'cluevo'); ?>"
                      required
                    />
                  </td>
                </tr>
              </table>
              <div class="input-field submit" v-if="!creating">
                <button
                  class="button auto button-primary"
                  :disabled="!competence.competence_name || competence.competence_name == '' || busy"
                  @click="save_competence"><?php esc_html_e("Save", "cluevo"); ?>
                </button>
                </div>
              <div class="details-container">
                <div class="modules">
                  <h5>{{ competence.modules.length }} <?php esc_html_e("Modules", "cluevo"); ?></h5>
                  <p class="hint" v-if="competence.modules.length == 0 && !editing_modules && !creating">&#x24d8; <?php esc_html_e("Not assigned to any modules", "cluevo"); ?></p>
                  <table v-if="!editing_modules && competence.modules.length > 0 && !creating" class="wp-list-table striped widefat">
                    <thead>
                      <tr>
                        <th><?php esc_html_e("Module", "cluevo"); ?></th>
                        <th class="right"><?php esc_html_e("Coverage", "cluevo"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in competence.modules">
                        <td class="ellipsis">{{ item.module_name }}</td>
                        <td class="right">{{ item.competence_coverage * 100 }}%</td>
                      </tr>
                    </tbody>
                  </table>
                  <table v-if="editing_modules || creating" class="edit-modules wp-list-table striped widefat">
                    <thead>
                      <tr>
                        <th><?php esc_html_e("Module", "cluevo"); ?></th>
                        <th class="right"><?php esc_html_e("Coverage", "cluevo"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in modules" :key="item.module_id">
                        <td>
                          <label>
                            <input type="checkbox" name="modules[]" :value="item.module_id" v-model="item.checked" />
                            <span>{{ item.module_name }}</span>
                          </label>
                        </td>
                        <td class="right">
                          <input type="number" min="0" max="100" v-model="item.competence_coverage" /> %
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="buttons" v-if="!creating">
                    <div class="button auto button-secondary" name="edit-modules" v-if="!this.editing_modules" @click.prevent="toggle_edit_modules"><?php esc_attr_e("Edit", "cluevo"); ?></div>
                    <div class="button auto button-secondary" name="cancel-save-modules" v-if="this.editing_modules" @click.prevent="toggle_edit_modules"><?php esc_attr_e("Cancel", "cluevo"); ?></div>
                    <div class="button auto button-primary" name="save-modules" v-if="this.editing_modules" @click.prevent="save_modules"><?php esc_attr_e("Save", "cluevo"); ?></div>
                  </div>
                </div>
                <div class="areas">
                  <h5>{{ competence.areas.length }} <?php esc_html_e("Competence Groups", "cluevo"); ?></h5>
                  <p class="hint" v-if="competence.areas.length == 0 && !editing_areas && !creating">&#x24d8; <?php esc_html_e("Not assigned to any competence groups.", "cluevo"); ?></p>
                  <p class="hint" v-if="creating && areas && areas.length == 0"><?php esc_html_e("No competence groups have been created yet.", "cluevo"); ?></p>
                  <ul v-if="!editing_areas && competence.areas.length > 0 && !creating">
                    <li v-for="item in competence.areas">{{ item.competence_area_name }}</li>
                  </ul>
                  <ul v-if="editing_areas || creating" class="edit-areas">
                    <li v-for="item in areas">
                    <label><input type="checkbox" name="areas[]" :value="item.competence_area_id" v-model="item.checked" /> {{ item.competence_area_name }}</label>
                    </li>
                  </ul>
                  <div class="buttons" v-if="!creating">
                    <div class="button auto button-secondary" name="edit-areas" v-if="!this.editing_areas" @click.prevent="toggle_edit_areas"><?php esc_attr_e("Edit", "cluevo"); ?></div>
                    <div class="button auto button-secondary" name="cancel-save-areas" v-if="this.editing_areas" @click.prevent="toggle_edit_areas"><?php esc_attr_e("Cancel", "cluevo"); ?></div>
                    <div class="button auto button-primary" name="save-areas" v-if="this.editing_areas" @click.prevent="save_areas"><?php esc_attr_e("Save", "cluevo"); ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="button auto button-secondary" name="cancel-edit-competence" @click="$emit('close')"><?php esc_attr_e("Close", "cluevo"); ?></div>
            <div
              v-if="creating"
              :disabled="!competence.competence_name || competence.competence_name == '' || busy"
              class="button auto button-primary"
              name="create-competence"
              @click="create_competence"
            ><?php esc_attr_e("Save", "cluevo"); ?></div>
          </div>
        </div>
      </div>
    </div>
    </transition>
  </script>
    <div id="competence-admin-app"></div>

  <?php
  }

  function cluevo_render_competence_areas_tab()
  {
    wp_register_script(
      'cluevo-admin-competence-area-view',
      plugins_url('/js/competence-area.admin.js', plugin_dir_path(__FILE__)),
      array("vue-js"),
      CLUEVO_VERSION,
      true
    );
    wp_localize_script(
      'cluevo-admin-competence-area-view',
      'compApiSettings',
      array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
      )
    );
    if (function_exists('wp_set_script_translations')) {
      wp_set_script_translations('cluevo-admin-competence-area-view', 'cluevo', plugin_dir_path(__DIR__) . 'lang');
    }
    wp_enqueue_script('cluevo-admin-competence-area-view');
    cluevo_display_notice(__("Hint", "cluevo"), __("You can organize your competences into different competence areas. Your users can increase there competence area scores by completing modules that teach competences that are grouped into competence areas.", "cluevo"), "info", "cluevo-competence-area-intro");
  ?>
    <script type="text/x-template" id="competence-area-app-header-template">
      <tr>
      <th>#</th>
      <th class="left"><?php esc_html_e("Name", "cluevo"); ?></th>
      <th><?php esc_html_e("Competence Groups", "cluevo"); ?></th>
      <th><?php esc_html_e("Modules", "cluevo"); ?></th>
      <th class="left"><?php esc_html_e("Created by", "cluevo"); ?></th>
      <th><?php esc_html_e("Date Created", "cluevo"); ?></th>
      <th class="left"><?php esc_html_e("Modified by", "cluevo"); ?></th>
      <th><?php esc_html_e("Date Modified", "cluevo"); ?></th>
    </tr>
  </script>
    <script type="text/x-template" id="competence-area-editor-template">
      <transition name="modal">
    <div class="modal-mask" @click.self="$emit('close')">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <h3 v-once v-if="!creating"><?php esc_html_e("Edit Competence Group", "cluevo"); ?>: {{ area.competence_area_name }}</h3>
            <h3 v-if="creating"><?php esc_html_e("Create Competence Group", "cluevo"); ?></h3>
            <button class="close" @click="$emit('close')"><span class="dashicons dashicons-no-alt"></span></button>
          </div>

          <div class="modal-body">
            <div class="competence-editor">
              <table class="name">
                <tr>
                  <td><label><?php esc_html_e("Name", "cluevo"); ?></label></td>
                  <td class="input">
                    <input
                       v-model="area.competence_area_name"
                       placeholder="<?php esc_html_e('Enter a name here', 'cluevo'); ?>"
                       type="text"
                       name="competence_area_name"
                       required
                     />
                  </td>
                </tr>
              </table>
              <div v-if="!creating" class="input-field submit">
                <div class="button auto button-primary" @click="save_area"><?php esc_html_e("Save", "cluevo"); ?></div>
              </div>
              <div class="details-container">
                <div class="modules">
                  <h5>{{ area.modules.length }} <?php esc_html_e("Modules", "cluevo"); ?></h5>
                  <p class="hint" v-if="area.modules.length == 0 && !editing_modules">&#x24d8; <?php esc_html_e("Not assigned to any modules.", "cluevo"); ?></p>
                  <ul>
                    <li v-for="module in area.modules">{{ module.module_name }}</li>
                  </ul>
                </div>
                <div class="competences">
                  <h5>{{ area.competences.length }} <?php esc_html_e("Competences", "cluevo"); ?></h5>
                  <p class="hint" v-if="area.competences.length == 0 && !editing_comps && !creating">&#x24d8; <?php esc_html_e("No competences have been assigned yet.", "cluevo"); ?></p>
                  <p class="hint" v-if="creating && comps && comps.length == 0"><?php esc_html_e("No competences have been created yet.", "cluevo"); ?></p>
                  <ul v-if="!editing_comps && area.competences.length > 0 && !creating">
                    <li v-for="item in area.competences">{{ item.competence_name }}</li>
                  </ul>
                  <ul v-if="editing_comps || creating" class="edit-comps">
                    <li v-for="item in comps">
                    <label><input type="checkbox" name="comps[]" :value="item.competence_id" v-model="item.checked" /> {{ item.competence_name }}</label>
                    </li>
                  </ul>
                  <div class="buttons" v-if="!creating">
                    <div class="button auto button-secondary" name="edit-comps" v-if="!this.editing_comps" @click.prevent="toggle_edit_comps"><?php esc_attr_e("Edit", "cluevo"); ?></div>
                    <div class="button auto button-secondary" name="cancel-save-areas" v-if="this.editing_areas" @click.prevent="toggle_edit_comps"><?php esc_attr_e("Cancel", "cluevo"); ?></div>
                    <button
                      v-if="this.editing_comps"
                      type="button"
                      class="button auto button-primary"
                      name="save-comps"
                      @click.prevent="save_comps"
                    ><?php esc_attr_e("Save", "cluevo"); ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="button auto button-secondary" name="cancel-edit-area" @click="$emit('close')"><?php esc_attr_e("Close", "cluevo"); ?></div>
            <button
              v-if="creating"
              :disabled="!area.competence_area_name || area.competence_area_name === '' || busy"
              class="button auto button-primary"
              type="button"
              name="create-competence_area"
              @click="create_area"
            ><?php esc_attr_e("Save", "cluevo"); ?></button>
          </div>
        </div>
      </div>
    </div>
    </transition>
  </script>
    <div id="competence-area-admin-app">
    </div>

  <?php
  }

  function cluevo_render_competence_areas_page()
  {
    cluevo_init_competence_page();
    $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : CLUEVO_ADMIN_TAB_COMPETENCE_MAIN;
  ?>
    <div class="wrap cluevo-admin-page-container">
      <h1 class="cluevo-admin-page-title-container">
        <div><?php esc_html_e("CLUEVO Competence", "cluevo"); ?></div>
        <img class="plugin-logo" src="<?php echo esc_url(plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)), ['http', 'https']); ?>" />
      </h1>
      <div class="cluevo-admin-page-content-container">
        <h2 class="nav-tab-wrapper cluevo">
          <a href="<?php echo esc_url(admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_COMPETENCE . "&tab=" . CLUEVO_ADMIN_TAB_COMPETENCE_MAIN), ['http', 'https']); ?>" class="nav-tab <?php echo ($active_tab == CLUEVO_ADMIN_TAB_COMPETENCE_MAIN) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e("Competences", "cluevo"); ?></a>
          <a href="<?php echo esc_url(admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_COMPETENCE . "&tab=" . CLUEVO_ADMIN_TAB_COMPETENCE_AREAS), ['http', 'https']); ?>" class="nav-tab <?php echo ($active_tab == CLUEVO_ADMIN_TAB_COMPETENCE_AREAS) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e("Competence Groups", "cluevo"); ?></a>
        </h2>

        <?php
        switch ($active_tab) {
          case CLUEVO_ADMIN_TAB_COMPETENCE_MAIN:
            cluevo_render_competences_tab();
            break;
          case CLUEVO_ADMIN_TAB_COMPETENCE_AREAS:
            cluevo_render_competence_areas_tab();
            break;
          default:
            cluevo_render_competences_tab();
            break;
        }
        ?>
      </div>
    </div>

<?php
  }
}
