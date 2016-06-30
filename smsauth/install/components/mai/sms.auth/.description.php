<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Смс аутентификация",
	"DESCRIPTION" => "Компонент двухфакторной аутентификации",
	"ICON" => "/images/user_authform.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "smsauth",
		"NAME" => "smsauth",
	),
);
?>