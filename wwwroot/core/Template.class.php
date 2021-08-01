<?php

class Template
{
	var $_root		= "_rootblock";
	var $content 	= "";

	var $paths		= array();
	var $parsed		= array();
	var $blocks 	= array();
	var $vars		= array();
	var $language	= null;

	function __construct($filename = null)
	{
		if (!is_null($filename)) {
			array_unshift($this->paths, dirname($filename));
			$basename	 = basename($filename);

			if (is_file($filename))
				$this->addBlockFromFile($basename, $this->_root);
		}
	}

	function setLanguage(Language &$lang)
	{
		$this->language = &$lang;
	}

	function addPath($path)
	{
		array_unshift($this->paths, $path);
	}

	function addBlockFromFile($filename, $blockname)
	{
		foreach ($this->paths as $path) {
			if (file_exists($path . "/" . $filename)) {
				$file	= @file($path . "/" . $filename);
				break;
			}
		}

		if (!isset($file))
			throw new Exception("File `$filename` could not be found to include");

		return $this->addBlock($blockname, $file);
	}

	function addBlock($blockname, $block)
	{
		if (!isset($this->blocks[$blockname])) {
			return $this->blocks[$blockname] = new TemplateBlock($this, $blockname, $block);
		} else throw new Exception("Block `$blockname` already exists");
	}

	function setFromExternalBlock($var, $filename)
	{
		$this->vars[$var] = $this->addBlockFromFile($filename, $var);
	}

	function set($var, $value)
	{
		$this->vars[$var] = $value;
	}

	function setFromBlock($var, $block)
	{
		$this->blocks[$block]->parse(true);
		$this->vars[$var] = $this->get($block);
	}

	function touch($block)
	{
		if (isset($this->blocks[$block]))
			$this->parsed[] = $this->blocks[$block]->parse(true);
	}

	function parseBlock($block)
	{
		if (isset($this->blocks[$block])) {
			$this->blocks[$block]->parse(false);
		} else throw new Exception("Block `" . $block . "` couldn`t be found!");
	}

	function parseRepeatedly($block, $count = 0)
	{
		if (isset($this->blocks[$block])) {
			for ($i = 0; $i < $count; $i++) {
				$this->blocks[$block]->parse();
			}
		}
	}

	function replaceBlock($block, $replace)
	{
		if (isset($this->blocks[$block]) && isset($this->blocks[$replace])) {
			$this->blocks[$block] = $this->blocks[$replace];
			$this->blocks[$block]->parse();
		}
	}

	function parseOnce($block)
	{
		$this->parseBlock($block);
		$this->clearBlock($block);
	}

	function clearBlock($block)
	{
		if (isset($this->blocks[$block])) {
			$this->blocks[$block]->clear();
		}
	}

	function reset()
	{
		$this->vars = array();
	}

	function parse($block = null)
	{
		if (!$block) {
			$this->blocks[$this->_root]->parse(false);
			return;
		}

		if (isset($this->blocks[$block])) {
			$this->parsed[] = $this->blocks[$block]->parse();
		} else throw new Exception("Block `$block` not found!");
	}

	function get($block = null)
	{
		if (!$block) $block = $this->_root;
		return implode($this->blocks[$block]->parsed);
	}

	function spit($block = null)
	{
		if (!$block) $block = $this->_root;
		echo implode($this->blocks[$block]->parsed);
	}
}
