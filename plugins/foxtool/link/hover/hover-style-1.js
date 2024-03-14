// hieu ung tuyet roi
const LIFE_PER_TICK = 1000 / 60;
const MAX_FLAKES = Math.min(75, screen.width / 1280 * 10);
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
        flake.innerHTML = "<img style='width:20px;height:20px;' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAEtGlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjEwMCIKICAgZXhpZjpQaXhlbFlEaW1lbnNpb249IjEwMCIKICAgZXhpZjpDb2xvclNwYWNlPSIxIgogICB0aWZmOkltYWdlV2lkdGg9IjEwMCIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMTAwIgogICB0aWZmOlJlc29sdXRpb25Vbml0PSIyIgogICB0aWZmOlhSZXNvbHV0aW9uPSI3Mi8xIgogICB0aWZmOllSZXNvbHV0aW9uPSI3Mi8xIgogICBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIgogICBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiCiAgIHhtcDpNb2RpZnlEYXRlPSIyMDIz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'/>";
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