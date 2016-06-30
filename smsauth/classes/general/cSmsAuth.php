<?php
class cSmsAuth {
    static $MODULE_ID="smsauth";

    /**
     * ’эндлер, отслеживающий авторизации пользователей
     * @param $arFields
     * @return bool
     */
    static function onAfterUserAuthorizeHandler($arUser)
	{
		if(!$_SESSION["UserID"])
		{
			global $USER;
			AddMessage2Log("вызывается AfterUserAuthorize");
			$_SESSION["UserID"] = $arUser["user_fields"]["ID"];
			$_SESSION["backurl"] = $_REQUEST["backurl"];
			if (empty($_SESSION["backurl"]))
				{
					$_SESSION["backurl"] = $_SESSION["SESS_LAST_URI"];
				}
			$login = $arUser["user_fields"]["LOGIN"];
			$pass =  $arUser["user_fields"]["PASSWORD"];
			$phone = self::GetUserPhone($arUser["user_fields"]["ID"]);
			$userid =  $arUser["user_fields"]["ID"];
			$time = time();
			$key = rand(1000, 9999);
			self::insertToBD($login, $pass, $key, $userid);
			$USER->Logout();
			self::SendSMS($phone, $key);
			LocalRedirect("/smsauth/index.php");
			return;
		}
    }
	static function insertToBD($login, $pass, $key, $userid, $time)
	{
		global $DB;
		$strSql = "INSERT INTO auth_sms_key (login, password, smskey, userid, moment) VALUES ('" . $login . "', '" . $pass . "', '" . $key . "' , '" . $uerid . "', '" . $time ."')";
		$res = $DB->Query($strSql);
		return $res;
	}
	public function GetUserPhone ($userID)
	{
		if (!intval($userID))
			$result = false;
		$rsUser = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$userID),array("SELECT"=>array("PERSONAL_PHONE")));
		if ($arUser = $rsUser->Fetch())
		{
			$result = $arUser["PERSONAL_PHONE"];
		}
		$result = self::ValidatePhone($result);
		return $result;
	}
	 function ValidatePhone ($phone)
    {
        $saveOnlyNumberInPhone =  preg_replace('/[^0-9]/', '', $phone);

        if ((strlen($saveOnlyNumberInPhone)==11) && (substr($saveOnlyNumberInPhone, 0, 1)=="7"))
        {
            $phone = $saveOnlyNumberInPhone;
        }

        if ((strlen($saveOnlyNumberInPhone)==11) && (substr($saveOnlyNumberInPhone, 0, 1)=="8"))
        {
            $phone = "7".substr($saveOnlyNumberInPhone, 1);
        }

        if (strlen($saveOnlyNumberInPhone)==10)
        {
            $phone = "7".$saveOnlyNumberInPhone;
        }
        return $phone;
    }
	function SendSMS($phone, $key)
	{
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(

			"api_id"		=>	"F974C2E6-C5F1-D1B7-8191-1970A4134B3F",
			"to"			=>	"79670546279",
			"text"		=>	iconv("windows-1251","utf-8",$key)

		));
		$body = curl_exec($ch);
		curl_close($ch);
	}
}