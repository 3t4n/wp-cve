export const IS_BROWSER = typeof window !== 'undefined';

export default class Darkmode {
  constructor(options) {
    if (!IS_BROWSER) {
      return;
    }

    const defaultOptions = {
      bottom: '32px',
      right: '32px',
      left: 'unset',
      top: 'unset',
      width: '44px',
      height: '44px',
      borderRadius: '44px',
      fontSize: '16px',
      time: '0s',
      mixColor: '#fff',
      backgroundColor: 'transparent',
      buttonColorDark: '#333333',
      buttonColorLight: '#b3b3b3',
      buttonColorTDark: '#ffffff',
      buttonColorTLight: '#000000',
      label: '',
      saveInCookies: true,
      autoMatchOsTheme: true,
      buttonAriaLabel: 'Toggle dark mode'
    };

    options = Object.assign({}, defaultOptions, options);

    const css = `
      .darkmode-layer {
        position: fixed;
        pointer-events: none;
        background: ${options.mixColor};
        transition: all ${options.time} ease;
        mix-blend-mode: difference;
      }

      .darkmode-layer--button {
        width: ${options.width};
        height: ${options.height};
        border-radius: ${options.borderRadius};
        right: ${options.right};
        bottom: ${options.bottom};
        left: ${options.left};
        top: ${options.top};
        transform: scale(0.98);
      }

      .darkmode-layer--simple {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        transform: scale(1) !important;
      }

      .darkmode-layer--expanded {
        transform: scale(100);
        border-radius: 0;
      }

      .darkmode-layer--no-transition {
        transition: none;
      }

      .darkmode-toggle {
        width: ${options.width};
        height: ${options.height};
        position: fixed;
        padding: 0 !important;
        margin: 0;
        border-radius: ${options.borderRadius} !important;
        border:none;
        right: ${options.right};
        bottom: ${options.bottom};
        left: ${options.left};
        top: ${options.top};
        font-size: ${options.fontSize} !important;
        font-weight: 600;
        line-height: 1 !important;
        cursor: pointer;
        transition: all 0.5s ease;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .darkmode-toggle,
      .darkmode-toggle:hover,
      .darkmode-toggle:focus,
      .darkmode-toggle:active {
        box-shadow: none !important;
        background: ${options.buttonColorDark};
        color: ${options.buttonColorTDark};
      }

      .darkmode-toggle--white,
      .darkmode-toggle--white:hover,
      .darkmode-toggle--white:focus,
      .darkmode-toggle--white:active {
        background: ${options.buttonColorLight};
        color: ${options.buttonColorTLight};
      }

      .darkmode-toggle--inactive {
        display: none;
      }

      .darkmode-background {
        background: ${options.backgroundColor};
        position: fixed;
        pointer-events: none;
        z-index: -10;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
      }

      img, .darkmode-ignore {
        isolation: isolate;
      }

      @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
        .darkmode-toggle {display: none !important}
      }

      @supports (-ms-ime-align:auto), (-ms-accelerator:true) {
        .darkmode-toggle {display: none !important}
      }
    `;

    const layer = document.createElement('div');
    const button = document.createElement('button');
    const background = document.createElement('div');

    button.innerHTML = options.label;
    button.classList.add('darkmode-toggle--inactive');
    layer.classList.add('darkmode-layer');
    background.classList.add('darkmode-background');

    const darkmodeActivated = window.localStorage.getItem('darkmode') === 'true';
    const preferedThemeOs =
      options.autoMatchOsTheme && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const darkmodeNeverActivatedByAction = window.localStorage.getItem('darkmode') === null;

    if (
      (darkmodeActivated === true && options.saveInCookies) ||
      (darkmodeNeverActivatedByAction && preferedThemeOs)
    ) {
      layer.classList.add(
        'darkmode-layer--expanded',
        'darkmode-layer--simple',
        'darkmode-layer--no-transition'
      );
      button.classList.add('darkmode-toggle--white');
      button.setAttribute('aria-checked', 'true');
      document.body.classList.add('darkmode--activated');
    } else {
      button.setAttribute('aria-checked', 'false');
    }

    document.body.insertBefore(button, document.body.firstChild);
    document.body.insertBefore(layer, document.body.firstChild);
    document.body.insertBefore(background, document.body.firstChild);

    this.addStyle(css);

    this.button = button;
    this.layer = layer;
    this.saveInCookies = options.saveInCookies;
    this.time = options.time;
    this.buttonAriaLabel = options.buttonAriaLabel;
  }

  addStyle(css) {
    const linkElement = document.createElement('link');

    linkElement.setAttribute('rel', 'stylesheet');
    linkElement.setAttribute('type', 'text/css');
    linkElement.setAttribute('href', 'data:text/css;charset=UTF-8,' + encodeURIComponent(css));
    document.head.appendChild(linkElement);
  }

  showWidget() {
    if (!IS_BROWSER) {
      return;
    }
    const button = this.button;
    const layer = this.layer;
    const time = parseFloat(this.time) * 1000;

    button.classList.add('darkmode-toggle');
    button.classList.remove('darkmode-toggle--inactive');
    button.setAttribute('aria-label', this.buttonAriaLabel);
    button.setAttribute('role', 'checkbox');
    layer.classList.add('darkmode-layer--button');

    button.addEventListener('click', () => {
      const isDarkmode = this.isActivated();

      if (!isDarkmode) {
        layer.classList.add('darkmode-layer--expanded');
        button.setAttribute('disabled', true);
        setTimeout(() => {
          layer.classList.add('darkmode-layer--no-transition');
          layer.classList.add('darkmode-layer--simple');
          button.setAttribute('aria-checked', 'true');
          button.removeAttribute('disabled');
        }, time);
      } else {
        layer.classList.remove('darkmode-layer--simple');
        button.setAttribute('disabled', true);
        setTimeout(() => {
          layer.classList.remove('darkmode-layer--no-transition');
          layer.classList.remove('darkmode-layer--expanded');
          button.setAttribute('aria-checked', 'false');
          button.removeAttribute('disabled');
        }, 1);
      }

      button.classList.toggle('darkmode-toggle--white');
      document.body.classList.toggle('darkmode--activated');
      window.localStorage.setItem('darkmode', !isDarkmode);
    });
  }

  isActivated() {
    if (!IS_BROWSER) {
      return null;
    }
    return document.body.classList.contains('darkmode--activated');
  }
}
