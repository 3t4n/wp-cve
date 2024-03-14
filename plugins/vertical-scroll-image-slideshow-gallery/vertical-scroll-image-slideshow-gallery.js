//////////////////////Vertical scroll image slideshow gallery/////////////////////////////////////////////

var ie=document.all
var dom=document.getElementById

if (vs_slideimages.length>2)
vs_i=2
else
vs_i=0

function vs_move1(whichlayer){
tlayer=eval(whichlayer)
if (tlayer.top>0&&tlayer.top<=5){
tlayer.top=0
setTimeout("vs_move1(tlayer)",vs_pausebetweenimages)
setTimeout("vs_move2(document.vs_main.document.vs_second)",vs_pausebetweenimages)
return
}
if (tlayer.top>=tlayer.document.height*-1){
tlayer.top-=5
setTimeout("vs_move1(tlayer)",50)
}
else{
tlayer.top=parseInt(vs_scrollerheight)
tlayer.document.write(vs_slideimages[vs_i])
tlayer.document.close()
if (vs_i==vs_slideimages.length-1)
vs_i=0
else
vs_i++
}
}

function vs_move2(whichlayer){
tlayer2=eval(whichlayer)
if (tlayer2.top>0&&tlayer2.top<=5){
tlayer2.top=0
setTimeout("vs_move2(tlayer2)",vs_pausebetweenimages)
setTimeout("vs_move1(document.vs_main.document.vs_first)",vs_pausebetweenimages)
return
}
if (tlayer2.top>=tlayer2.document.height*-1){
tlayer2.top-=5
setTimeout("vs_move2(tlayer2)",50)
}
else{
tlayer2.top=parseInt(vs_scrollerheight)
tlayer2.document.write(vs_slideimages[vs_i])
tlayer2.document.close()
if (vs_i==vs_slideimages.length-1)
vs_i=0
else
vs_i++
}
}

function vs_move3(whichdiv){
tdiv=eval(whichdiv)
if (parseInt(tdiv.style.top)>0&&parseInt(tdiv.style.top)<=5){
tdiv.style.top=0+"px"
setTimeout("vs_move3(tdiv)",vs_pausebetweenimages)
setTimeout("vs_move4(vs_second2_obj)",vs_pausebetweenimages)
return
}
if (parseInt(tdiv.style.top)>=tdiv.offsetHeight*-1){
tdiv.style.top=parseInt(tdiv.style.top)-5+"px"
setTimeout("vs_move3(tdiv)",50)
}
else{
tdiv.style.top=vs_scrollerheight
tdiv.innerHTML=vs_slideimages[vs_i]
if (vs_i==vs_slideimages.length-1)
vs_i=0
else
vs_i++
}
}

function vs_move4(whichdiv){
tdiv2=eval(whichdiv)
if (parseInt(tdiv2.style.top)>0&&parseInt(tdiv2.style.top)<=5){
tdiv2.style.top=0+"px"
setTimeout("vs_move4(tdiv2)",vs_pausebetweenimages)
setTimeout("vs_move3(vs_first2_obj)",vs_pausebetweenimages)
return
}
if (parseInt(tdiv2.style.top)>=tdiv2.offsetHeight*-1){
tdiv2.style.top=parseInt(tdiv2.style.top)-5+"px"
setTimeout("vs_move4(vs_second2_obj)",50)
}
else{
tdiv2.style.top=vs_scrollerheight
tdiv2.innerHTML=vs_slideimages[vs_i]
if (vs_i==vs_slideimages.length-1)
vs_i=0
else
vs_i++
}
}

function startscroll(){
if (ie||dom){
vs_first2_obj=ie? vs_first2 : document.getElementById("vs_first2")
vs_second2_obj=ie? vs_second2 : document.getElementById("vs_second2")
vs_move3(vs_first2_obj)
vs_second2_obj.style.top=vs_scrollerheight
vs_second2_obj.style.visibility='visible'
}
else if (document.layers){
document.vs_main.visibility='show'
vs_move1(document.vs_main.document.vs_first)
document.vs_main.document.vs_second.top=parseInt(vs_scrollerheight)+5
document.vs_main.document.vs_second.visibility='show'
}
}

window.onload=startscroll
