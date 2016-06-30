<?php
CModule::IncludeModule("smsauth");
global $DBType;

$arClasses=array(
    'cSmsAuth'=>'classes/general/cSmsAuth.php'
);

CModule::AddAutoloadClasses("smsauth",$arClasses);
?>