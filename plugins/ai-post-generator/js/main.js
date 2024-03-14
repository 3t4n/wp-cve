








const MAX = 100



var ai_selRange, ant_im;

var id_queue = 0

var intervalId_table = 0;

var gpt3_text = ""



var id_time = "";
var concepts;

var all_titles=[];







function togle_info(type){
	let x = document.getElementById(type);
	if(x.style.display=="block"){
		x.style.display="none";
	}else{
		x.style.display="block";

	}
}
function edit_concept(id){

	const div = document.createElement("div");
	var text = ""
	div.setAttribute("class", "popup-container");

	div.setAttribute("id", "mail-pop");
	for(let i = 0; i < concepts.length; i++){
		if(concepts[i].id == id){
			text = concepts[i].desc;
		}
	}
	div.innerHTML = `
	<div class="ai-popup flex-column" id="boxpop">
	<textarea class="gpt3-title form-control" id="ai-desc-concept-edit" rows=3 maxlength="1000">${text}</textarea>
	<button class="btn btn-primary mt-3" onclick="ai_concept('edit','${id}')">Save</button>
  </div>`;

	document.getElementById("pop-concept-cont").appendChild(div);

	document.getElementById('mail-pop').onclick = function(e) {

		container=document.getElementById('boxpop')

		if (container !== e.target && !container.contains(e.target)) {

			document.getElementById("mail-pop").remove();

		}

	}

}

const cp = new CircleProgress('.gpt3-progress-circle', {







	max: MAX,







	value: 0,







	animationDuration: 900,







	textFormat: (val) => val + '%',







});















function loading(time){







	document.getElementById("gpt3-loading").style.display="flex";







	var intervalId = window.setInterval(function(){







		if(cp.value==100){







			cp.value=0







		}







	  cp.value++;







		cp.el.style.setProperty('--progress-value', cp.value / MAX);







	}, time*10);







	return intervalId;







}















function check_inputs(){







	var all = document.getElementsByClassName("gpt3-input");







	var ret = true







	if(document.getElementById("title").value==""){







		ret = false;







		document.getElementById("title").classList.add("required");







	}else{







		document.getElementById("title").classList.remove("required");







	}







	for(var i = 0; i < all.length; i++){







			if(all[i].value==""){







					all[i].classList.add("required");







					ret = false







			}else{







				all[i].classList.remove("required");







			}







	}







	return ret;







}







function tag(number, type){







	if(type=="open"){







		return "<h" + number.split(".").length + ">";







	}else{







		return "</h" + number.split(".").length + ">";







	}







}







function generate_table(table){







	var arr = table.split('\n');







	document.getElementById("ul-gpt3").innerHTML="";







	var title = ""







	for(var i = 0; i < arr.length; i++){







			title = arr[i].replace(i+1 + ". ", "");







			let ulElm = document.getElementById("ul-gpt3");







      let new_li = document.createElement("li");







			new_li.innerHTML=document.getElementById("levelMarksame").innerHTML;







      new_li.getElementsByClassName("level-title")[0].innerHTML = i+1 + "."







      new_li.getElementsByClassName("gpt3-input")[0].value = title







      new_li.firstChild.nextSibling.setAttribute("data-level", "A");







      ulElm.append(new_li);







		}







}







function table_of_content(x){







	document.getElementById("gpt3-text").innerHTML='';







	document.getElementById("response-gpt3").style.display= "none";







	//document.getElementById("gpt3-button").style.display="inline-block";







	var title = document.getElementById("title");







	if(title.value==""){







		title.classList.add("required");







		return ;







	}else{







		title.classList.remove("required");







	}







	var prompt1 = 'Write a table of contents for the following blog title:\n';







	prompt1 += title.value + '\n\n';







	prompt1 += 'Instructions: The table of contents may not contain the following sentence: 1. ' + title.value + '\n\n';







	prompt1 += 'Table of contents:\n';







	var prompt2 = '1.';







	var table = "";














	x.classList.add("loading");







	document.getElementById("form-errors").style.display="none";















	var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');







	xmlhttp.onreadystatechange = function() {







		if (this.readyState == 4 && this.status == 200) {





			try{

				const consulta = JSON.parse(this.responseText);



				console.log(consulta);







				x.classList.remove("loading");







				if(consulta.id){







					table = "1. " + consulta.choices[0].message.content;







					generate_table(table);







					get_info();























				}else{







					document.getElementById("form-errors").innerHTML=consulta.error;







					document.getElementById("form-errors").style.display="block";







				}



			}catch(error){

				document.getElementById("form-errors").innerHTML="Oops! Something went wrong";







				document.getElementById("form-errors").style.display="block";



				x.classList.remove("loading");



				return ;

			}







		}else if (this.readyState == 4 && this.status != 200){







			document.getElementById("form-errors").innerHTML="Oops! Something went wrong";







			document.getElementById("form-errors").style.display="block";







		}







	}







	xmlhttp.open("POST","https://webator.es/gpt3_api/gpt_scripts/gpt3-create-table.py",true);







	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");







	xmlhttp.send("prompt1=" + prompt1 + "&prompt2=" + prompt2 + "&table=1"+ "&idiom=" + title.value);







}

