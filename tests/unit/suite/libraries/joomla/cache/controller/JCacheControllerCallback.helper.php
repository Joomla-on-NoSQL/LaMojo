<?php
/**
 * JDate constructor tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: JCacheControllerCallback.helper.php 16235 2010-04-20 04:13:25Z pasamio $
 * @author Anthony Ferrara
 */

class testCallbackController {

	public function instanceCallback($arg1, $arg2) {
		echo $arg1;
		return $arg2;
	}

	static function staticCallback($arg1, $arg2) {
		echo $arg1;
		return $arg2;
	}

}

function testCallbackControllerFunc($arg1, $arg2) {
	echo $arg1;
	return $arg2;
}
