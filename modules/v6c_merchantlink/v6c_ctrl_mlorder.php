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
 * This class extends the Order control class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	order => v6c_merchantlink/v6c_ctrl_mlorder
 */
class v6c_ctrl_mlOrder extends v6c_ctrl_mlorder_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////

    /**
     * Add support for receiving integrated merchant gateway parms in query string
     *
     * @return string
     */
    public function render()
    {
        $sRet = parent::render();
        // Will be available otherwise parent method would have thrown error
        $oPayment = $this->getPayment();
        if ($oPayment->v6cIsLinkedGateway())
        {
            /* TODO
             * WIP: Code to save payment authorizations
            // Flag integrated linked merchant gateway step as completed
            oxSession::setVar( 'v6c_bHaveLnkPayInfo', true );
            */
            // Process any query strings returned by gateway (via return URL)
            $oPaymentGateway = oxNew('oxPaymentGateway');
            $sFn = $oPayment->oxpayments__v6link->value.'_ProcessQueryStr';
            if (method_exists($oPaymentGateway, $sFn))
            { $oPaymentGateway->$sFn(); }
        }
        return $sRet;
    }

    /**
    * Make basket available to payment gateway.
    */
    /* TODO
     * WIP: Code to save payment authorizations
    protected function _executePayment( oxBasket $oBasket, $oUserpayment )
    {
        $this->_oBasket = $oBasket;
        $ret = parent::_executePayment($oBasket, $oUserpayment);
        $this->_oBasket = null; // Probably not neccessary but erasing all tracks
        return $ret;
    }
    */


	/////////////////////// ADDITIONS ////////////////////////////

}