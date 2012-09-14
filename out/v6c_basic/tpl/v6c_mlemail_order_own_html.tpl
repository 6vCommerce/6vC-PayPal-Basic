<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>[{ $shop->oxshops__oxordersubject->value }]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
  </head>
  <body bgcolor="#FFFFFF" link="#355222" alink="#355222" vlink="#355222" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;">
    <img src="[{$oViewConf->getNoSslImageDir()}]/logo_white.gif" border="0" hspace="0" vspace="0" alt="[{ $shop->oxshops__oxname->value }]" align="texttop"><br><br>
    [{if $payment->oxuserpayments__oxpaymentsid->value == "oxempty"}]
      [{oxcontent ident="oxadminordernpemail"}]
    [{else}]
      [{oxcontent ident="oxadminorderemail"}]
    [{/if}]
    [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_ORDERNOMBER" }] <b>[{ $order->oxorder__oxordernr->value }]</b><br><br>
    <table border="0" cellspacing="0" cellpadding="0" width="600">
      <tr>
        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; background-color: #494949; color: #FFFFFF;" height="15" width="100">
          &nbsp;&nbsp;<b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PRODUCT" }]</b>
        </td>
        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; background-color: #494949; color: #FFFFFF;" height="15">
        </td>
        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; background-color: #494949; color: #FFFFFF;" align="right" width="70">
          <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_UNITPRICE" }]</b>
        </td>
        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; background-color: #494949; color: #FFFFFF;" align="right" width="70">
          <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_QUANTITY" }]</b>
        </td>
        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; background-color: #494949; color: #FFFFFF;" align="right" width="70">
          <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_TOTAL" }]</b>&nbsp;&nbsp;
        </td>
      </tr>
      [{foreach from=$order->getOrderArticles() key=sOrdItmId item=oOrdItm}]
        <tr>
	        <td valign="top" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; padding-top: 10px;">
	          <img src="[{ $oViewConf->getPictureDir() }]/[{ $oOrdItm->oxorderarticles__oxthumb->value }]" border="0" hspace="0" vspace="0" alt="[{ $oOrdItm->oxorderarticles__oxtitle->value|strip_tags }]" align="texttop">
	            [{if $oViewConf->getShowGiftWrapping() }]
	            <br><b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_WRAPPING" }]&nbsp;</b>[{ if !$oOrdItm->getWrapping() }][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_NONE" }][{else}][{assign var="oWrap" value=$oOrdItm->getWrapping()}][{$oWrap->oxwrapping__oxname->value}][{/if}]
	            [{/if}]
	        </td>
	        <td valign="top" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; padding-top: 10px;">
	          <b>[{ $oOrdItm->oxorderarticles__oxtitle->value }][{ if $oOrdItm->oxorderarticles__oxselvariant->value}], [{ $oOrdItm->oxorderarticles__oxselvariant->value}][{/if}]</b>
	          [{ if $oOrdItm->getSelectLists() }],
	            [{foreach from=$oOrdItm->getSelectLists() item=oList}]
	              [{ $oList->name }] [{ $oList->value }]&nbsp;
	            [{/foreach}]
	          [{/if}]
	          [{ if $oOrdItm->getPersParams() }]
	            [{foreach key=sVar from=$oOrdItm->getPersParams() item=aParam}]
	              ,&nbsp;<em>[{$sVar}] : [{$aParam}]</em>
	            [{/foreach}]
	          [{/if}]
	          <br>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_ARTNOMBER" }] [{ $oOrdItm->oxorderarticles__oxartnum->value }]
	        </td>
	        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; padding-top: 10px;" valign="top" align="right">
	          <b>[{if $oOrdItm->oxorderarticles__oxbprice->value }][{ $oViewConf->v6cFormatCurrency($oOrdItm->oxorderarticles__oxbprice->value, $oOrdCur) }][{/if}]</b>
	          <!-- Following commented code has no equivalent values from order info -->
	          [{*if $basketitem->aDiscounts}]<br><br>
	            <em style="font-size: 7pt;font-weight: normal;">[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_DISCOUNT" }]
	            [{foreach from=$basketitem->aDiscounts item=oDiscount}]
	              <br>[{ $oDiscount->sDiscount }]
	            [{/foreach}]
	            </em>
	          [{/if}]
	          [{ if $basketproduct->oxarticles__oxorderinfo->value }]
	            [{ $basketproduct->oxarticles__oxorderinfo->value }]
	          [{/if*}]
	        </td>
	        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; padding-top: 10px;" valign="top" align="right">
	          [{ $oOrdItm->oxorderarticles__oxamount->value }]
	        </td>
	        <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px; padding-top: 10px;" valign="top" align="right">
	          <b>[{ $oViewConf->v6cFormatCurrency($oOrdItm->oxorderarticles__oxbrutprice->value) }]</b>
	        </td>
        </tr>
      [{/foreach}]
      <tr>
        <td height="1" bgcolor="#BEBEBE"></td>
        <td height="1" bgcolor="#BEBEBE"></td>
        <td height="1" bgcolor="#BEBEBE"></td>
        <td height="1" bgcolor="#BEBEBE"></td>
        <td height="1" bgcolor="#BEBEBE"></td>
        <td height="1" bgcolor="#BEBEBE"></td>
      </tr>
    </table>
    <br>
    
  	[{if $order->getGiftCard() }]
  	  [{assign var="oCard" value=$basket->getCard()}]
      <table border="0" cellspacing="0" cellpadding="2" width="600">
        <tr>
          <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
            <b>[{ oxmultilang ident="EMAIL_ORDER_OWNER_HTML_ATENTIONGREETINGCARD" }]</b><br>
            <img src="[{$oCard->nossl_dimagedir}]/0/[{$oCard->oxwrapping__oxpic->value}]" alt="[{$oCard->oxwrapping__oxname->value}]" hspace="0" vspace="0" border="0" align="top"><br><br>
          </td>
          <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
            [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_YOURMESSAGE" }]<br><br>
            [{$order->oxorder__oxcardtext->value}]
          </td>
        </tr>
      </table>
      <br>
  	[{/if}]    

    <table border="0" cellspacing="0" cellpadding="2" width="600">
      <tr>
        <td width="50%" valign="top">
          [{if $oViewConf->getShowVouchers() }]
          <table border="0" cellspacing="0" cellpadding="0">
            [{if $basket->dVoucherDiscount }]
              <tr>
                <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
                  [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_USEDCOUPONS" }]<br>
                </td>
                <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
                  [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_REBATE" }]
                </td>
              </tr>
            [{/if}]
            [{ foreach from=$vouchers item=voucher}]
              <tr>
                  <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
                    [{$voucher->oxmodvouchers__oxvouchernr->value}]
                  </td>
                  <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top">
                    [{ if $voucher->oxmodvouchers__oxdiscounttype->value == "absolute"}][{$oViewConf->v6cFormatCurrency($voucher->oxmodvouchers__oxdiscount->value, $oOrdCur)}][{else}][{$voucher->oxmodvouchers__oxdiscount->value}]%[{/if}]
                  </td>
              </tr>
            [{/foreach }]
          </table>
          [{/if}]
        </td>
        <td width="50%" valign="top">
          <table border="0" cellspacing="0" cellpadding="2" width="300">

		  <!--  basket subtotal  -->
		  <tr>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
              [{ oxmultilang ident="V6C_BASKET_PRODUCTSTOTAL" }]
            </td>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right" width="60">
              [{ $oViewConf->v6cFormatCurrency($order->oxorder__oxtotalbrutsum->value) }]
            </td>
          </tr>

          [{assign var="v6c_bAdjustmentsBorder" value=false }]

          <!--  discounts: only applies to basket-specific discounts (such as per/qty), not global discounts  -->
          [{if $order->oxorder__oxdiscount->value != 0}]
          	<tr><td height="1"></td><td height="1" bgcolor="#BEBEBE"></td></tr>
          	[{assign var="v6c_bAdjustmentsBorder" value=true }]
			<tr>
	          <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
	            [{if $order->oxorder__oxdiscount->value > 0}][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_DICOUNT" }][{else}][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_CHARGE" }][{/if}]
	          </td>
	          <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right" width="60">
	            [{if $order->oxorder__oxdiscount->value > 0}]-[{/if}][{ $oViewConf->v6cFormatCurrency($order->oxorder__oxdiscount->value, $oOrdCur)|replace:"-":"" }]
	          </td>
	        </tr>
          [{/if}]

          <!--  vouchers/coupons  -->
          [{if $oViewConf->getShowVouchers() && $order->oxorder__oxvoucherdiscount->value != 0}]
            [{if !$v6c_bAdjustmentsBorder}]<tr><td height="1"></td><td height="1" bgcolor="#BEBEBE"></td></tr>[{/if}]
            <tr>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_COUPON" }]
              </td>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{ if $order->oxorder__oxvoucherdiscount->value > 0 }]-[{/if}][{ $oViewConf->v6cFormatCurrency($order->oxorder__oxvoucherdiscount->value, $oOrdCur)|replace:"-":"" }]
              </td>
            </tr>
          [{/if}]

		  <tr><td height="1"></td><td height="1" bgcolor="#BEBEBE"></td></tr>

          <!--  delivery costs  -->
          <tr>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
              [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_SHIPPINGGROSS1" }]
            </td>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
              [{ $oViewConf->v6cFormatCurrency($order->oxorder__oxdelcost->value, $oOrdCur) }]
            </td>
          </tr>

		  <!--  payment charges  -->
		  [{if $order->oxorder__oxpaycost->value != 0}]
            <tr>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{if $order->oxorder__oxpaycost->value >= 0}][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PAYMENTCHARGEDISCOUNT1" }][{else}][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PAYMENTCHARGEDISCOUNT2" }][{/if}] [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PAYMENTCHARGEDISCOUNT3" }]
              </td>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{ $oViewConf->v6cFormatCurrency($order->oxorder__oxpaycost->value, $oOrdCur) }]
              </td>
            </tr>
		  [{/if}]

		  <!--  TrustedShops protection costs  -->
		  [{if $order->oxorder__oxtsprotectcosts->value != 0}]
            <tr>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_TSPROTECTION" }]
              </td>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                [{ $oViewConf->v6cFormatCurrency($order->oxorder__oxtsprotectcosts->value, $oOrdCur) }]
              </td>
            </tr>
		  [{/if}]

		  <!--  gift wrapping/card costs  -->
		  [{ if $oViewConf->getShowGiftWrapping() && $order->oxorder__oxwrapcost->value != 0 }]
            <tr>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_WRAPPINGANDGREETINGCARD1" }]
              </td>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ $oViewConf->v6cFormatCurrency($order->oxorder__oxwrapcost->value, $oOrdCur) }]
              </td>
            </tr>
		  [{/if}]

		  <!--  order net total & tax  -->
		  [{if $oViewConf->v6cIsTaxOff() }]
		    <tr><td height="1"></td><td height="1" bgcolor="#BEBEBE"></td></tr>
		    <!--  net total  -->
            <tr>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ oxmultilang ident="V6C_ORDER_TOTAL" }]
              </td>
              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ $basket->v6cGetFPriceNet() }]
              </td>
            </tr>
            <!--  tax  -->
		    [{if $oViewConf->v6cIsTaxLabelled() }]
		      [{foreach from=$basket->v6cGetBasketTaxes() item=sFTaxCost key=sTaxId }]
	            <tr>
	              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
	                  [{ oxmultilang ident="V6C_ORDER_TAX_1" }][{$sTaxId}][{ oxmultilang ident="V6C_ORDER_TAX_2" }]
	              </td>
	              <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
	                  [{$sFTaxCost}]
	              </td>
	            </tr>
		      [{/foreach}]
		    [{else}]
              <tr>
                <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ oxmultilang ident="V6C_ORDER_TAX" }]
                </td>
                <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
                  [{ $basket->v6cGetFPriceTax() }]
                </td>
              </tr>
		    [{/if}]
		  [{/if}]

          <tr><td height="1"></td><td height="1" bgcolor="#BEBEBE"></td></tr>

          <!--  order grand total  -->
          <tr>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
              <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_GRANDTOTAL" }]</b>
            </td>
            <td style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;" valign="top" align="right">
              <b>[{ $oViewConf->v6cFormatCurrency($order->oxorder__oxtotalordersum->value, $oOrdCur) }]</b>
            </td>
          </tr>

          </table>
        </td>
      </tr>
    </table>
    <br>
    
    [{ if $order->oxorder__oxremark->value }]
      <b>[{ oxmultilang ident="EMAIL_ORDER_OWNER_HTML_MESSAGE" }]</b> [{ $order->oxorder__oxremark->value }]<br>
      <br>
    [{/if}]
    
    [{if $payment->oxuserpayments__oxpaymentsid->value != "oxempty"}]
      <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PAYMENTMETHOD" }]</b> [{ $payment->oxpayments__oxdesc->value }]<br>
      <b>[{ oxmultilang ident="V6C_EMAIL_ORDER_OWNER_PAYMENTEXTRA" }]</b><br>
      [{foreach from=$aUsrPayParms key=sKey item=sValue}]
        [{$sKey}]: [{$sValue}]<br>
      [{/foreach}]
      <br>
    [{/if}]

    <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_EMAILADDRESS" }]</b> [{ $user->oxuser__oxusername->value }]<br>
    <br>
    
    <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_BILLINGADDRESS" }]</b><br>
    <br>
    [{if $order->oxorder__oxbillcompany->value }][{ $order->oxorder__oxbillcompany->value }]<br>[{/if}]
    [{* $order->oxorder__oxbillsal->value|oxmultilangsal *}][{ $order->oxorder__oxbillfname->value }] [{ $order->oxorder__oxbilllname->value }]<br>
    [{ $order->oxorder__oxbillstreet->value }][{* $order->oxorder__oxbillstreetnr->value *}]<br>
    [{if $order->oxorder__oxbilladdinfo->value }][{ $order->oxorder__oxbilladdinfo->value }]<br>[{/if}]
    [{ $order->oxorder__oxbillcity->value }], [{ $order->oxorder__oxbillstateid->value }] [{ $order->oxorder__oxbillzip->value|upper }]<br>
    [{ $order->oxorder__oxbillcountry->value }]<br>
    <br>
    [{*if $order->oxorder__oxbillustid->value}][{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_VATIDNOMBER" }] [{ $order->oxorder__oxbillustid->value }]<br>[{/if*}]
    [{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_PHONE" }] [{ $order->oxorder__oxbillfon->value }]<br>
    <br>
    
    [{ if $order->oxorder__oxdellname->value }]
      <b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_SHIPPINGADDRESS" }]</b><br>
      <br>
      [{if $order->oxorder__oxdelcompany->value }][{ $order->oxorder__oxdelcompany->value }]<br>[{/if}]
      [{ $order->oxorder__oxdelsal->value|oxmultilangsal }] [{ $order->oxorder__oxdelfname->value }] [{ $order->oxorder__oxdellname->value }]<br>
      [{ $order->oxorder__oxdelstreet->value }] [{ $order->oxorder__oxdelstreetnr->value }]<br>
      [{if $order->oxorder__oxdeladdinfo->value }][{ $order->oxorder__oxdeladdinfo->value }]<br>[{/if}]
      [{ $order->oxorder__oxdelcity->value }], [{ $order->oxorder__oxdelstateid->value }] [{ $order->oxorder__oxdelzip->value|upper }]<br>
      [{ $order->oxorder__oxdelcountry->value }]<br>
      <br>
    [{/if}]

    [{if $payment->oxuserpayments__oxpaymentsid->value != "oxempty"}]<b>[{ oxmultilang ident="EMAIL_ORDER_CUST_HTML_SHIPPINGCARRIER" }]</b> [{ $order->oDelSet->oxdeliveryset__oxtitle->value }]<br>[{/if}]

	[{*-- V6Ccourierws MOD BEGINS: Display packaging info --*}]
	[{if $aFPkgLst}]
		<b>[{ oxmultilang ident="V6C_COURIERWS_PACKING" }]</b><br>
		[{foreach from=$aFPkgLst item=sPack name=pkglists}]
			&nbsp;&nbsp;&nbsp;&nbsp;[{$sPack}]<br>
		[{/foreach}]
	[{/if}]
    [{*-- V6C MOD ENDS --*}]

    <br><br>
    [{ oxcontent ident="oxemailfooter" }]
    
  </body>
</html>
