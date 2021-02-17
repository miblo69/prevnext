<?php

/*
Version: 1.0
Plugin Name: prevnext
Plugin URI: // Here comes a link to the Piwigo extension gallery, after
           // publication of your plugin. For auto-updates of the plugin.
Author:  miblo69 - Mike Blomgren.
Description: A Plugin to retrieve the Previous/Next Albums, for simpler navigation among many albums.
Version: 0.1 Alfa


*/


// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Define the path to our plugin.
define('PREVNEXT_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

include_once( PHPWG_ROOT_PATH.'include/common.inc.php' );
//include(PHPWG_ROOT_PATH.'include/section_init.inc.php');
include(PHPWG_ROOT_PATH.'/include/ws_core.inc.php');
include(PHPWG_ROOT_PATH.'/include/ws_functions.inc.php');
// Added MB 14-05-05:
include (PHPWG_ROOT_PATH.'/include/ws_functions/pwg.categories.php');
//include_once( PHPWG_ROOT_PATH.'/include/common.inc.php' );
//include(PHPWG_ROOT_PATH.'include/section_init.inc.php');

// Hook on to an event..
add_event_handler('loc_end_index', 'prevnext_menu');
//add_event_handler('render_page_banner', 'prevnext_menu');

global $template;
global $myprev, $mynext;


function prevnext_menu() {
  global $id; 
  global $page;
  $txtpn = '';

  if (isset($page['is_homepage'])) {
  	echo  "Homepage"; 
  } else { 
  	if (isset($page['category']['id_uppercat'])) {
  		//echo "set";
  	} else { return;
  	echo " not set"; }
  	$myupperid = $page['category']['id_uppercat'];
  	$myuppercats = $page['category']['uppercats'];
  	
  	$params['format'] = 'php';
  	$params['tree_output'] = false;
  	$params['cat_id']= $myupperid;
  	$params['recursive'] = false;
  	$params['public'] = false;
  	$params['fullname'] = false;

  	$txt = ws_categories_getList($params,$service);
  	
  	if (isset($myupperid)) {
  		$currentcats = $txt['categories']->_content;
  		$p = 0;
  		$catid = -1;

  		foreach ($currentcats as $name) {
			$catid += 1;
  			if ((int)$name['id'] == (int)$myupperid)  { 
  				$p = 1; 
  				 
  			} else {
  				
  			}
  			
  				
  			if ($p == 1) {
  				$txtcurr = '';
  				$txtprev = '';
  				$txtnext = '';
  				
  				if ($page['category']['id'] == $name['id']) { // Found current Category

  					if($catid > 0 and $currentcats[$catid-1]['id'] <> $myupperid) {  // If there is a Previous cat, add it
  						$txtprev = 'Previous: <a href=' . $currentcats[$catid-1]['url'] . '>' . 
  						$currentcats[$catid-1]['name'] . "</a>" . "<br>";
  				
  					
  					} else { $txtnext = 'No Prev Cat <br/>'; }
  					// Add the current Category
  					$txtcurr .= '<b>Current: <a href=' . $currentcats[$catid-0]['url'] . '>' . 
  					$currentcats[$catid-0]['name'] . "</a></b><br/>";
  					
  					if (array_key_exists($catid+1, $currentcats)) {
  					  	$txtnext = 'Next: <a href=' . $currentcats[$catid+1]['url'] . '>' .
  					  	 $currentcats[$catid+1]['name'] . "</a>" . "<br/>";
  					} else { 

  					}
  					$p = 0;
  				}

  			}
  			
		}
  	echo $txtprev, $txtcurr, $txtnext;
  	$mynext = $txtnext;
  	$myprev = $txtprev;
  		
  	} else {
  		//// echo "One down from Top <br>";
  		
  		$currentcats = $txt['categories']->_content;
  		$p = 0;
  		//var_dump($currentcats);	
  		foreach ($currentcats as $name) {
  			$txtcurr = "";
  				
  			$txtcurr .= '<a href=' . $name['url'] . '>' . $name['name'] . "</a> <br>";
  			echo $txtcurr;
  		}
  	}
  	
  	 
  }
  return;
  
}


?>
