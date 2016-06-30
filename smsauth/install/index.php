<?
Class smsauth extends CModule
{
    var $MODULE_ID = "smsauth";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function smsauth()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "Модуль двухфакторной аутентификации";
        $this->MODULE_DESCRIPTION = "Подтверждение аутентификации с помощью смс кода";
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        // Install events
        RegisterModuleDependences("main","OnAfterUserAuthorize",$this->MODULE_ID,"cSmsAuth","onAfterUserAuthorizeHandler");
        $APPLICATION->IncludeAdminFile("Установка модуля smsauth", $DOCUMENT_ROOT."/bitrix/modules/smsauth/install/step.php");
		$this->InstallFiles();
		$this->InstallDB();
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        UnRegisterModuleDependences("main","OnAfterUserAuthorize",$this->MODULE_ID,"cSmsAuth","onAfterUserAuthorizeHandler");
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля smsauth", $DOCUMENT_ROOT."/bitrix/modules/smsauth/install/unstep.php");
		$this->UnInstallFiles();
		$this->UnInstallDB();
    }
	function InstallFiles($arParams = array())
	{
		$siteList = array();
		$rsSites = CSite::GetList($by="sort", $order="asc", Array());
		while($arRes = $rsSites->GetNext(false,false))
		{
			$siteList[] = Array("ID" => $arRes["ID"], "NAME" => $arRes["NAME"]);
		}
		foreach ($siteList as $site)
		{
			$rsSites = CSite::GetByID($site["ID"]);
			$arSite = $rsSites->Fetch();
			$dir = "";
			if ($arSite["length(L.DIR)"] > 1)
			{
				$dir = $arSite["DIR"];
			}
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/public/smsauth", $arSite["ABS_DOC_ROOT"].$dir."/smsauth", false, true);
		}
		global $APPLICATION;
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", false, true);
	}
	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/components/".$this->MODULE_ID);
	}
	function InstallDB()
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;

		$this->errors = $DB->RunSqlBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/db/install.sql");

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("<br>", $this->errors));
			return false;
		}
		else
		{
			RegisterModule($this->MODULE_ID);
			CModule::IncludeModule($this->MODULE_ID);
			return true;
		}
	}
	function UnInstallDB()
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;
		
		//kick current user options
		COption::RemoveOption($this->MODULE_ID, "");
		//drop tables
		$this->errors = $DB->RunSqlBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/db/uninstall.sql");
	

		UnRegisterModule($this->MODULE_ID);
		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("<br>", $this->errors));
			return false;
		}

		return true;
	}
}
?>