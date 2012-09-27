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
 * This class extends the oxOrder core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 		oxorder => v6c_merchantlink/v6c_mlorder
 */
class v6c_mlOrder extends v6c_mlOrder_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

    /**
     * Correcting definition. oxUserPayment, not oxPayment.
     * Redundant code, for documentation only.
     *
     * @var oxUserPayment
     */
    protected $_oPayment = null;

    /**
     * Correcting definition.  Returns an oxUserPayment object.
     * Redundant code, for documentation only.
     *
     * @return oxUserPayment
     */
    public function getPayment()
    {
        return parent::getPayment();
    }

	/**
     * Correcting definition.  $oPayment is of type oxUserPayment, not oxPayment.
     * Furthermore, arguments should all be required at this point because parent
     * functions ALWAYS sets them which would clear them if left null.
     *
     * Note that magic getter in oxUserPayment allows access to oxPayment db fields.
     *
     * @param oxUser    	$oUser    		order user
     * @param oxBasket  	$oBasket  		current order basket
     * @param oxUserPayment $oUsrPayment	order payment
     *
     * @return bool
     */
    protected function _sendOrderByEmail( oxUser $oUser, oxbasket $oBasket, oxUserPayment $oUsrPayment )
    {
    	return parent::_sendOrderByEmail($oUser,$oBasket,$oUsrPayment);
    }


	/////////////////////// EXTENSIONS ////////////////////////////

    /**
     * Allow Folder to be set to supplied value.
     *
     * @return null
     */
    protected function _setFolder($sFolder = null)
    {
    	if ($sFolder === null)
    		{ parent::_setFolder(); }
    	elseif (is_string($sFolder))
    		// Not sure why T_RAW is specified here, but this is what parent does
    		{ $this->oxorder__oxfolder = new oxField($sFolder, oxField::T_RAW); }
    }

    /**
     * Calls parent.  If order is pending, delete it w/o affecting stock,
     * otherwise delete as usual.
     *
     * @param string $sOxId Ordering ID (default null)
     *
     * @return bool
     */
    public function delete( $sOxId = null )
    {
    	if ( (isset($sOxId) && $this->load($sOxId)) || $this->isLoaded() )
    	{
	    	if (strcmp($this->oxorder__oxfolder->value, 'V6C_ORDERFOLDER_PENDING') == 0)
	    	{
	    		// delete order articles w/o affecting stock
	        	$oOrderArticles = $this->getOrderArticles( false );
	        	foreach ( $oOrderArticles as $oOrderArticle )
	        	{
	        		// force cancellation flag to avoid stock changes during delete
	        		$oOrderArticle->oxorderarticles__oxstorno->setValue(1);
	            	$oOrderArticle->delete();
	        	}
	        	// reset list so parent function call doesn't attempt to delete them again
	        	$this->setOrderArticleList(oxNew('oxlist'));
	    	}
    	}
    	return parent::delete($sOxId);
    }

    /**
    * Add support for processed orders still pending on payment (such as PayPal eChecks)
    * and make non-pending orders as paid.
    *
    * NOTE: Original declaration states an integer return value but this is not neccessarily
    * true!  If an error occurs, the return value can be an error message (string).
    *
    * @param oxBasket $oBasket              Shopping basket object
    * @param object   $oUser                Current user object
    * @param bool     $blRecalculatingOrder Order recalculation
    *
    * @return integer|string
    */
    public function finalizeOrder( oxBasket $oBasket, $oUser, $blRecalculatingOrder = false )
    {
        $mRet = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
        if ($mRet != self::ORDER_STATE_OK) return $mRet;
        $oGateway = $this->_getGateway();

        // Set status and paid date for order according to status received from merchant gateway
        $sPayStatus = $oGateway->getGatewayPaymentStatus();
        if (isset($sPayStatus))
        {
            if (strcasecmp($sPayStatus, 'Pending') == 0)
                $this->_v6cSaveAsComplete($oGateway->getGatewayOrderId(), true);
            elseif (strcasecmp($sPayStatus, 'Completed') == 0)
                $this->_v6cSaveAsComplete($oGateway->getGatewayOrderId());
            else //TODO: translate
                oxUtilsView::getInstance()->addErrorToDisplay('Merchant gateway reported an unknown payment status.');
        }

        // add gateway parms to userpayment
        $oUserPayment = $this->_v6cGetUserPayment();
        if (isset($oUserPayment))
        {
            $oUserPayment->oxuserpayments__oxvalue = new oxField(serialize($oGateway->getGatewayParms()), oxField::T_RAW);
            $oUserPayment->save();
        }
        //TODO: add ELSE to log failure to save parms

        return $mRet;
    }

    /**
    * Return the same gateway object.  This is required for self::finalizeOrder
    * extension which counts on values being present in oxPaymentGateway object
    * after parent method oxOrder::finalizeOrder has been called.
    *
    * @return object $oPayTransaction payment gateway object
    */
    protected function _getGateway()
    {
        if (!isset($this->_v6c_oPayGateway)) $this->_v6c_oPayGateway = parent::_getGateway();
        return $this->_v6c_oPayGateway;
    }

    /**
    * Save userpayment object to local var.
    *
    * @param string $sPaymentid used payment id
    *
    * @return oxUserPayment $oUserpayment user payment object
    */
    protected function _setPayment( $sPaymentid )
    {
        $ret = parent::_setPayment($sPaymentid);
        if (!isset($this->_v6c_oUsrPay)) $this->_v6c_oUsrPay = $ret;
        return $ret;
    }


	/////////////////////// ADDITIONS ////////////////////////////

    /**
    * Payment gateway.
    * @var oxPaymentGateway
    */
    protected $_v6c_oPayGateway = null;

    /**
    * UserPayment object.
    * @var oxUserPayment
    */
    protected $_v6c_oUsrPay = null;

    /**
     * Updates order for completion. Updating individual fields faster than
     * saving whole record again.
     *
     * @param string $sGatewayOrderId unique gateway order id
     * @param bool $bPending pending flag
     *
     * @return null
     */
    protected function _v6cSaveAsComplete($sGatewayOrderId, $bPending = false)
    {
        $oDb = oxDb::getDb();
        $sDate = date( 'Y-m-d H:i:s', oxUtilsDate::getInstance()->getTime() );
        $sQ = 	'update oxorder set oxtransstatus='.$oDb->quote( ($bPending ? 'PENDING' : 'OK') ).
        		', oxfolder='.$oDb->quote( 'ORDERFOLDER_NEW' ).
        		', oxbillnr='.$oDb->quote( $sGatewayOrderId ).
        		( $bPending ? '' : ', oxpaid='.$oDb->quote( $sDate ) ).
        		' where oxid='.$oDb->quote( $this->getId() );
        $oDb->execute( $sQ );

        //updating order object
        $this->oxorder__oxtransstatus = new oxField( 'OK' );
        $this->oxorder__oxfolder = new oxField( 'ORDERFOLDER_NEW' );
        $this->oxorder__oxbillnr = new oxField( $sGatewayOrderId );
        if (!$bPending) $this->oxorder__oxpaid = new oxField( $sDate );
    }

    /**
    * Load order by merchant gateway transaction ID.  True on success otherwise false.
    *
    * @param string $sIso 3-letter country ISO code
    *
    * @return bool
    */
    public function v6cLoadByMerchantId($sId)
    {
        return $this->assignRecord("select * from oxorder where oxbillnr = '$sId'");
    }

    /**
    * Mark pending orders as paid.
    *
    * @return null
    */
    public function v6cSetAsPaid()
    {
        $oDb = oxDb::getDb();
        $sDate = date( 'Y-m-d H:i:s', oxUtilsDate::getInstance()->getTime() );
        $sQ = 	'update oxorder set oxtransstatus='.$oDb->quote('OK').
    			', oxpaid='.$oDb->quote( $sDate ).
        		' where oxid='.$oDb->quote( $this->getId() );
        $oDb->execute( $sQ );

        //updating order object
        $this->oxorder__oxtransstatus = new oxField( 'OK' );
        $this->oxorder__oxpaid = new oxField( $sDate );
    }

    /**
    * Var getter.
    *
    * @return object $oUserpayment payment object
    */
    protected function _v6cGetUserPayment()
    {
        return $this->_v6c_oUsrPay;
    }
}