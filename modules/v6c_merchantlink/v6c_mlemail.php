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
 * This class extends the oxEmail core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	oxemail => v6c_merchantlink/v6c_mlemail
 */
class v6c_mlEmail extends v6c_mlEmail_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////

    /**
     * Adds new template variables that can be used to display detailed
     * PayPal transaction info in confirmation email.
     *
     * @param oxOrder $oOrder   Order object
     * @param string  $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendOrderEmailToOwner( $oOrder, $sSubject = null )
    {
    	$bRstLang = false;

    	// Extension only applies to linked payment orders
    	if ($this->_v6cIsPaymentLinked($oOrder))
    	{
    		// set additional tpl vars
	        $oSmarty = $this->_getSmarty();
	        $aUsrPayParms = unserialize($oOrder->getPayment()->oxuserpayments__oxvalue->getRawValue());
	        if (is_string($aUsrPayParms))
	        	$oSmarty->assign( "aUsrPayParms", array($aUsrPayParms));
	        elseif (is_array($aUsrPayParms))
	        	 $oSmarty->assign( "aUsrPayParms", $aUsrPayParms);
	        else
	        	 $oSmarty->assign( "aUsrPayParms", array());
    	}

    	$bRet = parent::sendOrderEmailToOwner( $oOrder, $sSubject );

        return $bRet;
    }

	/////////////////////// ADDITIONS ////////////////////////////

    /**
     * Is payment method assoicated with order linked to a merchant gateway?
     *
     * @var bool
     */
    protected $_v6c_bLinkedPay = null;

    /**
     * Is payment method assoicated with order linked to a merchant gateway?
     *
     * @param oxOrder $oOrder   Order object
     *
     * @return bool
     */
    protected function _v6cIsPaymentLinked(oxOrder $oOrder)
    {
    	if ($this->_v6c_bLinkedPay == null)
    	{
    		$this->_v6c_bLinkedPay = false;
    		$oPayment = oxNew('oxPayment');
    		if ($oPayment->load($oOrder->oxorder__oxpaymenttype->value))
    		{
    			$this->_v6c_bLinkedPay = $oPayment->v6cIsLinkedGateway();
    		}
    	}
    	return $this->_v6c_bLinkedPay;
    }
}