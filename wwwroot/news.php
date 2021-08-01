<?php

include_once("./Core/Template.class.php");
include_once("./Core/TemplateBlock.class.php");
include_once("./Core/Language.class.php");

$lang	= new Language();
$lang->setPath("./languages");
$lang->load("nl");

$tpl 	= new Template();
$tpl->addPath("./templates");
$tpl->setLanguage($lang);

$tpl->addBlockFromFile("index.tpl", $tpl->_root);

$tpl->set("stylesheet_href", "style/style.css");
$tpl->parse("stylesheetBlock");

$tpl->set("document_h1", "PHP Template engine");
$tpl->set("username", "Fex");

$tpl->parse("userBlock");
$tpl->touch("menuBlock");

$tpl->addBlockFromFile("news.tpl", "newsBlock");

for ($i = 0; $i < 10; $i++) {
	$tpl->set("news_title", $i);
	$tpl->parse("lineTraceBlock");
}

$tpl->touch("listBlock");

$tpl->setFromBlock("bodyBlock", "newsBlock");


$tpl->parse();
$tpl->spit();
