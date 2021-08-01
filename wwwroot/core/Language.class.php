<?php

class Language
{
	private $strings = array();
	private $path;

	function setPath($path)
	{
		$this->path = $path;
	}

	function &getStrings()
	{
		return $this->strings;
	}

	function getString($name, $sub)
	{
		if (isset($this->strings[$name])) {
			$value = &$this->strings[$name];
			return $value;
		} else {
			return $sub;
		}
	}

	function load($locale)
	{
		$lang_id = $locale . "_utf8";
		$path = $this->path . "/" . $lang_id . ".lang";
		if (file_exists($path)) {
			require_once($path);
			$this->strings = &$$lang_id;
		}
	}
}