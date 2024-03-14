// hieu ung tuyet roi
const LIFE_PER_TICK = 900 / 60;
const MAX_FLAKES = Math.min(75, screen.width / 1280 * 5);
const flakes = [];
const period = [
    n => 5 * (Math.sin(n)),
    n => 8 * (Math.cos(n)),
    n => 5 * (Math.sin(n) * Math.cos(2 * n)),
    n => 2 * (Math.sin(0.25 * n) - Math.cos(0.75 * n) + 1),
    n => 5 * (Math.sin(0.75 * n) + Math.cos(0.25 * n) - 1)
];
const fun = ['⛄', '🎁', '🦌', '☃', '🍪'];
const cssString = `.snowfall-container {
    display: block;
    height: 100vh;
    left: 0;
    margin: 0;
    padding: 0;
    -webkit-perspective-origin: top center;
            perspective-origin: top center;
    -webkit-perspective: 150px;
            perspective: 150px;
    pointer-events: none;
    position: fixed;
    top: 0;
    -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
    width: 100%;
    z-index: 99999; }

  .snowflake {
    pointer-events: none;
    color: #ddf;
    display: block;
    font-size: 24px;
    left: -12px;
    line-height: 24px;
    position: absolute;
    top: -12px;
    -webkit-transform-origin: center;
            transform-origin: center; }`;
function ready(fn) {
    if (document.attachEvent ? document.readyState === 'complete' : document.readyState !== 'loading') {
        fn();
    }
    else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}
function resetFlake(flake) {
    let x = flake.dataset.origX = (Math.random() * 100);
    let y = flake.dataset.origY = 0;
    let z = flake.dataset.origZ = (Math.random() < 0.1) ? (Math.ceil(Math.random() * 100) + 25) : 0;
    let life = flake.dataset.life = (Math.ceil(Math.random() * 4000) + 6000); 
    flake.dataset.origLife = life;
    flake.style.transform = `translate3d(${x}vw, ${y}vh, ${z}px)`;
    flake.style.opacity = 1.0;
    flake.dataset.periodFunction = Math.floor(Math.random() * period.length);

    if (Math.random() < 0.001) {
        flake.innerText = fun[Math.floor(Math.random() * fun.length)];
    }
}
function updatePositions() {

    flakes.forEach((flake) => {
        let origLife = parseFloat(flake.dataset.origLife)
        let curLife = parseFloat(flake.dataset.life);
        let dt = (origLife - curLife) / origLife;

        if (dt <= 1.0) {
            let p = period[parseInt(flake.dataset.periodFunction)];
            let x = p(dt * 2 * Math.PI) + parseFloat(flake.dataset.origX);
            let y = 100 * dt;
            let z = parseFloat(flake.dataset.origZ);
            flake.style.transform = `translate3d(${x}vw, ${y}vh, ${z}px)`;
            if (dt >= 0.5) {
                flake.style.opacity = (1.0 - ((dt - 0.5) * 2));
            }
            curLife -= LIFE_PER_TICK;
            flake.dataset.life = curLife;
        }
        else {
            resetFlake(flake);
        }
    });
    window.requestAnimationFrame(updatePositions);
}
function appendSnow() {
    let styles = document.createElement('style');
    styles.innerText = cssString;
    document.querySelector('head').appendChild(styles);
    let field = document.createElement('div');
    field.classList.add('snowfall-container');
    field.setAttribute('aria-hidden', 'true');
    field.setAttribute('role', 'presentation');
    document.body.appendChild(field);
    let i = 0;
    const addFlake = () => {
        let flake = document.createElement('span');
        flake.classList.add('snowflake');
        flake.setAttribute('aria-hidden', 'true');
        flake.setAttribute('role', 'presentation');
        flake.innerHTML = "<img style='width:25px;height:25px;' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAMAAAAp4XiDAAAAAXNSR0IArs4c6QAAAD9QTFRFR3BM/1ea/1ea/1ea/1ea/1ea/1ea/1ea/1ea/1ea//////L4/+Pv/8/k/7bV/6XM/5XD/4m7/3iw/2el/1easAlmAgAAAAp0Uk5TABArRFZzj6jD3zt35fsAAAIkSURBVHjaxZZZsoQgDEWfA1OYQmD/a30iYgPapX99P7rKLg+B5Cb490NNK5eSr/N7gqVDYnoHzDKdUsurTanU6s3mROqknok1DeKPiEyjHpEReD7NfEXWl0eJRLEe5l2UgJtC2sW+rc5lUnwrnCqE19odzLoImZLk633B5cK3X0LUAOCR8n9naeV89VR9ISDCphymF7utXgwhIxbAIOI1e1dTRQ3gAm6M3hPgbIep6WoqB5soIyYjPj+lRqKvd36zRyg/9gfq6o1ZXgPoGA4kGgATAg32YWeMIuetoYIEC95jVuySxlMt+CljnNnkLDg8FNteWLt9+cIU6ZPA0KZ5bqJYAJ1Bc6js1OV1ulb4IB42GeddRVxZBvyJqNYuMSNFuiIatN6XQezLL07GAGiPTRTnbEYsUj384BgKziLFFG0hLBEFm5ehcRROvDcmHohHDBSDp7s5OAvV2MaCc9Y4XZK8A0qwZbpMyaUmbq9gtoAFmysVYkpy+jomg9t7KyOxmLQ09JWpxol7B2NBjqJAsYv8ckEENAdiNH0QuhtOU0rNaCkVOtrHVlMuPcIHO+tiE/TWVYONW1Nt0xxRaHC+up/eWOUc1ZiUDk19lIGhc58hpqovd1ek0C4cP0BS1xz3r7E0SoxJHiT/+NPdNN6R66cnlCyLPNzELEeWFRdlto6axPUaZnWTTN1/aCzqvGXOdZgaszu2DBNSsN5L88rFD768/gGTC2AWw6Hr7QAAAABJRU5ErkJggg=='/>";
        resetFlake(flake);
        flakes.push(flake);
        field.appendChild(flake);
        if (i++ <= MAX_FLAKES) {
            setTimeout(addFlake, Math.ceil(Math.random() * 300) + 100);
        }
    };
    addFlake();
    updatePositions();
}
ready(appendSnow);