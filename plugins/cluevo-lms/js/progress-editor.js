Vue.component("cluevo-spinner", {
  template: `
    <div class="cluevo-spinner">
      <div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div>
    </div>
  `,
});

var cluevoProgressEditor = new Vue({
  name: "cluevo-progress-editor",
  el: "#cluevo-progress-editor",
  template: `
        <transition name="modal">
      <div v-if="!closed" class="modal-mask" @click.self="close">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h3> {{ __("Edit Progress Entry", "cluevo") }}</h3>
              <button class="close" @click="close"><span class="dashicons dashicons-no-alt"></span></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="cluevo-form-group">
                  <label>{{ __("Min. Pts.", "cluevo") }}
                    <input
                      v-model="minPoints"
                      :disabled="busy"
                      type="number"
                      name="cluevo-progress-min-pts"
                    />
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Max. Pts.", "cluevo") }}
                    <input v-model="maxPoints" :disabled="busy" type="number" name="cluevo-progress-max-pts" />
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Points", "cluevo") }}
                    <input v-model="points" :disabled="busy" type="number" name="cluevo-progress-pts" />
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Points %", "cluevo") }}
                    <input v-model="pointsPct" :disabled="busy" type="number" name="cluevo-progress-pts-pct" />
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Completion Status", "cluevo") }}
                    <select v-model="completionStatus" :disabled="busy" name="cluevo-progress-completion-status">
                      <option value=""></option>
                      <option value="completed">{{ __("Completed", "cluevo") }}</option>
                      <option value="incomplete">{{ __("Incomplete", "cluevo") }}</option>
                      <option value="not attempted">{{ __("Not Attempted", "cluevo") }}</option>
                      <option value="unknown">{{ __("Unknown", "cluevo") }}</option>
                    </select>
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Success Status", "cluevo") }}
                    <select v-model="successStatus" :disabled="busy" name="cluevo-progress-success-status">
                      <option value=""></option>
                      <option value="passed">{{ __("Passed", "cluevo") }}</option>
                      <option value="failed">{{ __("Failed", "cluevo") }}</option>
                      <option value="unknown">{{ __("Unknown", "cluevo") }}</option>
                    </select>
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label>{{ __("Lesson Status (SCORM 1.2)", "cluevo") }}
                    <select v-model="lessonStatus" :disabled="busy" name="cluevo-progress-lesson-status">
                      <option value=""></option>
                      <option value="passed">{{ __("Passed", "cluevo") }}</option>
                      <option value="completed">{{ __("Completed", "cluevo") }}</option>
                      <option value="failed">{{ __("Failed", "cluevo") }}</option>
                      <option value="incomplete">{{ __("Incomplete", "cluevo") }}</option>
                      <option value="browsed">{{ __("browsed", "cluevo") }}</option>
                      <option value="not attempted">{{ __("Not Attempted", "cluevo") }}</option>
                    </select>
                  </label>
                </div>
                <div class="cluevo-form-group">
                  <label class="cluevo-horizontal">
                    <input v-model="credit" :disabled="busy" type="checkbox" name="cluevo-progress-credit" :value="true" />
                    {{ __("credit", "cluevo") }}
                  </label>
                </div>
                <cluevo-spinner v-if="busy" />
                <div v-else class="cluevo-buttons">
                  <button :disabled="busy" type="button" class="button button-primary" @click="save">{{ __("Save", "cluevo") }}</button>
                  <button :disabled="busy" type="button" class="button" @click="close">{{ __("Cancel", "cluevo") }}</button>
                </div>
                <div v-if="error" class="cluevo-notice cluevo-notice-error">{{ __("Failed to update progress entry", "cluevo") }}</div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </transition>`,
  data: function () {
    return {
      attemptId: null,
      userId: null,
      moduleId: null,
      start: null,
      lastActivity: null,
      minPoints: null,
      maxPoints: null,
      points: null,
      pointsPct: null,
      completionStatus: null,
      successStatus: null,
      lessonStatus: null,
      credit: null,
      closed: true,
      error: null,
      busy: false,
    };
  },
  methods: {
    ...({ __ } = wp.i18n),
    close: function () {
      this.attemptId = null;
      this.userId = null;
      this.moduleId = null;
      this.start = null;
      this.lastActivity = null;
      this.minPoints = null;
      this.maxPoints = null;
      this.points = null;
      this.pointsPct = null;
      this.completionStatus = null;
      this.successStatus = null;
      this.lessonStatus = null;
      this.credit = null;
      this.closed = true;
      this.error = null;
    },
    load: async function (userId, moduleId, attemptId) {
      let url = cluevoProgressSettings.ajax_url;
      this.userId = parseInt(userId, 10);
      this.moduleId = parseInt(moduleId, 10);
      this.attemptId = parseInt(attemptId, 10);
      url += "?action=cluevo-get-progress-entry";
      url += "&cluevo-progress-nonce=" + cluevoProgressSettings.nonce;
      url += "&user_id=" + this.userId;
      url += "&module_id=" + this.moduleId;
      url += "&attempt_id=" + this.attemptId;

      this.busy = true;
      this.closed = false;
      const response = await fetch(url);
      if (response) {
        let data = null;
        try {
          data = await response.json();
          this.minPoints = data.score_min;
          this.maxPoints = data.score_max;
          this.points = data.score_raw;
          this.pointsPct = data.score_scaled;
          this.completionStatus = data.completion_status;
          this.successStatus = data.success_status;
          this.lessonStatus = data.lesson_status;
          this.credit = data.credit === "credit" ? true : false;
        } catch (error) {
          console.error("failed to get progress entry", error);
        }
        console.log(data);
        this.busy = false;
      }
    },
    save: async function () {
      let url =
        cluevoProgressSettings.ajax_url +
        "?action=cluevo-update-progress-entry";
      const payload = {
        "cluevo-progress-nonce": cluevoProgressSettings.nonce,
        completion_status: this.completionStatus,
        success_status: this.successStatus,
        attempt_id: Number(this.attemptId),
        user_id: Number(this.userId),
        module_id: Number(this.moduleId),
        score_min: Number(this.minPoints),
        score_max: Number(this.maxPoints),
        score_raw: Number(this.points),
        score_scaled: Number(this.score_scaled),
        credit: this.credit ? "credit" : "no-credit",
        lesson_status: this.lessonStatus,
      };
      this.busy = true;
      try {
        const response = await fetch(url, {
          method: "POST",
          credentials: "include",
          headers: {
            "Content-Type": "application/json; charset=utf-8",
            "X-WP-Nonce": cluevoProgressSettings.nonce,
          },
          body: JSON.stringify(payload),
        });
        this.closed = true;
        location.reload();
      } catch (error) {
        this.error = true;
        console.error("failed to save progress update", error);
      }
      this.busy = false;
    },
  },
});

jQuery(document).ready(function () {
  jQuery(".cluevo-edit-progress").click(async function (e) {
    e.preventDefault();
    const item = jQuery(this).parents("tr:first");
    const userId = item.data("user-id");
    const moduleId = item.data("module-id");
    const attemptId = item.data("attempt-id");

    cluevoProgressEditor.load(userId, moduleId, attemptId);
  });
});
