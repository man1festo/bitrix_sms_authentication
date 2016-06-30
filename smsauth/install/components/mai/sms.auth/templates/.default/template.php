<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $USER;
?>
<div id="sms4b-auth-sms">
	<div id="preloader"></div>
	<h3><?=GetMessage("SMS4BAUTH")?></h3>
	
	<form name="smsauth" method="post" action="index.php">
		
		<div id="psms">	
			<span><?=GetMessage("ENTER_SMS_KEY")?></span><br/>
			<input type="text" name="keyval" id="sms" size="20"><br/>
			<input type="hidden" name="attempt" value="<?=$arResult["attempt"]?>">
		 	<input type="submit" value="<?=GetMessage("ENTER")?>" name="auth" id="auth"  class="btn btn-green"><br/>
	  	</div>
	</form>
	
	<div id="errorsms">
		<?if (!empty($arResult["error"])):?>
			<?echo $arResult["error"];?>
		<?endif;?>
	</div>
</div>