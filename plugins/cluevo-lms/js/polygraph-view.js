// math helper...
function cluevoPolygraphValueToPoint(value, index, total) {
  var x = 0;
  var y = -value * 0.8;
  var angle = ((Math.PI * 2) / total) * index;
  var cos = Math.cos(angle);
  var sin = Math.sin(angle);
  var tx = x * cos - y * sin + 100;
  var ty = x * sin + y * cos + 100;
  return {
    x: tx,
    y: ty,
  };
}

jQuery(window).ready(function() {
  if (jQuery("#cluevo-polygraph").length > 0) {
    const cluevoCompetencePolygraphApp = Vue.createApp({
      name: "cluevo-polygraph",
      data() {
        return {
          stats: [],
          radius: 80,
        };
      },
      created() {
        this.stats = cluevoPolygraphData ?? [];
      },
      computed: {
        points: function() {
          var total = this.stats.length;
          return this.stats
            .map(function(stat, i) {
              var point = cluevoPolygraphValueToPoint(stat.value, i, total);
              return point.x + "," + point.y;
            })
            .join(" ");
        },
      },
      template: `
        <svg width="200" height="200">
          <g>
            <circle cx="100" cy="100" :r="radius"></circle>
            <polygon :points="points"></polygon>
            <axis-label
              v-for="(stat, index) in stats"
              :stat="stat"
              :index="index"
              :total="stats.length">
            </axis-label>
          </g>
        </svg>
        <div class="cluevo-polygraph-stats-container">
          <div v-for="stat, i in stats" :key="i">
            <div class="cluevo-labels">
              <label class="cluevo-comp-name">{{ stat.label }}</label>
              <label>{{ stat.value.toFixed(2) }}%</label>
            </div>
            <div class="cluevo-progress-container">
                <span
                  :style="{
                    width: (100 - stat.value) + '%'
                  }"
                  :data-value="stat.value" data-max="100"
                  class="cluevo-progress"
                >
                </span>
            </div>
          </div>
        </div>
      `,
    });
    cluevoCompetencePolygraphApp.component("axis-label", {
      props: {
        stat: Object,
        index: Number,
        total: Number,
      },
      template: `<text :x="point.x" :y="point.y">{{stat.label}}</text>`,
      computed: {
        point: function() {
          return cluevoPolygraphValueToPoint(100, this.index, this.total);
        },
      },
    });
    cluevoCompetencePolygraphApp.mount("#cluevo-polygraph");
  }
});
