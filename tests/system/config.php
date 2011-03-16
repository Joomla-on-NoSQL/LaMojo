<?php

$site = array('baseurl' => 'http://192.168.56.1/trunk/');
$selenium = array('browser' => '*chrome', 'host' => '192.168.56.102', 'port' => 4444);

class SiteSettings
{
	public static $url = 'http://192.168.56.1/trunk/';
	public static $username = 'admin';
	public static $password = 'admin';
}

class SeleniumSettings
{
	public static $browser = '*googlechrome';
	public static $host = '192.168.56.102';
	public static $port = 4444;
}