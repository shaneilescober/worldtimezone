<?php
class common
{
	function modelContents()
	{
		return getInstance('modelWorldtimezone');
	}
	
	function getFullUrl() {
		return $_SERVER['REQUEST_URI'];
	}
}