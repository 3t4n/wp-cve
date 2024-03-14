const babc_color_inputs_list = document.getElementsByClassName("color-inputs");
const babc_check_inputs_list = document.getElementsByClassName("check-inputs");
const babc_txt_cover_input = document.getElementsByClassName("txt-cover-input");
const babc_check_cover_input = document.getElementsByClassName("check-cover-input");

const babc_main_div = document.getElementById("babc_main_div");
const babc_inp_all = document.getElementById("input_all");
const babc_cbox_all = document.getElementById("cbox_all");
const babc_txt_inp_all = document.getElementById("txt-cover-inp-all");

const babc_search_inp_ge = document.getElementById("search_page_inp");
const babc_search_inp_st = document.getElementById("search_post_inp");

if(babc_cbox_all.checked){
  babc_all_inputs_cover();
  babc_inp_all.style.display = "block";
  babc_txt_inp_all.style.display = "none";
}; // block all inputs if all selected or not, on page start
if(!babc_cbox_all.checked){
  babc_all_inputs_cover();
  babc_inp_all.style.display = "none";
  babc_txt_inp_all.style.display = "block";
}; // block all inputs if all selected or not, on page start

function babc_disable_unselected(){
  for(let i = 0; i < babc_color_inputs_list.length; i++){
      if(!babc_check_inputs_list[i].checked){
        babc_color_inputs_list[i].style.display = "none";
        babc_txt_cover_input[i].style.display = "block";
      }else if(babc_check_inputs_list[i].checked){
        babc_color_inputs_list[i].style.display = "block";
        babc_check_inputs_list[i].style.display = "inline-block";
        babc_check_cover_input[i].style.display = "none";
          babc_txt_cover_input[i].style.display = "none";
      }
  }  
}; // loop and disable on page start
babc_disable_unselected();

babc_cbox_all.addEventListener("click",()=>{
  babc_inp_all.disabled = !babc_cbox_all.checked;
    if(!babc_cbox_all.checked){
      babc_all_inputs_cover();
      babc_inp_all.style.display = "none";
      babc_txt_inp_all.style.display = "block";
    }else if(babc_cbox_all.checked){
      babc_all_inputs_cover();
      babc_inp_all.style.display = "block";
      babc_txt_inp_all.style.display = "none";
    }
});

function babc_all_inputs_cover(){
  for(let i = 0; i < babc_color_inputs_list.length; i++){
      if(babc_cbox_all.checked){
        babc_color_inputs_list[i].style.display = "none";
        babc_check_inputs_list[i].style.display = "none";
        babc_check_cover_input[i].style.display = "inline-block";
        babc_txt_cover_input[i].style.display = "block";
      }
      if(!babc_cbox_all.checked){
        babc_check_cover_input[i].style.display = "none";
        babc_check_inputs_list[i].style.display = "inline-block";

          if(babc_check_inputs_list[i].checked){
            babc_color_inputs_list[i].style.display = "block";
            babc_txt_cover_input[i].style.display = "none";
          } // recover the color only if already selected
      }
  };
}

// Clicking one page:
for(let i = 0; i < babc_check_inputs_list.length; i++){
  babc_check_inputs_list[i].addEventListener("click",()=>{
      if(!babc_cbox_all.checked){
            if(babc_check_inputs_list[i].checked){
              babc_color_inputs_list[i].style.display = "block";
              babc_txt_cover_input[i].style.display = "none";
            }
            if(!babc_check_inputs_list[i].checked){
              babc_color_inputs_list[i].style.display = "none";
              babc_txt_cover_input[i].style.display = "block";
            }  
      }
  });
};

function displaySettings(){
  babc_main_div.style.visibility  = "visible";
}
displaySettings();//display the main div after building all inputs set

babc_search_inp_ge.onkeyup = ()=>{babc_search_in("ge")};
babc_search_inp_st.onkeyup = ()=>{babc_search_in("st")};

function babc_search_in(type){
  const str_s = (type === "ge") ? babc_search_inp_ge.value : babc_search_inp_st.value;
  const items_for_s = document.getElementsByClassName("item_"+type);

  Array.prototype.forEach.call(items_for_s, function (item) {
    if(!item.getAttribute("data-sename").toLowerCase().indexOf(str_s.toLowerCase())> -1){
      item.style.display = "none";
    }
    if(item.getAttribute("data-sename").toLowerCase().indexOf(str_s.toLowerCase())> -1){
      item.style.display = "block";
    }
  });
}