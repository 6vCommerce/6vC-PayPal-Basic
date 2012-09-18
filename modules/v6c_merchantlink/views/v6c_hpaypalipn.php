<?php

class v6c_hPayPalIpn extends oxUBase
{
	/**
     * Handles PayPal IPN transaction notifications
     *
     * @return  null
     */
    public function render()
    {
		$oPaymentGateway = oxNew( 'oxPaymentGateway' );
		try { $oPaymentGateway->confirmPayment(v6c_mlPaymentGateway::V6C_ML_PAYPAL_IPN); }
		catch (Exception $oEx)
		{
			oxUtils::getInstance()->writeToLog($oEx->getMessage()."\n", 'v6c_log.txt');
			return;
		}

		$this->_confirmOrder($oPaymentGateway);
    }

    /**
     * Confirm order
     *
     * @param oxPaymentGateway $oGateway Payment gateway
     *
     * @return null
     */
	protected function _confirmOrder($oGateway)
	{
    	$aCstParms = $oGateway->getCustomParms();
    	$oOrder = oxNew( 'oxorder' );

    	// Check for orders processed (in New folder) but pending payment
    	if ( strlen($oGateway->getGatewayOrderId()) && $oOrder->v6cLoadByMerchantId($oGateway->getGatewayOrderId()) )
    	{
    	    $oOrder->v6cSetAsPaid();
    	    // Notify admin
    	    $oxEmail = oxNew( 'oxemail' );
    	    $oShop = oxConfig::getInstance()->getActiveShop();
    	    $sMsg = "Order #".$oOrder->oxorder__oxordernr->value." has been paid and it's status changed from 'PENDING' to 'OK'";
    	    $oxEmail->sendEmail($oShop->oxshops__oxowneremail->value, "Order #".$oOrder->oxorder__oxordernr->value." Paid", $sMsg);
    	}

    	// Process incompleted orders (still in Pending folder)
		$oOrder = oxNew( 'oxorder' );
		if ($oOrder->load($aCstParms['v6c_orderid']))
		{
			try { $oOrder->v6cCompleteOrder($oGateway); }
			catch (Exception $oEx)
			{
				oxUtils::getInstance()->writeToLog($oEx->getMessage()."\n", 'v6c_log.txt');
			}
		} else {
			oxUtils::getInstance()->writeToLog(oxLang::getInstance()->translateString('V6C_THKYOU_NOORDER')."\n", 'v6c_log.txt');
		}
	}
}