/*
** JS Image Zoom By Csomor
*/

/*
** Global Variables
*/
let zoomSize = 150

/*
** DOMContentLoaded event
*/
document.addEventListener("DOMContentLoaded", ()=>{
  console.log('JS Image Zoom By Csömör loaded ...')
  let modAll = document.querySelectorAll(".csomor-image-zoom")
  if(modAll){
    Array.prototype.forEach.call(modAll, (m)=>{
      m.style.backgroundImage = 'url(' + m.querySelector('img').src + ')'
      m.addEventListener('click', e=>{
        if(zoomSize < 300){
          zoomSize += 50
        }
        else{
          zoomSize = 150
        }
      })
      m.addEventListener('mousemove', e=>{
        m.querySelector('img').style.opacity = 0
        zoom(e)
      })
      m.addEventListener('mouseleave', e=>{
        m.querySelector('img').style.opacity = 1
        normalize(e)
      })
    })
  }
})

/*
** Zoom function
*/
function zoom(e){
  var zoomer = e.currentTarget;
  let offsetX = e.offsetX ? e.offsetX : e.touches[0].pageX
  let offsetY = e.offsetY ? e.offsetY : e.touches[0].pageY
  let x = offsetX/zoomer.offsetWidth*100
  let y = offsetY/zoomer.offsetHeight*100
  zoomer.style.backgroundPosition = x + '% ' + y + '%';
  zoomer.style.backgroundSize = zoomSize + '%';
}

/*
** Normalize function
*/
function normalize(e){
  var zoomer = e.currentTarget;
  zoomer.style.backgroundPosition = '50% 50%';
  zoomer.style.backgroundSize = '100%';
}