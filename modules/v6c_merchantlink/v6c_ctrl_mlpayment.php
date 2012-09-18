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
 * This class extends the Payment control class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	payment => v6c_merchantlink/v6c_ctrl_mlpayment
 */
class v6c_ctrl_mlPayment extends v6c_ctrl_mlPayment_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////

    /**
     * Call parent and only set fAddPaymentSum if non-zero.  Minor
     * change to avoid displaying $0.00 on payment page for methods
     * that cost nothing.
     *
     * @param array    &$aPaymentList payments array
     * @param oxBasket $oBasket       basket object
     *
     * @return null
     */
    protected function _setDeprecatedValues( & $aPaymentList, $oBasket = null )
    {
    	parent::_setDeprecatedValues( $aPaymentList, $oBasket );

        if ( is_array($aPaymentList) )
        {
            foreach ( $aPaymentList as $oPayment )
            {
                if ($oPayment->dAddPaymentSum == 0)
                { $oPayment->fAddPaymentSum = null; }
            }
        }
    }

    /**
    * Add support for linked payments that integrate into checkout steps.  If parent function
    * returns as OK (returns string 'order') then re-direct for applicable linked payment type.
    *
    * Session variables:
    * <b>paymentid</b>, <b>dynvalue</b>, <b>payerror</b>
    *
    * @return  mixed
    */
    public function validatePayment()
    {
        $ret = parent::validatePayment();

        if ($ret == 'order')
        {
            // Check if payment type is linked and integrated into checkout
            if (!($sPaymentId = oxConfig::getParameter( 'paymentid' ))) $sPaymentId = oxSession::getVar('paymentid');
            $oPayment = oxNew( 'oxpayment' );
            $oPayment->load( $sPaymentId );
            if ($oPayment->v6cIsLinkedGateway())
            {
                /* WIP: Uncomment when ready to support saved payment authorizations
                $bUsePrevInfo = oxConfig::getParameter( 'v6c_lnkpaydone' ) == 'true' ? true : false;
                // Redirect to merchant gateway if previously submitted info is not available and user does not want to re-enter
                if (!$this->v6cHaveLnkPayInfo() || !$bUsePrevInfo)
                */
                    $ret = 'v6c_redirectpost';
            }
        }

        return $ret;
    }

    /////////////////////// ADDITIONS ////////////////////////////

    /**
    * Check if information for an integrated linked payment method has already been completed.
    *
    * @return  bool
    */
    public function v6cHaveLnkPayInfo()
    {
        if (oxSession::getVar('v6c_bHaveLnkPayInfo') === true) return true;
        else return false;
    }
}