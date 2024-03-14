Vue.component('tab', {
  props: {
    id: { default: null },
    title: { required: true },
  },
  data: function() {
    return { 
      badge: null,
      isActive: false,
      isVisible: true
    };
  }
});

Vue.component('tabs', {
  data: function() {
    return {
      tabs: [],
    };
  },
  methods: {
    selectTab: function(id) {
      for (var tab of this.tabs) {
        tab.isActive = (tab.id == id);
        if (tab.isActive) {
          this.$emit('tab-changed', tab.id);
        }
      }
    }
  },
  created: async function() {
    this.tabs = this.$children;
    await this.$nextTick();
    this.tabs.forEach((t) => {
      if (t.$attrs['data-badge']) {
        let parsed = JSON.parse(t.$attrs['data-badge']);
        let badge = {
          title: parsed.title ?? '',
          class: parsed.class ?? ''
        }
        t.badge = badge;
      }
    });
  },
  mounted: function() {
    if (this.tabs) {
      this.tabs[0].isActive = true;
    }
  }
});

var settingsApp = new Vue({
  el: '#cluevo-settings-page'
});

jQuery(document).ready(function() {
  jQuery('#cluevo-module-commit-interval').on('input', function() {
    jQuery('#cluevo-module-commit-interval-value').text(jQuery(this).val() + ' s');
  });
});
