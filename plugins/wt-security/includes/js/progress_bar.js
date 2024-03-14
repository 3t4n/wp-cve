// SVG circle progress bar %
// const circle = document.querySelector(".wtotem_from__circle");
// if(circle){
//     const radius = circle.r.baseVal.value;
//     const circumference = 2 * Math.PI * radius;
//     const percentCircle = document.querySelector(".wtotem_from__percent");
//
//     circle.style.strokeDasharray = `${circumference} ${circumference}`;
//     circle.style.strokeDashoffset = circumference;
//
//     const setProgress = (percent) => {
//         const offset = circumference - (percent / 100) * circumference;
//         circle.style.strokeDashoffset = offset;
//         percentCircle.textContent = `${percent}%`;
//     };
//     setProgress(percentCircle.dataset.value);
// }
//----------------------------------

// Progress bar horizontal in table
const stickProgress = document.querySelector(".wtotem_table__stick-progress");
const percentHorizontal = document.querySelector(".wtotem_table__percent");


const setProgressHorizontal = (percent) => {
    percentHorizontal.textContent = `${percent}%`;
    //1.67 * 100 = 167 это ширина этой горизонтальной линии
    stickProgress.style.width = `${percent * 1.67}px`;
};

document.addEventListener('DOMContentLoaded', function () {
    if(typeof availability_percent !== "undefined"){
        setProgressHorizontal(availability_percent);
    }
});
//-----------------------------------