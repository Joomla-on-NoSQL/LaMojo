<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters, Inc.
 * @version $Id$
 *
 */

class JStringTest_DataSet {
	/**
	 * Tests for JString::strpos.
	 *
	 * Each element contains $haystack, $needle, $offset, $expect,
	 *
	 * @var array
	 */
	static public $strposTests = array(
		array('missing',	'sing', 0,  3),
		array('missing',	'sting', 0,  false),
		array('missing',	'ing', 0,  4),
		array(' объектов на карте с',	'на карте', 0,  10),
		array('на карте с',	'на карте', 0,  0),
		array('на карте с',	'на каррте', 0,  false),
		array('на карте с',	'на карте', 2,  false),
		array('missing',	'sing', false,  3)		
	);

	static public $strrposTests = array(
		array('missing',	'sing', 0,  3),
		array('missing',	'sting', 0,  false),
		array('missing',	'ing', 0,  4),
		array(' объектов на карте с',	'на карте', 0,  10),
		array('на карте с',	'на карте', 0,  0),
		array('на карте с',	'на каррте', 0,  false),
		array('на карте с',	'карт', 2,  3)
	);

	static public $substrTests = array(
		array('Mississauga', 4, false, 'issauga'),
		array(' объектов на карте с', 10, false,	'на карте с'),
		array(' объектов на карте с', 10, 5,	'на ка'),
		array(' объектов на карте с', -4, false,	'те с'),
		array(' объектов на карте с', 99, false,	false)
	);

	static public $strtolowerTests = array(
		array('Joomla! Rocks', 'joomla! rocks')
	);

	static public $strtoupperTests = array(
		array('Joomla! Rocks', 'JOOMLA! ROCKS')
	);

	static public $strlenTests = array(
		array('Joomla! Rocks', 13)
	);
	
	static public $str_ireplaceTests = array(
		array('Pig', 'cow', 'the pig jumped', false, 'the cow jumped'),
		array('Pig', 'cow', 'the pig jumped', true, 'the cow jumped'),
		array('Pig', 'cow', 'the pig jumped over the cow', true,  'the cow jumped over the cow'),
		array(array('PIG', 'JUMPED'), array('cow', 'hopped'), true,  'the pig jumped over the pig', 'the cow hopped over the cow'),
		array('шил', 'биш', 'Би шил идэй чадна', true,  'Би биш идэй чадна')
	);
	
	static public $str_splitTests = array(
		array('string', 1, array('s','t','r','i','n','g')),
		array('string', 2, array('st','ri','ng')),
		array('волн', 3, array('вол','н')),
		array('волн', 1, array('в','о','л','н'))
	);
	
	static public $strcasecmpTests = array (
		array('THIS IS STRING1', 'this is string1', 0),
		array('this is string1', 'this is string2', -1),
		array('this is string2', 'this is string1', 1),
		array('бгдпт', 'бгдпт', 0)
	);
	
	static public $strcspnTests = array (
		array('subject <a> string <a>', '<>', false, false, 8),
		array('Би шил {123} идэй {456} чадна', '}{', null, false, 7),
		array('Би шил {123} идэй {456} чадна', '}{', 13, 10, 5)
	);
	
	static public $stristrTests = array (
		array('haystack', 'needle', false),
		array('before match, after match', 'match', 'match, after match'),
		array('Би шил идэй чадна', 'шил', 'шил идэй чадна')
	);
	
	static public $strrevTests = array (
		array('abc def', 'fed cba'),
		array('Би шил', 'лиш иБ')
	);
	
	static public $strspnTests = array (
		array('321 Main Street', '0123456789', false, false, 3),
		array('A321 Main Street', '0123456789', 0, false, 0),
		array('A321 Main Street', '0123456789', 1, 10, 3),
		array('A321 Main Street', '0123456789', 1, false, 0),
		array('Би шил идэй чадна', 'Би', null, null, 2),
		array('чадна Би шил идэй чадна', 'Би', null, null, 0)
	);

	static public $substr_replaceTests = array (
		array('321 Main Street', 'Broadway Avenue', 4, false, '321 Broadway Avenue'),
		array('321 Main Street', 'Broadway', 4, 4, '321 Broadway Street'),
		array('чадна Би шил идэй чадна', '我能吞', 6, false, 'чадна 我能吞'),
		array('чадна Би шил идэй чадна', '我能吞', 6, 2, 'чадна 我能吞 шил идэй чадна')
	);

	static public $ltrimTests = array (
		array('   abc def', null, 'abc def'),
		array(' Би шил', null, 'Би шил'),
		array("\t\n\r\x0BБи шил", null, 'Би шил'),
		array("\x0B\t\n\rБи шил", "\t\n\x0B", "\rБи шил"),
		array("\x09Би шил\x0A", "\x09\x0A", "Би шил\x0A"),
		array('1234abc', '0123456789', 'abc')
	);
	
	static public $rtrimTests = array (
		array('abc def   ', null, 'abc def'),
		array('Би шил ', null, 'Би шил'),
		array("Би шил\t\n\r\x0B", null, 'Би шил'),
		array("Би шил\r\x0B\t\n", "\t\n\x0B", "Би шил\r"),
		array("\x09Би шил\x0A", "\x09\x0A", "\x09Би шил"),
		array('1234abc', 'abc', '01234')
	);
	
	static public $trimTests = array (
		array('  abc def   ', null, 'abc def'),
		array('   Би шил ', null, 'Би шил'),
		array("\t\n\r\x0BБи шил\t\n\r\x0B", null, 'Би шил'),
		array("\x0B\t\n\rБи шил\r\x0B\t\n", "\t\n\x0B", "\rБи шил\r"),
		array("\x09Би шил\x0A", "\x09\x0A", "Би шил"),
		array('1234abc56789', '0123456789', 'abc')
	);
	
	static public $ucfirstTests = array (
		array('george', 'George'), 
		array('мога', 'Мога'), 
		array('ψυχοφθόρα', 'Ψυχοφθόρα')
	);
	
	static public $ucwordsTests = array (
		array('george washington', 'George Washington'), 
		array("george\r\nwashington", "George\r\nWashington"),
		array('мога', 'Мога'), 
		array('αβγ δεζ', 'Αβγ Δεζ'),
		array('åbc öde', 'Åbc Öde')
	);
	
	static public $transcodeTests = array (
		array('Åbc Öde €100', 'UTF-8', 'ISO-8859-1', "\xc5bc \xd6de EUR100") 
	);
	
	static public $validTests = array (
		array('george Мога Ž Ψυχοφθόρα ฉันกินกระจกได้ 我能吞下玻璃而不伤身体 ', true),
		array("\xFF ABC", false),
		array("0xfffd ABC", true),
		array('', true)
	);
}