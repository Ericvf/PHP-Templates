<?php

class TemplateBlock
{
	var $name;

	var $language   = null;
	var $block		= array();
	var $parsed 	= array();
	var $children 	= array();
	var $vars		= array();
	var $parseCount = 0;

	function __construct(&$engine, $name, $block)
	{
		$this->name 	= $name;
		$this->block 	= $block;
		$this->vars	= &$engine->vars;
		$this->language = &$engine->language;

		$this->findChildren($engine);
	}

	function findChildren(&$engine)
	{
		$inChild 	= false;
		$childName 	= null;
		$childStart = 0;
		$isFileBlock = false;

		for ($nr = 0; $nr < count($this->block); $nr++) {
			$line = &$this->block[$nr];

			if (preg_match_all('#\[(BLOCK|CLONE|END|FILEBLOCK) (.*?)( AS ([^}]+))?\]#', $line, $match)) {
				$search 	= $match[0];
				$type		= $match[1];
				$value		= $match[2];

				for ($i = 0; $i < count($search); $i++) {
					switch ($type[$i]) {
						case "FILEBLOCK":
							$isFileBlock = true;

						case "BLOCK":
							if (!is_null($childName)) break;
							$line = trim(str_replace($search[$i], "", $line));

							$childStart	= $nr;
							$childName  = $value[$i];

							break;

						case "CLONE":
							if (!is_null($childName)) break;
							$childNr	= sizeOf($this->children);
							$block		= &$engine->blocks[$value[$i]];

							$this->children[] = &$engine->addBlock($match[4][$i], $block->block);
							$this->children[$childNr]->children = $block->children;
							$line = str_replace($search[$i], "{CHILD:$childNr}", $line);

							break;

						case "END":
							if ($childName != $value[$i]) break;

							$line = trim(str_replace($search[$i], "", $line));

							$childNr	= sizeOf($this->children);
							$childBlock	= array_splice($this->block, $childStart, $nr - $childStart + 1, "{CHILD:$childNr}");

							if ($isFileBlock){
								$this->children[] = $engine->addBlockFromFile ($childBlock[0], $childName);
								$isFileBlock = false;
							}
							else{
								$this->children[] = $engine->addBlock($childName, $childBlock);
							}
							$childName 	= null;
							$nr			= $childStart;

							break;

						case "FBLOCK":
								if (!is_null($childName)) break;
								$line = trim(str_replace($search[$i], "", $line));

								$childStart	= $nr;
								$childName  = $value[$i];
								break;
					}
				}
			}
		}
	}

	function parse($touch = false)
	{
		$varCount = 0;

		$line = implode($this->block);

		if (preg_match_all("#\{((CYCLE|CHILD|ISSET|LANG|BLOCK)(:))?([^}]+)\}#", $line, $match)) {
			$search	= $match[0];
			$type	= $match[2];
			$value	= $match[4];

			for ($i = 0; $i < count($search); $i++) {
				switch ($type[$i]) {
					case "CYCLE":
						$vars		= explode(",", $value[$i]);
						$value[$i]	= $vars[$this->parseCount % count($vars)];
						$line 		= str_replace($search[$i], $value[$i], $line);
						break;

					case "CHILD":
						$line 		= str_replace($search[$i], implode($this->children[$value[$i]]->parsed), $line);
						break;

					case "ISSET":
						$vars		= explode(",", $value[$i]);

						if (!isset($this->vars[$vars[0]]) || (false === $this->vars[$vars[0]]))
							$vars[1] = "";

						$line 		= str_replace($search[$i], $vars[1], $line);
						break;

					case "LANG":
						$vars		= explode(",", $value[$i]);
						$val 		= isset($this->language)
							? $this->language->getString($vars[0], $vars[1])
							: $vars[1];

						$line 		= str_replace($search[$i], $val, $line);
						break;

					default:
						if (isset($this->vars[$value[$i]])) {
							$mvalue		= $this->vars[$value[$i]];
							$varCount++;
						} else $mvalue = "";

						if (is_array($mvalue)) $mvalue = $mvalue[$this->parseCount % count($mvalue)];
						$line 		= str_replace($search[$i], $mvalue, $line);

						break;
				}
			}

			if ($varCount)
				$this->parsed[] = $line;
		}

		if (!$varCount && $touch)
			$this->parsed[] = $line;

		$this->parseCount++;
		return $this->parsed;
	}

	function clear()
	{
		$this->parsed = array();
		$this->parseCount = 0;
	}
}
