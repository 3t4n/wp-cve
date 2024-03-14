<?php

	$nomTheme='simple-popup-manager';

	$pot='#'.$nomTheme."\n\n";
	$total=array();
	if ($handle = opendir('..')) 
	{
    	while (false !== ($file = readdir($handle))) 
    	{
        	if (substr($file,-3)=='php')
            {
            	//echo $file;
            	$str=file_get_contents('../'.$file);
            	
            	$matches=array();
            	preg_match_all('/__\(\'(.*)\',\''.$nomTheme.'\'\)/Uis',$str,$matches);
          
            	//print_r($matches);
            	//print_r($matches2);
            	
            	if(isset($matches[1]))
            	{
            		foreach($matches[1] as $m)
            		{
            			$total[$m][]=$file;
            		}
            	}
            	
            	if(isset($matches2[1]))
            	{
            		foreach($matches2[1] as $m)
            		{
            			$total[$m][]=$file;
            		}
            	}
				
				if(isset($matches3[1]))
            	{
            		foreach($matches3[1] as $m)
            		{
            			$total[$m][]=$file;
            		}
            	}
            }
    	}
    	closedir($handle);
	}
	
	foreach($total as $k=>$t)
	{
		$pot.='#:'.implode(' ',$t)."\n";
		$pot.='msgid "'.str_replace(array('"','\\\''),array('\"','\'',),$k).'"'."\n";
		$pot.='msgstr ""';
		$pot.="\n\n";
	}

	file_put_contents($nomTheme.'.pot',$pot);
?>