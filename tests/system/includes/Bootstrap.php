<?php

function JoomlaSystemTestPageLoader($className)
{
	if (substr($className, -4) == 'Page')
	{
		include 'pages/'.$className.'.php';
	}
}

spl_autoload_register('JoomlaSystemTestPageLoader');