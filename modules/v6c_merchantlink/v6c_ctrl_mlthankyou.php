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
}