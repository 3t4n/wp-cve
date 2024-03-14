<?php
  if ($expand==1) {
    $expandSym='+';
    $collapseSym='—';
  } elseif ($expand==2) {
    $expandSym='[+]';
    $collapseSym='[—]';
  } elseif ($expand==3) {
    $expandSym="<img src=\"".
         "/wp-content/plugins/collapsing-categories/" .
         "img/expand.gif\" alt=\"expand\" />";
    $collapseSym="<img src=\"".
         "/wp-content/plugins/collapsing-categories/" .
         "img/collapse.gif\" alt=\"collapse\" />";
  } elseif ($expand==5) {
    $collapseSym='<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" role="img" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>';
    $expandSym='<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" role="img" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>';
  } elseif ($expand==4) {
    $expandSym=$customExpand;
    $collapseSym=$customCollapse;
  } else {
    $expandSym='&#x25BA;';
    $collapseSym='&#x25BC;';
  }
?>
