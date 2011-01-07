<?php
/**
 * jUpgrade
 *
 * @author      Matias Aguirre
 * @email       maguirre@matware.com.ar
 * @url         http://www.matware.com.ar
 * @license     GNU/GPL
 */

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', dirname(__FILE__) );
 
$parts = explode( DS, JPATH_BASE );
$newparts = array();
for($i=0;$i<count($parts)-4;$i++){
	//echo $parts[$i] . "\n";
	$newparts[] = $parts[$i];

}

define( 'JPATH_ROOT',			implode( DS, $newparts ) );
define( 'JPATH_SITE',			JPATH_ROOT );
define( 'JPATH_CONFIGURATION', 	JPATH_ROOT );
define( 'JPATH_ADMINISTRATOR', 	JPATH_ROOT.DS.'administrator' );
define( 'JPATH_XMLRPC', 		JPATH_ROOT.DS.'xmlrpc' );
define( 'JPATH_LIBRARIES',	 	JPATH_ROOT.DS.'libraries' );
define( 'JPATH_PLUGINS',		JPATH_ROOT.DS.'plugins'   );
define( 'JPATH_INSTALLATION',	JPATH_ROOT.DS.'installation' );
define( 'JPATH_THEMES'	   ,	JPATH_BASE.DS.'templates' );
define( 'JPATH_CACHE',			JPATH_BASE.DS.'cache');

require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'methods.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'import.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'error'.DS.'error.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'base'.DS.'object.php' );
require_once ('..'.DS.'libraries'.DS.'pclzip.lib.php');

require(JPATH_ROOT.DS."configuration.php");

$zipfile = JPATH_ROOT.DS.'tmp'.DS.'joomla16.zip';
$dir = JPATH_ROOT.DS.'jupgrade';

if (file_exists($zipfile)) {
	$archive = new PclZip($zipfile);

	if ($archive->extract(PCLZIP_OPT_PATH, $dir) == 0) {
		die("Error : ".$archive->errorInfo(true));
	}
  echo 1;
} else {
  echo 0;
}

?>
