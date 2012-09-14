[{*--
  * Use shop_main.tpl as a basis for merging diffs on updates
--*}]

[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{cycle assign="_clear_" values=",2" }]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="v6c_merchantlinkmain">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="v6c_merchantlinkmain">
<input type="hidden" name="fnc" value="save">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">


    <table border=0>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type="submit" class="confinput" name="save" value="[{ oxmultilang ident="V6C_INSTALL" }]" onClick="Javascript:document.myedit.fnc.value='v6cInstallML'" [{ $readonly }] [{$v6c_sMlInstalled}]>
            [{ oxinputhelp ident="V6C_HELP_INSTLMERCKLNK" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_INSTLMERCKLNK" }]
         </td>
        </tr>

		[{if $oViewConf->v6cIsMdlInst('options')}]
        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[v6c_blCompactChkOut] value=false>
            <input type=checkbox name=confbools[v6c_blCompactChkOut] value=true  [{if ($confbools.v6c_blCompactChkOut)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="V6C_HELP_COMPACTCHKOUT" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_COMPACTCHKOUT" }]
         </td>
        </tr>
		[{else}]<input type=hidden name=confbools[v6c_blCompactChkOut] value=false>
		[{/if}]

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[v6c_blHideSinglePay] value=false>
            <input type=checkbox name=confbools[v6c_blHideSinglePay] value=true  [{if ($confbools.v6c_blHideSinglePay)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="V6C_HELP_HIDESNGLPAY" }]
         </td>
         <td valign="middle" width="100%">
          [{ oxmultilang ident="V6C_HIDESNGLPAY" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<textarea class="txtfield" name=confaarrs[v6c_aPayPalLangMap] [{ $readonly }]>[{$confaarrs.v6c_aPayPalLangMap}]</textarea>
			[{ oxinputhelp ident="V6C_HELP_PAYPALLANGMAP" }]
         </td>
         <td valign="middle" width="100%">
          	[{ oxmultilang ident="V6C_PAYPALLANGMAP" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalEmail] value="[{$confstrs.v6c_sPayPalEmail}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALEMAIL" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALEMAIL" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalId] value="[{$confstrs.v6c_sPayPalId}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALID" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALID" }]
         </td>
        </tr>

        [{* Probably best to not give user access to this...
        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalNvpVer] value="[{$confstrs.v6c_sPayPalNvpVer}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALNVPVER" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALNVPVER" }]
         </td>
        </tr>
        *}]

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalNvpUsr] value="[{$confstrs.v6c_sPayPalNvpUsr}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALNVPUSR" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALNVPUSR" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalNvpPwd] value="[{$confstrs.v6c_sPayPalNvpPwd}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALNVPPWD" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALNVPPWD" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalNvpSig] value="[{$confstrs.v6c_sPayPalNvpSig}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALNVPSIG" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALNVPSIG" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalPdtTkn] value="[{$confstrs.v6c_sPayPalPdtTkn}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALPDTTKN" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALPDTTKN" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[v6c_blPayPalSslPdt] value=false>
            <input type=checkbox name=confbools[v6c_blPayPalSslPdt] value=true  [{if ($confbools.v6c_blPayPalSslPdt)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="V6C_HELP_PAYPALSSLPDT" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALSSLPDT" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[v6c_blMrchLnkTst] value=false>
            <input type=checkbox name=confbools[v6c_blMrchLnkTst] value=true  [{if ($confbools.v6c_blMrchLnkTst)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="V6C_HELP_MERCHLINKTEST" }]
         </td>
         <td valign="middle" width="100%">
          [{ oxmultilang ident="V6C_MERCHLINKTEST" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalTstEmail] value="[{$confstrs.v6c_sPayPalTstEmail}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALTSTEMAIL" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALTSTEMAIL" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalTstId] value="[{$confstrs.v6c_sPayPalTstId}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALTSTID" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALTSTID" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalTstPdtTkn] value="[{$confstrs.v6c_sPayPalTstPdtTkn}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALTSTPDTTKN" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALTSTPDTTKN" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
			<input type=text class="txt" name=confstrs[v6c_sPayPalCountryCode] value="[{$confstrs.v6c_sPayPalCountryCode}]" [{ $readonly}]>
			[{ oxinputhelp ident="V6C_HELP_PAYPALLOCAL" }]
         </td>
         <td valign="middle" width="100%" >
            [{ oxmultilang ident="V6C_PAYPALLOCAL" }]
         </td>
        </tr>
[{*
        <tr class="conftext[{cycle}]">
         <td valign="middle" class="nowrap">
           <select class="confinput" name=confstrs[iTBD] [{ $readonly }]>
             <option value="0" [{ if $confstrs.iTBD == 0}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
             <option value="1" [{ if $confstrs.iTBD == 1}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
             <option value="2" [{ if $confstrs.iTBD == 2}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
           </select>
           [{ oxinputhelp ident="HELP_TBD" }]
         </td>
         <td valign="middle" width="100%">
              [{ oxmultilang ident="TBD" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
           <select class="confinput" name=confstrs[iTBD] [{ $readonly }]>
             <option value="0" [{ if $confstrs.iTBD == 0}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
             <option value="1" [{ if $confstrs.iTBD == 1}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
             <option value="2" [{ if $confstrs.iTBD == 2}]SELECTED[{/if}]>[{ oxmultilang ident="TBD" }]</option>
           </select>
           [{ oxinputhelp ident="HELP_TBD" }]
         </td>
         <td valign="middle" width="100%">
              [{ oxmultilang ident="TBD" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[blTBD] value=false>
            <input type=checkbox name=confbools[blTBD] value=true  [{if ($confbools.blTBD)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="HELP_TBD" }]
         </td>
         <td valign="middle" width="100%">
          [{ oxmultilang ident="TBD" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[blTBD] value=false>
            <input type=checkbox class="confinput" name=confbools[blTBD] value=true  [{if ($confbools.blTBD)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="HELP_TBD" }]
         </td>
         <td valign="middle" width="100%" >
           [{ oxmultilang ident="TBD" }]
         </td>
        </tr>

        <tr class="conftext[{cycle}]">
         <td valign="middle">
            <input type=hidden name=confbools[blTBD] value=false>
            <input type=checkbox class="confinput" name=confbools[blTBD] value=true  [{if ($confbools.blTBD)}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="HELP_TBD" }]
         </td>
         <td valign="middle" width="100%" >
           [{ oxmultilang ident="TBD" }]
         </td>
*}]
		<!-- Forcing width of first column -->
        <tr>
         <td valign="middle" class="nowrap">
         	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </td>
         <td valign="middle" width="100%"></td>
        </tr>
    </table>

	<input type="submit" class="confinput" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }]>

</form>

[{*include file="bottomnaviitem.tpl"*}]

[{include file="bottomitem.tpl"}]