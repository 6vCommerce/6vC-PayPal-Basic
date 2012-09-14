<?php
/**
*    This file is part of the 6vCommerce MerchantLink Module Support Package.
*
*    The 6vCommerce MerchantLink Module Support Package is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    The 6vCommerce MerchantLink Module Support Package is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with the 6vCommerce MerchantLink Module Support Package.  If not, see <http://www.gnu.org/licenses/>.
*
* @link      http://www.6vcommerce.ca
* @copyright (C) 6vCommerce
*/

/**
 * This class extends the Thankyou controller class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	thankyou => v6c_merchantlink/v6c_ctrl_mlthankyou
 */
class v6c_ctrl_mlThankYou extends v6c_ctrl_mlThankYou_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

    /**
     * NOTE: Probably obsolete if payments linked to order page are
     * discontinued.
     *
     * Overrides only if 1st 'if' condition is passed.  Mostly original
     * code so adopted code is noted for the sake of udpates.
     *
     * Executes parent::init(), loads basket from session
     * (thankyou::_oBasket = oxsession::getBasket()) then destroys
     * it (oxsession::delBasket()), unsets user session ID, if
     * this user didn't entered password while ordering.
     *
     * @return null
     */
    public function init()
    {
    	// handle init differently for internal (coming from previous step) vs external
    	// (returning from a payment gateway) request
    	if (!array_key_exists('v6c_gateway', $_GET)) parent::init();
    	else
    	{
    		// external request (customer returning from linked gateway)
    		oxUBase::init();

    		// if possible, check if order has already been processed
    		$bProcessOrder = true;
    		if (array_key_exists('cm', $_GET))
    		{
    		    $aCustParms = unserialize( stripslashes( htmlspecialchars_decode($_GET['cm']) ) );
    		    $oOrder = oxNew( 'oxOrder' );
    		    if ($oOrder->load($aCustParms['v6c_orderid']))
    		    {
    		        if (strcmp($oOrder->oxorder__oxfolder->value, 'ORDERFOLDER_NEW')  == 0)
    		        {
    		            $bProcessOrder = false;
    		            $this->_oOrder = $oOrder;
    		            $oBasket = oxNew( 'oxBasket' );
    		            $oBasket->v6cLoadFromOrder($oOrder, false);
    		            $this->_oBasket = $oBasket;
    		        }
    		    } else {
			        $this->_v6c_sCnfrmError = oxLang::getInstance()->translateString('V6C_THKYOU_NOORDER');
		        }
    		}

    		if ($bProcessOrder)
    		{
    		    // process gateway confirmation
    		    // Payment must be confirmed immediately in order to retrieve variables required
    		    // for backend processing
    		    $oPaymentGateway = oxNew( 'oxPaymentGateway' );
    		    try { $oPaymentGateway->confirmPayment(v6c_mlPaymentGateway::V6C_ML_PAYPAL_PDT);
    		    }
    		    catch (Exception $oEx)
    		    {
    		        //oxUtilsView::getInstance()->addErrorToDisplay( $oEx );
    		        $this->_v6c_sCnfrmError = $oEx->getMessage();
    		    }

    		    // mark order as confirmed, if already confirmed by gateway server (race condition?)
    		    // will not re-process but will still return basket associated with order.
    		    if ($this->_v6c_sCnfrmError == null) $this->_v6cConfirmOrder($oPaymentGateway);
    		}

	    	// update session:

	    	// clean-up session as done in oxorder::finalizeOrder and thankyou::init
	        oxSession::deleteVar( 'ordrem' );
	        oxSession::deleteVar( 'stsprotection' );

	        // BEGIN ADOPTED CODE
	        /*
	         * Delete basket in session, however, unlike original code a basket created from order
	         * data may already be available from _v6cConfirmOrder (already called at this point).
	         * If this other basket is already present in $_oBasket, then the session basket is not
	         * assigned.
	         */
	        $oBasket = $this->getSession()->getBasket();
	        // basket needed to render thankyou page, so if basket from order isn't available use session
	        if ($this->_oBasket == null) $this->_oBasket = clone $oBasket;
	        $oBasket->deleteBasket();
	        oxSession::deleteVar( 'sess_challenge' );  // never actually used in this case
	        // END ADOPTED CODE
    	}
    }

    /**
     * Only overrides if first 'if' condition is passed.  Adopted code
     * applicable to updates is noted.
     *
     * Allows thankyou page to continue rendering if there was a gateway
     * confirmation error.
     *
     * @return  string  current template file name
     */
    public function render()
    {
    	if ($this->_v6c_sCnfrmError == null)
    	{
    		// continue as normal
    		return parent::render();
    	}
    	else
    	{
    	    // Do not want any errors to show to user.
    	    $aErrors = oxSession::getVar( 'Errors' );
    	    oxSession::deleteVar('Errors');

    		oxUBase::render();

    		// BEGIN ADOPTED CODE
    	    $oUser = $this->getUser();

	        if ( !$oUser || !$oUser->oxuser__oxpassword->value)
	        {
                oxSession::deleteVar( 'usr' );
                oxSession::deleteVar( 'dynvalue' );
            }
	        // END ADOPTED CODE

            // prep error details
            $sErr = "[".date('Y-m-d\TH:i:sP')."]\n";
            if ( count( $aErrors ) > 0 ) {
                foreach ( $aErrors as $sLocation => $aEx2 ) {
                    foreach ( $aEx2 as $sKey => $oEr )
                    {
                        $oError = unserialize( $oEr );
                        $sErr .= "ERROR[$sLocation-$sKey]: " . $oError->getOxMessage() . "\n";
                    }
                }
            }
            $sErr .= $this->_v6c_sCnfrmError."\n";
            $sErr .= print_r($_REQUEST, true)."\n";
            $sErr .= "\n";
	    	// notify admin of error
    		$oxEmail = oxNew( 'oxemail' );
    		$oShop = oxConfig::getInstance()->getActiveShop();
    		if ( !$oxEmail->sendEmail($oShop->oxshops__oxowneremail->value, "Order Confirmation Failed", $sErr) )
    		{
    			// resort to log file...
    			oxUtils::getInstance()->writeToLog($sErr, 'v6c_log.txt');
    		}

    		// Flag for display of user-friendly msg
    		$this->_aViewData['v6c_bCnfrmError'] = true;

    		// BEGIN ADOPTED CODE
	        // we must set active class as start
	        $this->getViewConfig()->setViewConfigParam( 'cl', 'start' );

	        return $this->_sThisTemplate;
	        // END ADOPTED CODE
    	}
    }


    /////////////////////// EXTENSIONS ////////////////////////////


	/////////////////////// ADDITIONS ////////////////////////////

    /**
     * Gateway confirmation error (already translated accordingly)
     * @var string
     */
    protected $_v6c_sCnfrmError = null;

    /**
     * Confirm order
     *
     * @param oxPaymentGateway $oGateway Payment gateway
     *
     * @return null
     */
	protected function _v6cConfirmOrder($oGateway)
	{
    	$aCstParms = $oGateway->getCustomParms();

		$oOrder = oxNew( 'oxorder' );
		if ($oOrder->load($aCstParms['v6c_orderid']))
		{
		    $this->_oOrder = $oOrder;
    	    /*
    	     * Note that session basket is NOT used to confirm order. Can't use session
    	     * basket to confirm because there is no gaurantee that is hasn't changed
    	     * (in another tab say) since the user was redirected to the merchant site.
    	     */
			try { $this->_oBasket = $oOrder->v6cCompleteOrder($oGateway); }
			catch (Exception $oEx)
			{
				$this->_v6c_sCnfrmError = $oEx->getMessage();
			}
		} else {
			$this->_v6c_sCnfrmError = oxLang::getInstance()->translateString('V6C_THKYOU_NOORDER');
		}
	}
}