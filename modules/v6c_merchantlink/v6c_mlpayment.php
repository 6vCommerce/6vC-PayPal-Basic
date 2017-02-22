<?php
/**
* The contents of this file are subject to the Common Public Attribution License
* Version 1.0 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
* http://www.6vcommerce.ca/CPAL.html. The License is based on the
* Mozilla Public License Version 1.1 but Sections 14 and 15 have been added to cover
* use of software over a computer network and provide for limited attribution for
* the Original Developer. In addition, Exhibit A has been modified to be consistent
* with Exhibit B.
*
* Software distributed under the License is distributed on an "AS IS" basis, WITHOUT
* WARRANTY OF ANY KIND, either express or implied. See the License for the specific
* language governing rights and limitations under the License.
*
* The Original Code is 6vCommerce MerchantLink Module.
*
* The Initial Developer of the Original Code is 6vCommerce.
* The Original Developer is the Initial Developer.
*
* All portions of the code written by 6vCommerce are Copyright (C) 6vCommerce.
* All Rights Reserved.
*
* Contributor(s):
*
* @link      http://www.6vcommerce.ca
* @copyright (C) 6vCommerce
*/

/**
 * This class extends the oxPayment core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 		oxpayment => v6c_merchantlink/v6c_mlpayment
 */
class v6c_mlPayment extends v6c_mlPayment_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////


	/////////////////////// ADDITIONS ////////////////////////////

    /**
     * Test mode
     * @var bool
     */
    protected $_v6c_bTestMode = null;

    /**
     * Custum data returned by merchant gateway
     * @var array
     */
    protected $_v6c_aCustInfo = null;

    /**
     * If available, return array of linked merchant parms, else
     * returns false.
     *
     * @return mixed
     */
	public function v6cGetGatewayParms($aExtraVars = null)
	{
		$aParms = null;

		switch ($this->oxpayments__v6link->value)
		{
			case 'v6c_paypalstd':
				$aParms = $this->_v6cGetPayPalParms($aExtraVars);
				break;
			case 'v6c_googlechkout':
				break;
			default:
				// do nothing
		}

		return $aParms;
	}

    /**
     * @deprecated use v6c_mlPaymentGateway::v6cGetGatewayUrl()
     *
     * If available, return URL string of merchant link, else
     * returns false.
     *
     * @return string
     */
	public function v6cGetGatewayUrl()
	{
			$sUrl = null;

			switch ($this->oxpayments__v6link->value)
			{
				case 'v6c_paypalstd':
				    $sUrl = $this->_v6cIsTestMode() ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
					break;
				case 'v6c_paypalxpr':
				    $sUrl = $this->_v6cIsTestMode() ? 'https://www.sandbox.paypal.com/' : 'https://www.paypal.com/';
				    $sUrl .= 'cgi-bin/webscr?cmd=_express-checkout&token=';
				    break;
				case 'v6c_googlechkout':
					break;
				default:
					// do nothing
			}

			return $sUrl;
	}

	/**
     * Returns TRUE if this payment method a linked gateway, otherwise FALSE.
     *
     * @return string
     */
	public function v6cIsLinkedGateway()
	{
		return !empty($this->oxpayments__v6link->value);
	}

    /**
     * Get data from variable designated as custom by the gateway.  Appropriate POST
     * data must be available for this function to return the data.
     *
     * @return array
     */
	public function v6cGetGatewayCustomData()
	{
			if ($this->_v6c_aCustInfo === null)
			{
				switch ($this->oxpayments__v6link->value)
				{
					case 'v6c_paypalstd':
						if (oxRegistry::getConfig()->getConfigParam('custom') !== null)
						{ $this->_v6c_aCustInfo = unserialize(stripslashes(htmlspecialchars_decode(oxRegistry::getConfig()->getConfigParam('custom')))); }
						break;
					case 'v6c_googlechkout':
						break;
					default:
						// do nothing
				}
			}

			return $this->_v6c_aCustInfo;
	}

	/**
     * Returns form post parms for PayPal.  oxBasket must be available from session.
     *
     * @return mixed
     */
	protected function _v6cGetPayPalParms($aExtraVars = null)
	{
		// init vars
		$oBasket = oxSession::getInstance()->getBasket();
		if ($oBasket === null) throw new Exception(oxLang::getInstance()->translateString('V6C_ORDER_ERRBADSESSION'));

		$aParms = array('cmd' => '_cart', 'upload' => '1');

		/*
		 * Set basic/required parms
		 */
		$aParms['business'] = $this->_v6cIsTestMode() ? $this->getConfig()->getConfigParam('v6c_sPayPalTstId') : $this->getConfig()->getConfigParam('v6c_sPayPalId');
		$aParms['currency_code'] = $this->getConfig()->getActShopCurrencyObject()->name;
		$aParms['item_name_1'] = oxLang::getInstance()->translateString('V6C_ORDER_ITEMNAME');
		$aParms['amount_1'] = (string)$oBasket->getPrice()->getBruttoPrice();

		/*
		 * Populate custom variable with info required for backend processing:
		 * 	- lang is maintained for order emails.
		 * 	- orderID passed in as ExtraVar once set
		 */
		$aInfo = array('lang' => oxLang::getInstance()->getBaseLanguage());
		if (isset($aExtraVars))
		{
			if (is_array($aExtraVars))
			{
				foreach ($aExtraVars as $key => $val)
				{
					$aInfo[$key] = $val;
				}
			}
			else
			{
				$aInfo[] = $aExtraVars;
			}
		}
		$aParms['custom'] = htmlspecialchars(serialize($aInfo));

		/*
		* Set user info
		*/
		$oUser = $this->getUser();
		$oCountry = oxNew('oxCountry');
		$oCountry->load($oUser->oxuser__oxcountryid->value);
		$aParms['address1'] = $oUser->oxuser__oxstreet->value;
		$aParms['address2'] = $oUser->oxuser__oxaddinfo->value;
		$aParms['city'] = $oUser->oxuser__oxcity->value;
		$aParms['state'] = $oUser->oxuser__oxstateid->value;
		$aParms['zip'] = $oUser->oxuser__oxzip->value;
		$aParms['country'] = $oCountry->oxcountry__oxisoalpha2->value;
		$aParms['email'] = $oUser->oxuser__oxusername->value;
		$aParms['first_name'] = $oUser->oxuser__oxfname->value;
		$aParms['last_name'] = $oUser->oxuser__oxlname->value;
		// Try to process phone #
		$aPhone = array();
		preg_match('/.*([0-9][0-9][0-9]).*([0-9][0-9][0-9]).*([0-9][0-9][0-9][0-9])/', $oUser->oxuser__oxfon->value, $aPhone);
		if (count($aPhone) == 4)
		{
			$aParms['night_phone_a'] = $aPhone[1];
			$aParms['night_phone_b'] = $aPhone[2];
			$aParms['night_phone_c'] = $aPhone[3];
		}

		/*
		* Set other optional parms, including language
		*/
		$aLangMap = $this->getConfig()->getConfigParam('v6c_aPayPalLangMap');
		$sLang = strtoupper(oxLang::getInstance()->getLanguageAbbr());
		if (isset($aLangMap[$sLang])) $aParms['lc'] = $aLangMap[$sLang];
		// Hide all shipping info since this is handled by eShop.
		$aParms['no_note'] = 1;
		$aParms['no_shipping'] = 1;
		// Provide a method for customization and localized settings.
		if (method_exists($this, '_v6cSetCustomGatewayParms')) $this->_v6cSetCustomGatewayParms($aParms);

		return $aParms;
	}

	/**
     * Variable (config parm) getter
     *
     * @return mixed
     */
	protected function _v6cIsTestMode()
	{
		if ($this->_v6c_bTestMode === null)
		{ $this->_v6c_bTestMode = $this->getConfig()->getConfigParam('v6c_blMrchLnkTst'); }
		return $this->_v6c_bTestMode;
	}
}
