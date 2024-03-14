








const MAX = 100



var ai_selRange, ant_im;



var gpt3_text = ""



var id_time = "";
var concepts;
concept_function = null;







function ai_concept(type, id){
	if(type=="edit"){
		if(document.getElementById("ai-desc-concept-edit").value==""){
			document.getElementById("ai-desc-concept-edit").classList.add("required");
			return;
		}else{
			document.getElementById("ai-desc-concept-edit").classList.remove("required");

		}
	}
	var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  	xmlhttp.onreadystatechange = function () {
    	if (this.readyState == 4 && this.status == 200) {
      		const consulta = JSON.parse(this.responseText);

      		window.location.reload();

		} else if (this.readyState == 4 && this.status != 200) {
			document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";

			document.getElementById("form-errors-concept").style.display = "block";
		}
	};

	xmlhttp.open("POST", "https://webator.es/gpt3_api/concept.php", true);

	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	if(type=="delete"){
		if (confirm("Are you sure you want to delete that train model?") == true) {
			xmlhttp.send("type=" + type + "&id=" + id);
		}
	}else{
		xmlhttp.send("type=" + type + "&id=" + id + "&desc=" +document.getElementById("ai-desc-concept-edit").value);

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
				for (var i = 0; i < arr.length; i+=1) {
					concept_table+=`<tr>
					<td>${arr[i].title}</td>
					<td><button class="btn btn-primary me-2" onclick="edit_concept('${arr[i].id}')"><i class="fa fa-edit"></i></button><button onclick="ai_concept('delete', ${arr[i].id})" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
					</tr>`;
				}
				$('#concepts-table').DataTable().destroy();
			$('#concepts-table').find('tbody').append(concept_table);
			$('#concepts-table').DataTable().draw();
	
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

	
	$('#ai-concept-textarea').keyup(function() {
	var length = $(this).val().length;
	$('#ai-chars').text(length);
	});

	$("#ai-add-train").on("click", function() {

		var name = $("#ai-concept-title")
		var desc = $("#ai-concept-textarea")

		//Check inputs
		if(name.val()==""){
			name.addClass("required");
			return ;
		}else{
			name.removeClass("required");
		}
		if(desc.val()==""){
			desc.addClass("required");
			return ;
		}else{
			desc.removeClass("required");
		}

		var xmlhttp = window.XMLHttpRequest
		? new XMLHttpRequest()
		: new ActiveXObject("Microsoft.XMLHTTP");

		xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const consulta = JSON.parse(this.responseText);

				if(consulta.exito){
					window.location.reload();
				}else{
					document.getElementById("form-errors-concept").innerHTML = consulta.error;

					document.getElementById("form-errors-concept").style.display = "block";
				}

			} else if (this.readyState == 4 && this.status != 200) {
				document.getElementById("form-errors-concept").innerHTML = "Oops! Something went wrong";

				document.getElementById("form-errors-concept").style.display = "block";
			}
		};

		xmlhttp.open("POST", "https://webator.es/gpt3_api/concept.php", true);

		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xmlhttp.send("type=new&title=" + name.val() + "&desc=" + desc.val());

	});















});















})
