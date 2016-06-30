<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!empty($arResult["redirect"]))
{
	LocalRedirect("/");
}
global $USER;
?>
<p id="nojs"><font class="errortext"><?=GetMessageJS("nojs")?></font></p>
<div id="sms4b-auth-sms">
	<div id="preloader"></div>
	<h3><?=GetMessage("SMS4BAUTH")?></h3>
	
	<form name="smsauth" method="post" action="<?=$arResult["path"]?>">
		<? /*<?=GetMessage("LOGIN")?><b><?=$arResult["USER"]["LOGIN"]?></b><br/>*/?>
		<?if (!$arResult["USER"]["UF_PHONE_VALID"]):?>
			<div id="pgen">
				<span><?=GetMessage("INVALID_PHONE_CONFIRM_PHONE")?></span><br/>
				<input type="text" name="phone" id="phone" size="20" value="<?=$arResult["USER"]["PHONE"]?>" ><br>
				<span class="phone-exemple"><?=GetMessage("EXAMPLE")?></span><br/>
				<input type="button" class="btn btn-green" value="<?=GetMessage("GIVE_SMS_KEY")?>" name="gen" id="gen">
			</div>
		<?endif;?>
		
		<div id="psms" <?if (!$arResult["USER"]["UF_PHONE_VALID"]):?>style="display:none;"<?endif;?>>
			<?if (!$arResult["USER"]["UF_PHONE_VALID"]):?>
				<span><?=GetMessage("PHONE")?></span><br/>
				<input disabled="disabled" type="text" name="phone" id="view_phone" size="20" value="<?=$arResult["USER"]["VIEW_PHONE"]?>" ><br>
			<?else:?>
				<span><?=GetMessage("SEND_SMS")?></span><br/>
				<?=$arResult["USER"]["VIEW_PHONE"]?><br>
			<?endif;?>
		
			<?if (!$arResult["USER"]["UF_PHONE_VALID"]):?>
			 	<div class="redact-number">
			 		<a href="#" id="editphon"><?=GetMessage("CHANGE")?></a>
			 	</div>
			<?endif;?>
			 	
			<span><?=GetMessage("ENTER_SMS_KEY")?></span><br/>
			<input type="text" name="sms" id="sms" size="20"><br/>
		 	<input type="button" value="<?=GetMessage("ENTER")?>" name="auth" id="auth"  class="btn btn-green"><br/>
		 	<span><?=GetMessage("NO_SMS")?><a href="#" name="agen" id="agen" title="<?=GetMessage("GIVE_SMS_KEY2")?>"><?=GetMessage("GIVE_SMS_KEY2")?></a></span>
	  		<?if (in_array(1, $USER->GetUserGroup($_SESSION["UserID"]))):?>
	  			<div style="margin-top: -5px;"><span><a href="#" name="aone" id="aone" title="<?=GetMessage("IS_ADMIN")?>"><?=GetMessage("IS_ADMIN")?></a></span></div>
	  		<?endif;?>
	  	</div>
	  	
	  	<?if (in_array(1, $USER->GetUserGroup($_SESSION["UserID"]))):?>
		  	<div id="pone" style="display:none;">
		  		<span><?=GetMessage("ENTER_ONE_KEY")?></span><br />
		  		<input type="text" name="one" id="one" size="20"><br />
		  		<input type="button" class="btn btn-green" value="<?=GetMessage("ENTER")?>" name="oneenter" id="oneenter">
		  		<span><a href="#" name="asms" id="asms" title="<?=GetMessage("ENER_FOR_SMS")?>"><?=GetMessage("ENER_FOR_SMS")?></a></span>
		  	</div>
	  	<?endif;?>
	  	
	  	<div class="technology"><?=GetMessage("SMS4B")?></div>
	</form>
	
	<div id="errorsms">
		<?if (!empty($arResult["error"])):?>
			<script type="text/javascript">
				BX.ready(function(){
					ShowMessage("error_id","block");
					//BX.hide(BX("gen"));
					BX("phone").disabled=true;
					BX("gen").disabled=true;
				});
			</script>
		<?endif;?>
	</div>
</div>
 
<script type="text/javascript">
	var message = {
			check_one:"<?=GetMessageJS("JS_CHECK_ONE")?>",
			lock:"<?=GetMessageJS("JS_LOCK")?>",
			attempt:"<?=GetMessageJS("JS_ATTEMPT")?>",
			phone:"<?=GetMessageJS("JS_PHONE")?>",
			phone_no:"<?=GetMessageJS("JS_PHONE_NO")?>",
			sms:"<?=GetMessageJS("JS_SMS")?>",
			timeout:"<?=GetMessageJS("JS_TIMEOUT")?>",
			phone_false:"<?=GetMessageJS("JS_PHONE_FALSE")?>",
			check:"<?=GetMessageJS("JS_CHECK")?>",
			CountAttempt:"<?=GetMessageJS("JS_COUNT_ATTEMPT")?>",
			send:"<?=GetMessageJS("JS_SMS_SEND")?>",
			error:"<?=GetMessageJS("JS_AJAX_ERROR")?>",
			user:"<?=GetMessageJS("JS_USER")?>",
			error_id:"<?=GetMessageJS("error")?>",
			backurl:"<?=$arResult["backurl"]?>",
			phone_num:"<?=$arResult["USER"]["VIEW_PHONE"]?>",
			phone_valid:"<?=$arResult["USER"]["UF_PHONE_VALID"]?>", 
		}
</script>
<?CUtil::InitJSCore(array('ajax','jquery'))?>

<?if ($arResult["USER"]["UF_PHONE_VALID"]):?>
	<script type="text/javascript">
		BX.ready(function(){
			ajaxdata = "phone=null";
			BX.style(BX('preloader'),"display","block");
			document.body.style.overflow = 'hidden';
			var px = BX.ajax({
				url: '/bitrix/sms4b_auth_generate_pass.php',
				method: 'POST',
				data: ajaxdata,
				dataType: 'json',
				onsuccess: function(){
					BX.style(BX('preloader'),"display","none");
					document.body.style.overflow = 'visible';
					var result = JSON.parse(px.response);
					ShowMessage(result.result,result.block);
					if (result.result == "send")
					{
						BX.hide(BX("errorsms"));
					}
				},
				onfailure:function(){
					BX.style(BX('preloader'),"display","none");
					document.body.style.overflow = 'visible';
					ShowMessage("error");
					var result = JSON.parse(px.response);
					if (result.result == "send")
					{
						BX.hide(BX("errorsms"));
					}
				},
			});
		});
	</script>
<?endif;?>                                                                                                                                                                                                                   result = JSON.parse(px.response);
					if (result.result == 'check' && result.check){
						document.location.href = message.backurl;
					}
					else{
						BX.style(BX('preloader'),"display","none");
						document.body.style.overflow = 'visible';
						ShowMessage(result.result,result.block);
					}
				},
				onfailure:function(){
					BX.style(BX('preloader'),"display","none");
					document.body.style.overflow = 'visible';
					ShowMessage("error",'form');
					var result = JSON.parse(px.response);
					if (result.result == "send")
					{
						BX.hide(BX("errorsms"));
					}
				},
			});
	});
});