//SAVE SELECTED TEXT

function saveSelection() {
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            return sel.getRangeAt(0);
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange();
    }
    return null;
}
function store_saved(){
	if(document.activeElement.id !=="autowriter-editor"){
		return ;
	}
	ai_selRange = saveSelection();
}

function restoreSelection(range) {
    if (range) {
        if (window.getSelection) {
            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && range.select) {
            range.select();
        }
    }
}
function insertTextAtCursor(text) {
    var sel, range, html;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            var textNode = document.createTextNode(text) 
            range.insertNode(textNode);
            sel.removeAllRanges();
            range = range.cloneRange();
            range.selectNode(textNode);
            range.collapse(false);
            sel.addRange(range);
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.pasteHTML(text);
        range.select();
    }
}

//END SAVE SELECTED TEXT


function do_rich_images(x){
	document.getElementById("ai-rich-images").innerHTML='';

	var title = document.getElementById("ai_rich_images_input")

	if(title.value==""){

		title.classList.add("required");

		return ;

	}else{

		title.classList.remove("required");

	}

	x.classList.add("loading");

	document.getElementById("form-errors").style.display="none";

	var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

	xmlhttp.onreadystatechange = function() {

		if (this.readyState == 4 && this.status == 200) {
			try{
				const consulta = JSON.parse(this.responseText);
				console.log(consulta);

				x.classList.remove("loading");

				if (consulta.cover){


					show_imgs(consulta.cover, "ai-rich-images", ".ai-rich-im");

				}
				else{
					document.getElementById("form-errors").innerHTML=consulta.error;
					document.getElementById("form-errors").style.display="block";
				}
			}catch(error){

					x.classList.remove("loading");
					document.getElementById("form-errors").innerHTML=consulta.error;
					document.getElementById("form-errors").style.display="block";
			}

		}else if (this.readyState == 4 && this.status != 200){
			document.getElementById("form-errors").innerHTML="Oops! Something went wrong";
			document.getElementById("form-errors").style.display="block";
		}
	}
	xmlhttp.open("POST","https://webator.es/gpt3_api/gpt_scripts/gpt3-rich-image.py",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("title=" + title.value);
}


  function getSelectedText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}


function getTextBeforeCursor(contentEditable) {
	var range = window.getSelection().getRangeAt(0);
	var node = range.startContainer;
	var startPos = range.startOffset;
	var text = "";
	var charCount = 0;
	while (charCount < 200) {
	  if (node.textContent.length) {
		console.log(`Tiene texto`)
		console.log(node.tagName)
		if (node.tagName!=="SPAN"){
			text = node.textContent.substring(0, startPos) + "\n" + text;
		}else{

			text = node.textContent.substring(0, startPos) + text;
		}
		charCount += startPos;
		startPos = node.textContent.length;
	  }
	  if (!node.previousSibling) {
		console.log(`No previous sibling`);
		node = node.parentNode;
		startPos = node.childNodes.length - 1;
	  } else {
		console.log(`Sí previous sibling`);
		node = node.previousSibling;
		console.log(node.textContent);
		startPos = node.textContent.length;
	  }
	  if (node === contentEditable) {
		console.log(`node === contentEditable`);
		break;
	  }
	}
	console.log(`Final text: "${text}"`);
	return text.substr(-200).trim();
  }
  
  
  

document.addEventListener("selectionchange", function () {
	store_saved();
})

