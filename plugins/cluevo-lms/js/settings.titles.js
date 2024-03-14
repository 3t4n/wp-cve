Vue.component("cluevo-user-titles", {
  props: {
    titles: Object
  },
  data: function() {
    return {
      newName: "",
      newLevel: 0,
      list: []
    }
  },
  computed: {
    titleText: function() {
      var text = "";
      this.list.forEach(function(value) {
        text += value.level + ":" + value.name + "\n";
      });
      return text;
    },
    isValidInput() {
      let vm = this;
      let exists = this.list.find(function(t) { return parseInt(t.level, 10) === parseInt(vm.newLevel, 10); });
      if (this.newName.trim() === "" || parseInt(this.newLevel, 10) < 0 || exists) return false;
      return true;
    }
  },
  mounted: function() {
    let id = null;
    for (let level in this.titles) {
      do {
        id = Math.random();
      } while (this.list.find(function(t) { return t.id === id; }));
      this.list.push({
        level: level,
        name: this.titles[level],
        id: id
      });
    }
  },
  methods: {
    addTitle: function() {
      let level = parseInt(this.newLevel, 10);
      if (level < 0) return;
      if (this.newName.trim() == "") return;
      let id = null;
      do {
        id = Math.random();
      } while (this.list.find(function(t) { return t.id === id; }));

      this.list.push({
        level: this.newLevel,
        name: this.newName,
        id: id
      });
      this.newLevel = "";
      this.newName = "";
      this.list.sort((a,b) => (parseInt(a.level, 10) > parseInt(b.level, 10)) ? 1 : ((parseInt(b.level, 10) > parseInt(a.level, 10)) ? -1 : 0)); 
    },
    removeTitle(title) {
      this.list = this.list.filter(function(t) { return t.id !== title.id; });
    },
  }
})
