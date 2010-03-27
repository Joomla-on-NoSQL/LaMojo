<?php
/**
 * JSimpleCryptTest.php
 *
 * @version   $Id$
 * @package   Joomla.UnitTest
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license   GNU General Public License
 */
require_once 'PHPUnit/Framework.php';
require_once JPATH_BASE. DS . 'libraries' . DS . 'joomla' . DS . 'utilities' . DS . 'simplecrypt.php';

/**
 * Test class for JSimpleCrypt.
 * Generated by PHPUnit on 2009-10-26 at 22:30:43.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 */
class JSimpleCryptTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JSimpleCrypt
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		//$this->object = new JSimpleCrypt;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test cases for encryption/decryption
	 *
	 * @return void
	 */
	function casesEncryption()
	{
		return array(
			"HelloDef" => array(
				"Hello, World!",
				null,
				"2C515D 8574F446E57145C5443",
			),
			"HelloKey" => array(
				"Hello, World!",
				"This is a new key",
				"1C D 51F4F455377 E52 2 156",
			),
			"TypiDef" => array(
				"Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum",
				null,
				"304D41 D18 D B5718 E5152 75C4414 6555942594D584C 0 E46515A415E11 559 A445D1010194D154543425E5553 0574C594319505645 A F4B144342 C445250 75117445C5714455D",
			),
			"TypiKey" => array(
				"Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum",
				"This is a new key",
				" 011191A 0 71C4E4148 F 7124E1F451A38 91B1A54 8 745 C 0 7 B 4491F 4146F48 C 05449 65314534E 91247 E B D3D1B491A4E491A4912 01F101E 0 D 41A3D1C49164F1B 64D",
			),
			"WildDef" => array(
				chr(101).chr(23).chr(116).chr(3).chr(177).chr(99).chr(207).chr(249).chr(56).chr(107).chr(223).chr(49).chr(65).chr(119).chr(87).chr(189).chr(111).chr(133).chr(232).chr(48).chr(62).chr(201),
				null,
				" 123456789 0ABC0 0 DEF 123456789 ABCD0 0 EF0",
			),
			"WildKey" => array(
				chr(101).chr(23).chr(116).chr(3).chr(177).chr(99).chr(207).chr(249).chr(56).chr(107).chr(223).chr(49).chr(65).chr(119).chr(87).chr(189).chr(111).chr(133).chr(232).chr(48).chr(62).chr(201),
				"This is a new key",
				"317F1D7091 ABCD9594BB15436573CD816D180594DE9",
			),
		);
	}
	/**
	 * Testing testDecrypt().
	 *
	 * @param string $expected The expected result of decryption
	 * @param string $key	The key to use
	 * @param string $text	The decrypted text
	 *
	 * @return void
	 * @dataProvider casesEncryption
	 */
	public function testDecrypt( $expected, $key, $text )
	{
		$this->object = new JSimpleCrypt($key);

		$this->assertThat(
			$this->object->decrypt($text),
			$this->equalTo($expected)
		);
	}

	/**
	 * Testing testEncrypt().
	 *
	 * @param string $text	The text to be encrypted
	 * @param string $key	The key to use
	 * @param string $expected The expected result of encryption
	 *
	 * @return void
	 * @dataProvider casesEncryption
	 */
	public function testEncrypt( $text, $key, $expected )
	{
		$this->object = new JSimpleCrypt($key);

		$this->assertThat(
			$this->object->encrypt($text),
			$this->equalTo($expected)
		);
	}
}
?>
