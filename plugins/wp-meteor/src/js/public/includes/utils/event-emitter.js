export default class EventEmitter {
  constructor() {
    this.l = [];
  }
  emit(name, data = null) {
    this.l[name] && this.l[name].forEach((l) => l(data));
  }
  on(name, callback) {
    this.l[name] ||= [];
    this.l[name].push(callback);
  }
  off(name, callback) {
    this.l[name] = (this.l[name] || []).filter((c) => c !== callback);
  }
}
//# sourceMappingURL=event-emitter.js.map
