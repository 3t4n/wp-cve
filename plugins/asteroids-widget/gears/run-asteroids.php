<?php

  // Insert Image			
  switch ($asteroids_imageopt) {
    case "image-1":
      echo '<div align="center"><a href="#" onclick="'.$asteroids_start.'" >
            <img src="'.$asteroids_mainimage.'" width="190" height="100" border="0" alt="Asteroids Wordpress Widget" />
            </a></div>';
    break;
    
    case "image-2":
    /* Option 2 uses simple image hovering */	
      echo '<div align="center">
            <a href="#"  onclick="'.$asteroids_start.'"><img src="'.$asteroids_nohoverimage.'" onmouseover="this.src=' . "'";
      echo ''.$asteroids_hoverimage."'" . '" onmouseout="this.src=' . "'".$asteroids_nohoverimage."'" .'"></a></div>';
      
    break;
    
    case "image-3":
      echo '<div align="center"><a href="#"  onclick="'.$asteroids_start.'">
          <img src="'.$asteroids_rocketimage.'" width="90" height="70" border="0" alt="Asteroids Wordpress Widget" />
          </a></div>';
    break;
    
    case "image-4":
       echo '<div align="center"><a href="#"  onclick="'.$asteroids_start.'">
       <img src="'.$asteroids_arcadered.'" width="66" height="120" border="0" alt="Asteroids Wordpress Widget" />
       </a></div>';
    break;
    
    case "image-5":
       echo '<div align="center"><a href="#"  onclick="'.$asteroids_start.'">
       <img src="'.$asteroids_arcadeyellow.'" width="66" height="120" border="0" alt="Asteroids Wordpress Widget" />
       </a></div>';
    break;
    
    case "image-6":
       echo '<div align="center"><a href="#"  onclick="'.$asteroids_start.'">
       <img src="'.$asteroids_arcadeblack.'" width="66" height="120" border="0" alt="Asteroids Wordpress Widget" />
       </a></div>';
    break;
   }


/* Inser the button*/
  switch ($asteroids_buttonopt) {
  case "push-1":
    echo  '<div><p style="text-align: center;">
           <a href="#" onclick="'.$asteroids_start.'"><button>Click to Play Asteroids!!!</button>
           </a></p></div>';			      
  break;
  case "text-1":
    echo '<div><p style="text-align: center;">
           <a href="#" onclick="'.$asteroids_start.'" >Click to Play Asteroids!!!</a>
           </p></div>';		
  break;
  }
  
?>
