/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************************!*\
  !*** ./admin/demos/player2/js/TextToSpeechProDemo.js ***!
  \*******************************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }
function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
function _classPrivateMethodInitSpec(obj, privateSet) { _checkPrivateRedeclaration(obj, privateSet); privateSet.add(obj); }
function _checkPrivateRedeclaration(obj, privateCollection) { if (privateCollection.has(obj)) { throw new TypeError("Cannot initialize the same private elements twice on an object"); } }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : String(i); }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classPrivateMethodGet(receiver, privateSet, fn) { if (!privateSet.has(receiver)) { throw new TypeError("attempted to get private field on non-instance"); } return fn; }
document.addEventListener("DOMContentLoaded", function () {
  /**
   * Define TextToSpeechPro class if TextToSpeech class exists.
   * 
   */
  var id;
  id = setInterval(function () {
    var _window;
    if (window.speechSynthesis.getVoices().length !== 0 && (_window = window) !== null && _window !== void 0 && _window.TextToSpeech) {
      clearInterval(id);
      var _getStoredContent = /*#__PURE__*/new WeakSet();
      var _setPath = /*#__PURE__*/new WeakSet();
      var _setTitle = /*#__PURE__*/new WeakSet();
      var _getContent = /*#__PURE__*/new WeakSet();
      var TextToSpeechPro = /*#__PURE__*/function (_window$TextToSpeech) {
        _inherits(TextToSpeechPro, _window$TextToSpeech);
        var _super = _createSuper(TextToSpeechPro);
        function TextToSpeechPro(buttonId) {
          var _this;
          var _content = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
          var button = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
          var TTS = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : window.TTS;
          _classCallCheck(this, TextToSpeechPro);
          _this = _super.call(this, buttonId, _content, button, TTS);
          _classPrivateMethodInitSpec(_assertThisInitialized(_this), _getContent);
          _classPrivateMethodInitSpec(_assertThisInitialized(_this), _setTitle);
          _classPrivateMethodInitSpec(_assertThisInitialized(_this), _setPath);
          _classPrivateMethodInitSpec(_assertThisInitialized(_this), _getStoredContent);
          _defineProperty(_assertThisInitialized(_this), "buttonId", void 0);
          _defineProperty(_assertThisInitialized(_this), "title", '');
          _defineProperty(_assertThisInitialized(_this), "contents", '');
          _defineProperty(_assertThisInitialized(_this), "path", '');
          _defineProperty(_assertThisInitialized(_this), "storedContent", '');
          _defineProperty(_assertThisInitialized(_this), "compatible", {});
          _this.buttonId = buttonId;
          _classPrivateMethodGet(_assertThisInitialized(_this), _setTitle, _setTitle2).call(_assertThisInitialized(_this), TTS);
          _classPrivateMethodGet(_assertThisInitialized(_this), _setPath, _setPath2).call(_assertThisInitialized(_this), TTS);
          _this.content = _classPrivateMethodGet(_assertThisInitialized(_this), _getContent, _getContent2).call(_assertThisInitialized(_this), _content, TTS);
          _this.storedContent = _classPrivateMethodGet(_assertThisInitialized(_this), _getStoredContent, _getStoredContent2).call(_assertThisInitialized(_this), _this.content);
          //TODO highlight the text in the future.
          // wp.hooks.addAction('tts_high_light_text', 'ttsPro', this.highlightText, 10, 2)
          return _this;
        }
        _createClass(TextToSpeechPro, [{
          key: "onAValueChanged",
          value: function onAValueChanged(handler) {
            console.log(handler);
          }
        }, {
          key: "getData",
          value: function getData() {
            var shouldAsingThis = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
            if (shouldAsingThis) {
              window.TextToSpeechPro = this;
            }
            return this;
          }
        }]);
        return TextToSpeechPro;
      }(window.TextToSpeech);
      function _getStoredContent2(content) {
        var _storedContent, _storedContent2, _storedContent3, _storedContent4;
        var storedContent = JSON.parse(window.sessionStorage.getItem('tts_stored_content'));
        if (!((_storedContent = storedContent) !== null && _storedContent !== void 0 && _storedContent.url) || ((_storedContent2 = storedContent) === null || _storedContent2 === void 0 ? void 0 : _storedContent2.url) !== window.location.href) {
          window.sessionStorage.setItem('tts_stored_content', JSON.stringify({
            content: content,
            url: window.location.href
          }));
        }
        storedContent = JSON.parse(window.sessionStorage.getItem('tts_stored_content'));
        return (_storedContent3 = storedContent) !== null && _storedContent3 !== void 0 && _storedContent3.content ? (_storedContent4 = storedContent) === null || _storedContent4 === void 0 ? void 0 : _storedContent4.content : "";
      }
      function _setPath2(tts) {
        if (tts !== null && tts !== void 0 && tts.extra) {
          this.path = tts.extra[this.buttonId].date;
        }
      }
      function _setTitle2(tts) {
        if (tts !== null && tts !== void 0 && tts.extra) {
          this.title = tts.extra[this.buttonId].title;
        } else {
          this.title = 'Demo Content';
        }
        this.title = this.title.replace(/[^a-zA-Z ]/g, "");
        this.title = this.title.split(' ').join('_');
        this.title = this.title + "__lang=" + tts.settings.listening.tta__listening_lang;
        this.title = this.title + "__voice=" + tts.settings.listening.tta__listening_voice;
      }
      function _getContent2(content, tts) {
        var _tts$settings;
        var domContent = '';
        var selectors = (_tts$settings = tts.settings) === null || _tts$settings === void 0 || (_tts$settings = _tts$settings.settings) === null || _tts$settings === void 0 || (_tts$settings = _tts$settings.settings) === null || _tts$settings === void 0 ? void 0 : _tts$settings.tta__settings_css_selectors;
        if (!selectors && !Array.isArray(selectors)) {
          return content;
        }
        selectors = selectors.split('\n');
        if (selectors.length === 0 || selectors[0] == '') {
          return content;
        }
        for (var i = 0; i < selectors.length; i++) {
          var currentSelector = selectors[i].trim();
          if (currentSelector) {
            var _content2 = document.querySelector(currentSelector);

            // Extract text using textContent
            if (_content2) {
              domContent += ' ' + _content2.textContent || 0;
            }
          }
        }
        if (domContent) {
          var _buttonContent, _buttonContent2;
          var buttonContent = document.querySelector('#tts__listent_content_' + this.buttonId);
          // Extract text using textContent
          buttonContent = ((_buttonContent = buttonContent) === null || _buttonContent === void 0 ? void 0 : _buttonContent.textContent) || ((_buttonContent2 = buttonContent) === null || _buttonContent2 === void 0 ? void 0 : _buttonContent2.innerText);
          domContent = domContent.replace(buttonContent, '');
          domContent = domContent.replaceAll('\n', '');
          return domContent;
        }
        return content;
      }
      window.TextToSpeechPro = TextToSpeechPro;
      window.TextToSpeechPro2 = TextToSpeechPro;
      var buttons = _toConsumableArray(document.querySelectorAll('.tts__listent_content'));
      if (buttons.length) {
        buttons.map(function (button) {
          var buttonId = button.getAttribute('data-id');
          new TextToSpeechPro(buttonId, window.TTS.contents[buttonId], button, window.TTS);
        });
      }
    }
  }, 1000);
});
/******/ })()
;