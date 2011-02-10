<?php
/**
 * jUpgrade
 *
 * @version		$Id$
 * @package		MatWare
 * @subpackage	com_jupgrade
 * @copyright	Copyright 2006 - 2011 Matias Aguire. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 * @author		Matias Aguirre <maguirre@matware.com.ar>
 * @link		http://www.matware.com.ar
 */

define('_JEXEC',		1);
define('JPATH_BASE',	dirname(__FILE__));
define('DS',			DIRECTORY_SEPARATOR);

require_once JPATH_BASE.'/defines.php';
require_once JPATH_BASE.'/jupgrade.class.php';

$sizefile = JPATH_ROOT.'/tmp/size.tmp';
$zipfile = JPATH_ROOT.'/tmp/joomla16.zip';

if (file_exists($zipfile)) {
   $size = filesize($zipfile);
}
else {
	echo 212;
	exit;
}

if (file_exists($sizefile)) {
	$handle = fopen($sizefile, 'r');
	$total = trim(fread($handle, 18));
}
else {
	echo 121;
	exit;
}

$percent = $size / $total * 100;
$percent = round($percent);

echo "{$percent},{$size},{$total}";