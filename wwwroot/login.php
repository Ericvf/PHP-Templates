<?php

include_once("./Core/Template.class.php");
include_once("./Core/TemplateBlock.class.php");
include_once("./Core/Language.class.php");

$lang    = new Language();
$lang->setPath("./languages");
$lang->load("nl");

$tpl     = new Template();
$tpl->addPath("./templates");
$tpl->setLanguage($lang);

$tpl->addBlockFromFile("index.tpl", $tpl->_root);

$tpl->set("stylesheet_href", "style/style.css");
$tpl->parse("stylesheetBlock");

$tpl->set("document_h1", "PHP Template engine");
$tpl->set("username", "Fex");

$tpl->parse("userBlock");
$tpl->touch("menuBlock");

$tpl->addBlockFromFile("login.tpl", "loginBlock");

if (!empty($_POST))
{
    if ($_POST["loginName"] != $_POST["loginPassword"])
    {
        $tpl->touch("errorBlock");
        $tpl->touch("formBlock");
    }
    else{
        $tpl->touch("thanksBlock");
    }
}
else{
    $tpl->touch("formBlock");
}

$tpl->setFromBlock("bodyBlock", "loginBlock");

$tpl->parse();
$tpl->spit();

