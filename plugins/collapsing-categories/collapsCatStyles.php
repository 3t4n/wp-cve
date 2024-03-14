<?php
$styleOptions = array(
'kubrick' => 'Kubrick',
'twentyten' => 'Twenty Ten',
'block_left' => 'Block Left',
'block_right' => 'Block Right',
'noarrows' => 'No arrows'
);

$defaultStyles= array(
'kubrick' => "{ID} span.collapsing-categories {
        border:0;
        padding:0;
        margin:0;
        cursor:pointer;
}

{ID} li.widget_collapscat h2 span.sym {float:right;padding:0 .5em}
{ID} li.collapsing-categories.self a {font-weight:bold}
{ID}:before {content:'';}
{ID}  li.collapsing-categories:before {content:'';}
{ID}  li.collapsing-categories {list-style-type:none}
{ID}  li.collapsing-categories{
       padding:0 0 0 1em;
       text-indent:-1em;
}
{ID} li.collapsing-categories.item:before {content: '\\00BB \\00A0' !important;}
{ID} li.collapsing-categories .sym {
   cursor:pointer;
   font-size:1.1em;
   font-family:Arial, Helvetica;
    padding-right:5px;}",

'block_left' => "{ID} li a {
            display:block;
            text-decoration:none;
            margin:0;
            width:100%;
            padding:0 10em 0 1em;
            }
{ID}.collapsing-categories, {ID} li.collapsing-categories ul {
margin-left:0;
padding:0;

}
{ID} li li a {
padding-left:1em;
}
{ID} li li li a {
padding-left:2em;
}
{ID} li a:hover {
            text-decoration:none;
          }
{ID} span.collapsing-categories {
        border:0;
        padding:0;
        margin:0;
        cursor:pointer;
}

{ID} li.widget_collapscat h2 span.sym {float:right;padding:0 .5em}
{ID} li.collapsing-categories.self a {
 font-weight:bold;
}
{ID}:before {content:'';}
{ID} li.collapsing-categories {
list-style-type:none;
}
{ID} li.collapsing-categories.item:before,
  {ID} li.collapsing-categories:before {
       content:'';
  }
{ID}  li.collapsing-categories .sym {
   cursor:pointer;
   font-size:1.1em;
   font-family:Arial, Helvetica;
    float:left;
    padding-right:5px;
}",

'block_right' => "{ID} li a {
            display:block;
            text-decoration:none;
            margin:0;
            width:100%;
            padding:0 10em 0 1em;
            }
{ID}.collapsing-categories, {ID} li.collapsing-categories ul {
margin-left:0;
padding:0;

}
{ID} li li a {
padding-left:1em;
}
{ID} li li li a {
padding-left:2em;
}
{ID} li a:hover {
            text-decoration:none;
          }
{ID} span.collapsing-categories {
        border:0;
        padding:0;
        margin:0;
        cursor:pointer;
}

{ID} li.widget_collapscat h2 span.sym {float:right;padding:0 .5em}
{ID} span.sym {
float:right;
}
{ID} li.collapsing-categories.self a {
 font-weight:bold;
}
{ID}:before {content:'';}
{ID} li.collapsing-categories {
list-style-type:none;
}
{ID} li.collapsing-categories.item:before,
  {ID} li.collapsing-categories:before {
       content:'';
  }
{ID}  li.collapsing-categories .sym {
  /*
   cursor:pointer;
   font-size:1.1em;
    float:left;
   font-family:Arial, Helvetica;
    padding-right:5px;
    */
}",

'twentyten' => "
{ID} span.collapsing-categories {
        border:0;
        padding:0;
        margin:0;
        cursor:pointer;
}

{ID} h3 span.sym {float:right;padding:0 .5em}
{ID} li.collapsing-categories.self > a {font-weight:bold}
{ID}:before {content:'';}
{ID} li.collapsing-categories.expandable:before {content:'';}
{ID} li.collapsing-categories {
  background:none;
  position:relative;
  top:0;
  bottom:0;
  right:0;
  left:0;
}
{ID} li.collapsing-categories.expandable {
       list-style:none;
       padding:0 0 0 .9em;
       margin-left:-1em;
       text-indent:-1.1em;
}
{ID} li.collapsing-categories.item {
  padding:0;
  text-indent:0;
}

{ID} li.collapsing-categories .sym {
   cursor:pointer;
   font-size:1.1em;
   font-family:Arial, Helvetica;
    padding-right:5px;}
",


'noArrows'=>
"{ID} span.collapsing-categories {
        border:0;
        padding:0;
        margin:0;
        cursor:pointer;
}
{ID} li.collapsing-categories.self a {font-weight:bold}

{ID} li.widget_collapscat h2 span.sym {float:right;padding:0 .5em}
{ID}:before {content:'';}
{ID} li.collapsing-categories:before {content:'';}
{ID} li.collapsing-categories {list-style-type:none}
{ID} li.collapsing-categories .sym {
   cursor:pointer;
   font-size:1.1em;
   font-family:Arial, Helvetica;
    padding-right:5px;",
'theme' => "",
    );
?>
