function seo_breadcrumbs() {
var elem=document.getElementsByClassName('crumb');
elem[elem.length-1].setAttribute('class','current'); }
// Adding current class to last list item in breadcrumbs.
seo_breadcrumbs();