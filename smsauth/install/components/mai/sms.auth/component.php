<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	if ($_SESSION["UserID"])
	{
		if($_POST['key_val'])
		{
			global $DB;
			global $USER;
			$attempt = $_POST["attempt"];
			$attempt ++;
			$arResult["attempt"] = $attempt;
			if($attempt > 5)
			{
				$DB->Query("DELETE FROM `auth_sms_key` WHERE `user_id`='" . $_SESSION["UserID"] . "'");
				$arResult["error"] = "��������� ����� �������";
			}
			else
			{
				$results = $DB->Query("SELECT login, password, smskey, moment FROM `auth_sms_key` WHERE `user_id`='" . $_SESSION["UserID"] . "'");   //���������  ������
				$name_array=array();   //������� ������ ������
				while ($row = $results->Fetch())
				{
					array_push($name_array, $row);  //������� ������ �� ����� ���������� ������� ������ ������
				}
				if(($name_array["smskey"] == $_POST['key_val']) && (($name_array["moment"] + 1200) > time()))
				{
					$DB->Query("DELETE FROM `auth_sms_key` WHERE `user_id`='" . $_SESSION["UserID"] . "'");
					if (!is_object($USER)) $USER = new CUser;
					$arAuthResult = $USER->Login($name_array["login"], $name_array["password"], "N", "N");
					$APPLICATION->arAuthResult = $arAuthResult;
				}
				else
				{
					$arResult["error"] = "�������� ��� ���";
				}
			}	
		}
	}
	else
	{
		$arResult["error"] = "������� ���������� ��������������";
	}
}

$this->IncludeComponentTemplate();?>