function ai_rewrite(x, type){
    document.getElementById("autowriter-editor").focus();
	restoreSelection(ai_selRange);
	//insertTextAtCursor("");
	var prompt=""

	x.classList.add("loading");
	if(type=="continue"){
		prompt =`Continue the text with a 250-word max phrase.\n"${getTextBeforeCursor(document.getElementById("autowriter-editor"))} `;


	}else{
		prompt = `Rewrites the next text in the same language, in a different form:\n"${getSelectedText()}"\nNew text:\n"`;
	}
	//console.log(prompt)
	document.getElementById("form-errors").style.display="none";
	var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

	xmlhttp.onreadystatechange = function() {

		if (this.readyState == 4 && this.status == 200) {
			
				const consulta = JSON.parse(this.responseText);
				console.log(consulta);

				x.classList.remove("loading");

				if (!consulta.error && consulta.id){


					//replaceSelectedText(consulta.choices[0].message.content.replace(/^"(.+(?="$))"$/, '$1'));
					document.execCommand("inserttext", false, consulta.choices[0].message.content.replace(/\"/g, ''));
					get_info()

				}
				else{
					document.getElementById("form-errors").innerHTML=consulta.error;
					document.getElementById("form-errors").style.display="block";
				}
			

		}else if (this.readyState == 4 && this.status != 200){
			document.getElementById("form-errors").innerHTML="Oops! Something went wrong";
			document.getElementById("form-errors").style.display="block";
		}
	}
	xmlhttp.open("POST","https://webator.es/gpt3_api/gpt_scripts/gpt3-new-rich.py",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("prompt=" + prompt + "&type=" + type);
}

function get_images(x, image = false){











	document.getElementById("ai-images").innerHTML='';







	var title = image ? document.getElementById("image-title") : document.getElementById("title");





	if(title.value==""){







		title.classList.add("required");







		return ;







	}else{







		title.classList.remove("required");







	}







	x.classList.add("loading");







	document.getElementById("form-errors").style.display="none";















	var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');







	xmlhttp.onreadystatechange = function() {







		if (this.readyState == 4 && this.status == 200) {





			try{



				const consulta = JSON.parse(this.responseText);







				console.log(consulta);







				x.classList.remove("loading");



				if (image){



					document.getElementById("mail-pop").remove();



				}







				if (consulta.cover){







					show_imgs(consulta.cover, "ai-images", ".ai-img-data");







					get_info();







				}







				else{







					document.getElementById("form-errors").innerHTML=consulta.error;







					document.getElementById("form-errors").style.display="block";







				}



			}catch(error){



					x.classList.remove("loading");



					document.getElementById("form-errors").innerHTML=consulta.error;







					document.getElementById("form-errors").style.display="block";



			}







		}else if (this.readyState == 4 && this.status != 200){







			document.getElementById("form-errors").innerHTML="Oops! Something went wrong";







			document.getElementById("form-errors").style.display="block";







		}







	}







	xmlhttp.open("POST","https://webator.es/gpt3_api/gpt_scripts/gpt3-image-new.py",true);







	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");







	xmlhttp.send("title=" + title.value);







}











function show_imgs(imgs, id_container, class_name){
	var ai_im = document.getElementById(id_container);
	imgs.forEach(im => {
		var div = document.createElement("div");
		div.setAttribute("class", "ai-img");
		div.setAttribute("data-name", im);
		div.innerHTML=`<img class="ai-img-data"  onclick="select_img(this, '${class_name}')" src="${im}">`;
		ai_im.appendChild(div);
	});
}











function select_img(im, class_name){


	if(class_name == ".ai-rich-im"){
		document.getElementById("autowriter-editor").focus();
		restoreSelection(ai_selRange);
		document.execCommand("insertimage", false, im.src);
		return ;
	}
	const imgs = document.querySelectorAll(class_name);

	if(im.classList.contains("active")){



		im.classList.remove("active");



	}else{







		imgs.forEach((i) => {



		   i.classList.remove("active");



		});



		im.classList.add("active");



	}



}

































function show_pop_img(){







	const div = document.createElement("div");







	div.setAttribute("class", "popup-container");







	div.setAttribute("id", "mail-pop");







	div.innerHTML = `







	<div class="ai-popup" id="boxpop">





	<input class="form-control gpt3-title" type="text" name="title" id="image-title" placeholder="Search image">



	<div class="ms-3">



	<svg onclick="get_images(this, true)" class="regpt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">



	<path d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/>



	</svg>



  </div>





  </div>`;







	document.getElementById("pop-img-cont").appendChild(div);





	document.getElementById('mail-pop').onclick = function(e) {







		container=document.getElementById('boxpop')







		if (container !== e.target && !container.contains(e.target)) {







			document.getElementById("mail-pop").remove();







		}







	}







}



























/*TREE*/







function create_matrix(){







	//Create array







	var all = document.getElementsByClassName("treeview__level")







	var max_index="A"







	for(var i = 1; i < all.length; i++){







		if(all[i].getAttribute("data-level")>max_index){







			max_index=all[i].getAttribute("data-level")







		}







	}







	//var matrix = []







	max_index = max_index.charCodeAt(0) - "A".charCodeAt(0) + 1







	const matrix = new Array(i).fill(0).map(() => new Array(max_index).fill(0));







	for(var i = 0; i < all.length; i++){







		for(var j = 0; j < max_index; j++){







			if(j == all[i].getAttribute("data-level").charCodeAt(0) - "A".charCodeAt(0)){







				matrix[i][j]=1







			}else{







				matrix[i][j]=0







			}







		}







	}







	return matrix







}







function put_value(matrix){







	var all = document.getElementsByClassName("treeview__level")







	for(var i = 0; i < matrix.length; i++){







		all[i].removeAttribute("data-value")







		for(var j = 0; j < matrix[0].length; j++){







			if(matrix[i][j]!=0){







				all[i].setAttribute("data-value", all[i].getAttribute("data-value") + matrix[i][j] + ".");







			}







		}







		all[i].getElementsByClassName("level-title")[0].innerHTML = all[i].getAttribute("data-value");







	}







}







function renumber(){







	const matrix = create_matrix();







	for(var j = 0; j < matrix[0].length; j++){







		for(var i = 1; i < matrix.length; i++){







			//Recorro columna por columna en vertical, empezando por la fila 1







			if(matrix[i][j]==0){







				matrix[i][j]=matrix[i-1][j]







				if(j>0 && matrix[i-1][j-1]<matrix[i][j-1]){







					matrix[i][j]=0







				}















			}







			else{







				matrix[i][j]=matrix[i-1][j]+1







				if(j>0 && matrix[i-1][j-1]<matrix[i][j-1]){







					matrix[i][j]=0







				}







			}







			//Compruebo, si el numero que miro es distinto de 0, si ha habido algún cero en su fila, para reset







			if(matrix[i][j]!=0){







				for(var col = 1; col < matrix[0].length; col++){







					if(matrix[i][col]==0 && col < j){







						matrix[i][j]=0







					}







				}







			}







		}















	}







	put_value(matrix)







}







/*END TREE*/



function replaceSelectedText(replacementText) {
    var sel, range;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            range.insertNode(document.createTextNode(replacementText));
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.text = replacementText;
    }
}



jQuery(document).ready(function($) {







  $(function() {
	function get_concepts(){
		var concept_table = "";
		var xmlhttp = window.XMLHttpRequest
		? new XMLHttpRequest()
		: new ActiveXObject("Microsoft.XMLHTTP");
	
		  xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				  const consulta = JSON.parse(this.responseText);
	
				var arr = consulta.response
				concepts = arr
				if(arr.length > 0){
					document.getElementById("ai-trained-model").style.display="flex";
				}
				for (var i = 0; i < arr.length; i+=1) {
					//OPTION SELECT
					var div = document.createElement("option");
					div.setAttribute("data-id", arr[i].id);
					div.innerHTML=arr[i].title;
					document.getElementById("ai-train-select").appendChild(div);
				}
			} else if (this.readyState == 4 && this.status != 200) {
				document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";
	
				document.getElementById("form-errors-concept").style.display = "block";
			}
		};
	
		xmlhttp.open("GET", "https://webator.es/gpt3_api/get_concepts.php", true);
	
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
		xmlhttp.send();
	}
	get_concepts();
	function get_titles(){
		var is_creating = 0;
		all_titles = []
		$('#concepts-table').DataTable().destroy();
		$('#ai-train-tbody').empty();
		var concept_table = "";
		$.ajax({
			url : 'admin-ajax.php',
			type: 'get',
			dataType: 'json',
			data: {
			  action: 'ai_post_generator_get_Posts'
			},
			success: function(data) {

				if(data.array.length){
					all_titles.push(...data.array)
				}
				var xmlhttp = window.XMLHttpRequest
				? new XMLHttpRequest()
				: new ActiveXObject("Microsoft.XMLHTTP");
			
				xmlhttp.onreadystatechange = function () {
					if (this.readyState == 4 && this.status == 200) {
						const consulta = JSON.parse(this.responseText);
			
						all_titles.push(...consulta.response)
						var arr = all_titles
						for (var i = 0; i < arr.length; i+=1) {
							concept_table+=`<tr>
							<td>${arr[i].title}</td>`;
							if(arr[i].status =="to create"){
								concept_table+=`<td>Pending</td>`;
							}else if(arr[i].status =="created"){
								concept_table+=`<td>Ready</td>`;
							}else if(arr[i].status =="draft"){
								concept_table+=`<td>Draft</td>`;
							}else if(arr[i].status =="publish"){
								concept_table+=`<td>Published</td>`;
							}else{
								concept_table+=`<td>${arr[i].status}</td>`;
							}
							if(arr[i].status == "to create"){
								concept_table+= `<td>
								<span class="ai-create-title me-2" data-id="${arr[i].id}" data-n="${i}">
								<i class="fa fa-plus p-2 ai-clickable ai-blue">
								</i>
								</span>
								<span class="ai-delete-title" data-id="${arr[i].id}">
								<i  class="fa fa-trash p-2 ai-clickable ai-red" >
								</i>
								</span>
								</td>`;
							}else if(arr[i].status == "created"){
								concept_table+= `<td>
								<span class="ai-create-post me-2" data-id="${arr[i].id}" data-n="${i}">
								<i class="fa fa-eye p-2 ai-clickable ai-blue">
								</i>
								</span>
								<span class="ai-delete-title" data-id="${arr[i].id}">
								<i  class="fa fa-trash p-2 ai-clickable ai-red" >
								</i>
								</span>
								</td>`;

							}else if(arr[i].status == "creating"){
								is_creating = 1;
								concept_table+= `<td>
								<i class="fa fa-spinner me-2 p-2 ai-blue load-titles load">
								</i>
								<span class="ai-delete-title" data-id="${arr[i].id}">
								<i  class="fa fa-trash p-2 ai-clickable ai-red" >
								</i>
								</span>
								</td>`;

							}else{
								concept_table+= `<td>
								<span class="ai-show-post me-2" data-id="${arr[i].id}" data-n="${i}">
								<i class="fa fa-eye p-2 ai-clickable ai-blue">
								</i>
								</span>
								<span class="ai-delete-post" data-id="${arr[i].id}" data-n="${i}">
								<i  class="fa fa-trash p-2 ai-clickable ai-red" >
								</i>
								</span>
								</td>`;
							}
							concept_table+= `<td>${arr[i].date}</td>
							</tr>`;
						}
						$('#concepts-table').DataTable().destroy();
					$('#concepts-table').find('tbody').append(concept_table);
					$('#concepts-table').DataTable().draw();
					//Order by date clicking twice
					$('#ai-title-date').click();
					$('#ai-title-date').click();
					
					add_functions_to_table(arr);

					if(is_creating){
						//Reload table each 20 secs
						if(intervalId_table){
							clearInterval(intervalId_table)
						}
						intervalId_table = window.setInterval(function(){
							get_titles();
						  }, 20000);
					}else{
						if(intervalId_table){
							clearInterval(intervalId_table)
						}
					}

			
					} else if (this.readyState == 4 && this.status != 200) {
						document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";
			
						document.getElementById("form-errors-concept").style.display = "block";
					}
				};
			
				xmlhttp.open("GET", "https://webator.es/gpt3_api/get_posts.php", true);
			
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			
				xmlhttp.send();
				//If there is any title creating and interval is not set yet, interval function is created
			}
		});
	}
	get_titles();
	function add_functions_to_table(arr){

		$(".ai-delete-title").off("click");
		$(".ai-delete-title").on("click", function() {
			delete_title($(this).attr("data-id"))
		});

		$(".ai-create-post").off("click");
		$(".ai-create-post").on("click", function() {
			$(this).off("click");
			$(this).addClass("load-titles load");
			create_post(arr[$(this).attr("data-n")]);
		});

		$(".ai-delete-post").off("click");
		$(".ai-delete-post").on("click", function() {
			delete_post(arr[$(this).attr("data-n")]);
		});

		$(".ai-show-post").off("click");
		$(".ai-show-post").on("click", function() {
			show_post(arr[$(this).attr("data-n")]);
		});

		$(".ai-create-title").off("click");
		$(".ai-create-title").on("click", function() {
			var tit = arr[$(this).attr("data-n")].title
			$('#pills-home-tab').click()
			$('#title').val(tit)
			id_queue = $(this).attr("data-id")

		});
	}

	$('#concepts-table').on( 'draw.dt', function () {
		add_functions_to_table(all_titles)
	} );

	function create_post(post){
		$.ajax({
			url : 'admin-ajax.php',
			type: 'post',
			dataType: 'json',
			data: {
			  action: 'ai_post_generator_data_Publish', title: post.title, text: post.text, type : "draft", im : post.cover, date : post.date
			},
			success: function(data) {
				delete_title(post.id, false)
				//Show post
				document.getElementById("response-editor-autowriter").style.display = "block";
				document.getElementById("autowriter-editor").innerHTML = data.content;
				document.getElementById("ai-iframe").style.display = "block";
				document.getElementById("ai-iframe").src = data.url;
				document.getElementById("ai-iframe").setAttribute("data-id", data.id);
				document.getElementById("pills-preview-tab").setAttribute("data-url", data.url);
				document.getElementById("response-gpt3-buttons").style.display = "flex";
				
			}
		});
	}
	function delete_post(post){
		if (confirm("Are you sure you want to delete that post?") == true) {
			$.ajax({
				url : 'admin-ajax.php',
				type: 'post',
				dataType: 'json',
				data: {
				action: 'ai_post_generator_delete_Post', id: post.id
				},
				success: function(data) {
					get_titles();
				}
			});
		}
	}
	//Delete titles
	function delete_title(id, do_confirm = true){

		var xmlhttp = window.XMLHttpRequest
		? new XMLHttpRequest()
		: new ActiveXObject("Microsoft.XMLHTTP");
	
		  xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				  const consulta = JSON.parse(this.responseText);
	
				  get_titles();
	
			} else if (this.readyState == 4 && this.status != 200) {
				document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";
	
				document.getElementById("form-errors-concept").style.display = "block";
			}
		};
	
		xmlhttp.open("POST", "https://webator.es/gpt3_api/delete_title.php", true);
	
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		if (do_confirm){
			if (confirm("Are you sure you want to delete that blog title?") == true) {
				xmlhttp.send("id=" + id);
			}
		}else{
			xmlhttp.send("id=" + id);
		}
	}

	$("#ai-close-editor").on("click", function() {
		document.getElementById("response-editor-autowriter").style.display="none";
	});

	//PWIJEFPBIEJFFBPEI

	var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];
	var forePalette = $('.fore-palette');
	var backPalette = $('.back-palette');

	for (var i = 0; i < colorPalette.length; i++) {
	  forePalette.append('<button data-command="forecolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></button>');
	  backPalette.append('<button data-command="backcolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></button>');
	}


	$('.toolbar button').click(function(e) {
	  var command = $(this).data('command');
	  if (command == 'h1' || command == 'h2' || command == 'h3' || command == 'p') {
		document.execCommand('formatBlock', false, command);
	}
	if (command == 'replace') {
		  replaceSelectedText("hola");
	}
	  if (command == 'forecolor' || command == 'backcolor') {
		document.execCommand($(this).data('command'), false, $(this).data('value'));
	  }
		if (command == 'createlink' || command == 'insertimage') {
	  url = prompt('Enter the link here: ','http:\/\/'); document.execCommand($(this).data('command'), false, url);
	  }
	  else document.execCommand($(this).data('command'), false, null);
	});
	//KJBWOKRJBERKJIERHRR

	$('#ai-richeditor-show').click(function() {
		$('#ai-richeditor-show').toggleClass("ai-show ai-hide");
		$('#ai-richeditor').toggle(0);
	});

	$('#ai-new-content-plan').click(function() {
		$('#new-content-plan').toggleClass("ai-show-block ai-show-none");
		$('#ai-richeditor').toggle(0);
	});
	$('#n_titles').on('input', function(){
		var newval=$(this).val();
		$("#n_titles_value").text(newval);
		$("#ai-cost-titles").text(newval/10);
	  });



	$("#ai-add-train").on("click", function() {

		var title = $("#ai-concept-title")
		var idiom = $("#ai-concept-idiom")
		var n_titles = $("#n_titles").val()

		//Check inputs
		if(title.val()==""){
			title.addClass("required");
			return ;
		}else{
			title.removeClass("required");
		}
		if(idiom.val()==""){
			idiom_val = "English";
		}else{
			idiom_val = idiom.val();
		}
		$("#load-titles").toggleClass("none load");

		var xmlhttp = window.XMLHttpRequest
		? new XMLHttpRequest()
		: new ActiveXObject("Microsoft.XMLHTTP");

		xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				$("#load-titles").toggleClass("none load");
				const consulta = JSON.parse(this.responseText);

				if(consulta){
					get_info();
					$('#new-content-plan').toggleClass("ai-show-block ai-show-none");
					get_titles();
				}else{
					document.getElementById("form-errors-concept").innerHTML = consulta.error;

					document.getElementById("form-errors-concept").style.display = "block";
				}

			} else if (this.readyState == 4 && this.status != 200) {
				document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";

				document.getElementById("form-errors-concept").style.display = "block";
			}
		};

		xmlhttp.open("POST", "https://webator.es/gpt3_api/gpt_scripts/gpt3-contentplan.py", true);

		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xmlhttp.send("title=" + title.val() + "&idiom=" + idiom_val+ "&n_titles=" + n_titles);

	});


	function show_post(post){
		document.getElementById("response-editor-autowriter").style.display = "block";
		document.getElementById("autowriter-editor").innerHTML = post.content;
		document.getElementById("gpt3-loading").style.display="none";

		document.getElementById("ai-iframe").style.display = "block";
		document.getElementById("ai-iframe").src = post.url;
		document.getElementById("ai-iframe").setAttribute("data-id", post.id);
		document.getElementById("pills-preview-tab").setAttribute("data-url", post.url);
		if(post.status=="draft"){
			document.getElementById("response-gpt3-buttons").style.display = "flex";
		}else{
			document.getElementById("response-gpt3-buttons").style.display = "none";
		}
	}







  let treeview = {







    resetBtnToggle: function() {







      $(".js-treeview")







        .find(".level-add")







        .siblings()







        .removeClass("in");







    },







    addSameLevel: function(target) {







      let ulElm = target.closest("ul");







      let liElm = target.closest("li")[0];







      let sameLevelCodeASCII = target







        .closest("[data-level]")







        .attr("data-level")







        .charCodeAt(0);







        console.log(liElm);







        var new_li = document.createElement("li");







        new_li.innerHTML=$("#levelMarksame").html();







        new_li.firstChild.nextSibling.setAttribute("data-level", String.fromCharCode(sameLevelCodeASCII));







      liElm.parentNode.insertBefore(new_li, liElm.nextSibling);







    },







    addSubLevel: function(target) {







      let liElm = target.closest("li");







      let nextLevelCodeASCII = liElm.find("[data-level]").attr("data-level").charCodeAt(0) + 1;







      liElm.children("ul").append($("#levelMarkup").html());







      liElm.children("ul").find("[data-level]")







        .attr("data-level", String.fromCharCode(nextLevelCodeASCII));







    },







    removeLevel: function(target) {







      target.closest("li").remove();















    }







  };















  // Treeview Functions







  $(".js-treeview").on("click", ".level-add", function() {







		$(this).find("div").siblings().toggleClass("in");







  });















  // Add same level







  $(".js-treeview").on("click", ".level-same", function() {







    treeview.addSameLevel($(this));







    treeview.resetBtnToggle();







		renumber()







  });















  // Add sub level







  $(".js-treeview").on("click", ".level-sub", function() {







    treeview.addSubLevel($(this));







    treeview.resetBtnToggle();







		renumber()







  });







    // Remove Level







  $(".js-treeview").on("click", ".level-remove", function() {







    treeview.removeLevel($(this));







		renumber()







  });















  // Selected Level







  $(".js-treeview").on("click", ".level-title", function() {







    let isSelected = $(this).closest("[data-level]").hasClass("selected");







    !isSelected && $(this).closest(".js-treeview").find("[data-level]").removeClass("selected");







    $(this).closest("[data-level]").toggleClass("selected");







  });















  $("#gpt3-button-create").on("click", function() {


  	gpt3_all('draft');







  });



    $("#gpt3-button-re").on("click", function() {



  	gpt3_all('draft');







  });




    $("#gpt3-button-publish").on("click", function() {
		save_as_publish($(this));

  });





  function gpt3_all(type){







		if(!check_inputs()){



			return ;







		}





		document.getElementById("ai-iframe").style.display= "none";



		document.getElementById("ai-iframe").src = "";



		document.getElementById("ai-iframe").setAttribute("data-id", "");





		document.getElementById("response-gpt3").style.display= "none";









		var title = document.getElementById("title").value;


		var im = $(".ai-img-data.active").attr('src');
		if (im === undefined){
			im = false;
		}




		var prompt1 = 'Write an extensive and detailed blog with the following title:\n';







		prompt1 += title + '\n';
		var selected_train = document.getElementById("ai-train-select")
		var train_id = selected_train.options[selected_train.selectedIndex].getAttribute('data-id');


		prompt1 += 'TABLE OF CONTENT\n';







		var all = document.getElementsByClassName("gpt3-input")







		var number = 1;







		for(var i = 0; i < all.length; i++){







			prompt1+= tag(all[i].previousElementSibling.innerHTML, "open") + all[i].value + tag(all[i].previousElementSibling.innerHTML, "close") + '\n';







			if(all[i].previousElementSibling.innerHTML.split('.').length==2){







				number = parseInt(all[i].previousElementSibling.innerHTML.slice(0,-1));







			}







		}















		number++;







		//prompt1+= number + ". <end>" + all[i-1].value + '</end>\n';







		prompt1+= "<end>" + all[i-1].value + '</end>\n';















		//prompt1+='\nUse <b> for some phrases\n';




		if (train_id){
			for(let i = 0; i < concepts.length; i++){
				if(concepts[i].id == train_id){
					train_desc = concepts[i].desc;
					prompt1+=`Use the following trained model concept:\n"${train_desc}"\n`;
				}
			}
		}


		var prompt2 ='<h2>' + all[0].value + '</h2>\n';







		prompt2+='<p><b>';

		var first = '<h2>' + document.getElementsByClassName("gpt3-input")[0].value + '</h2>\n<p><b>';







		//console.log(prompt1 + '\nBlog language: es.\n' + prompt2);




		//document.getElementById("gpt3-button-draft").style.display="none";







		document.getElementById("gpt3-button-create").style.display="none";







		document.getElementById("form-errors").style.display="none";







		cp.value = 0;







		//id_time = loading(90);
		document.getElementById("loader").style.display="block";







		document.getElementById("gpt3-text").innerHTML='';







		document.getElementById("response-gpt3").style.display= "none";







		var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');







		xmlhttp.onreadystatechange = function() {







			if (this.readyState == 4 && this.status == 200) {





				try{

					const consulta = JSON.parse(this.responseText);







					get_info();















					if(consulta.exito){
						
						document.getElementById("response-editor-autowriter").style.display="none"
						document.getElementById("pills-train-tab").click();
						get_titles();
						//Reset principal screen
						document.getElementById("ul-gpt3").innerHTML=`
						<li>

										<div class="treeview__level" data-level="A" data-value="1">

											<div class="treeview__level-btns me-2">

												<div class="btn btn-default btn-sm level-add"><span
														class="fa fa-plus"></span>

													<div class="gpt3-buttons">

														<div class="btn btn-default btn-sm level-same"><span
																class="fa fa-arrow-down"></span></div>

														<div class="btn btn-default btn-sm level-sub"><span
																class="fa fa-arrow-right"></span></div>

														<div class="btn btn-default btn-sm level-remove"><span
																class="fa fa-trash text-danger"></span></div>

													</div>

												</div>

											</div>

											<span class="level-title mx-2">1.</span>

											<input class="gpt3-input" type="text">

										</div>

										<ul class="ul-gpt3">

										</ul>

									</li>
						`;
						document.getElementById("title").value="";
						document.getElementById("loader").style.display="none";
						document.getElementById("ai-images").innerHTML='';
						document.getElementById("gpt3-button-create").style.display="block";
						document.getElementById("form-errors").innerHTML='';
						document.getElementById("form-errors").style.display="none";

					
					}else{
						document.getElementById("response-editor-autowriter").style.display = "none";
						document.getElementById("gpt3-loading").style.display="none";
						document.getElementById("loader").style.display="none";
						document.getElementById("form-errors").innerHTML=consulta.error;
						document.getElementById("form-errors").style.display="block";




					}



				}catch( error ){



					document.getElementById("gpt3-button-create").style.display="inline-block";



					document.getElementById("gpt3-loading").style.display="none";

					document.getElementById("loader").style.display="none";




					document.getElementById("form-errors").innerHTML="Oops! Something went wrong";



					document.getElementById("form-errors").style.display="block";



				}







			}else if (this.readyState == 4 && this.status != 200){







				document.getElementById("gpt3-loading").style.display="none";

				document.getElementById("loader").style.display="none";









				document.getElementById("form-errors").innerHTML="Oops! Something went wrong";







				document.getElementById("form-errors").style.display="block";













			}







		}







		xmlhttp.open("POST","https://webator.es/gpt3_api/gpt_scripts/gpt3-create-post.py",true);







		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");







		xmlhttp.send("prompt1=" + prompt1 + "&prompt2=" + prompt2 + "&stop=" + number+ "&idiom=" + title+ "&image=" + im + "&id=" + id_queue + "&first=" + first);



  }
	document.getElementById("ai-iframe").style.display = "block";
	document.getElementById("ai-iframe").src = "?p=21";


  function save_ajax(text){
	var title = document.getElementById("title").value;
	var im = $(".ai-img-data.active").attr('src');
  	if (im === undefined){
  		im = false;
  	}else{
		ant_im = im;
	}
		$.ajax({
	    url : 'admin-ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: {
	      action: 'ai_post_generator_data_Publish', title: title, text: text, type : "draft", im : im
	    },
	    success: function(data) {
	    	console.log(data)
	      if(data.exito){
				document.getElementById("response-editor-autowriter").style.display = "block";
				document.getElementById("autowriter-editor").innerHTML = text;
				document.getElementById("gpt3-loading").style.display="none";
				clearInterval(id_time);
				document.getElementById("gpt3-button-create").style.display= "none";
				document.getElementById("response-gpt3").style.display= "block";

	      		document.getElementById("ai-iframe").style.display = "block";
	      		document.getElementById("ai-iframe").src = "/?p=" + data.id;
	      		document.getElementById("ai-iframe").setAttribute("data-id", data.id);
			}else{
				document.getElementById("gpt3-loading").style.display="none";
				clearInterval(id_time);
				document.getElementById("form-errors").innerHTML="Oops! Something went wrong";
			}
	    }
	  });
}
$("#pills-preview-tab").on("click", function() {

	preview($(this).attr("data-url"));

});
function preview(url){
	document.getElementById("ai-iframe").style.display="none";
	document.getElementById("autowriter-load-frame").style.display="flex";
	var id = document.getElementById("ai-iframe").getAttribute("data-id");
	var text = document.getElementById("autowriter-editor").innerHTML;

		$.ajax({
	    url : 'admin-ajax.php',
	    type: 'post',
	    dataType: 'json',
	    data: {
	      action: 'ai_post_generator_data_Preview', id: id, text: text
	    },
	    success: function(data) {
	    	console.log(data)
			if(data.exito){
				document.getElementById("ai-iframe").style.display="block";
				document.getElementById("autowriter-load-frame").style.display="none";
	      		document.getElementById("ai-iframe").src = url;
			}else{
				document.getElementById("ai-iframe").style.display="none";
				document.getElementById("autowriter-load-frame").style.display="none";
				document.getElementById("gpt3-loading").style.display="none";
				clearInterval(id_time);
				document.getElementById("form-errors").innerHTML="Oops! Something went wrong";
			}
	    }
	  });
}




function save_as_publish(x){
	var id = document.getElementById("ai-iframe").getAttribute("data-id");
	var text = document.getElementById("autowriter-editor").innerHTML;
	x.addClass("loading");

	$.ajax({
	    url : 'admin-ajax.php',

	    type: 'post',

	    dataType: 'json',

	    data: {

	      action: 'ai_post_generator_saveas_Publish', id: id, text: text

	    },

	    success: function(data) {

			x.removeClass("loading");
			document.getElementById("response-editor-autowriter").style.display="none";
			document.getElementById("response-gpt3-buttons").style.display="none";

	    	get_titles();
		}



	  });

  }






});















})
