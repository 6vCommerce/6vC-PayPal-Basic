<?php
/**
 * This class extends the oxPaymentGateway core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 		oxuserpayment => v6c_merchantlink/v6c_mluserpayment
 */
class v6c_mlUserPayment extends v6c_mlUserPayment_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

    // Reported as bug (#0003308) and fixed in 4.5.4 revision 39463
    // As a result this file is no longer needed!

    /**
     * Original method enforces payment type and user as a unique
     * key.  This, however, does not hold for linked payments where
     * the info/value stored varies for each payment, even for the
     * same payment type and user.  Therefore, bypassing parent method
     * only for linked payment types.
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function exists( $sOXID = null )
    {
        if ( isset($this->oxuserpayments__oxpaymentsid->value) )
        {
        	$oPayment = oxNew('oxPayment');
        	if ( $oPayment->load($this->oxuserpayments__oxpaymentsid->value) && $oPayment->v6cIsLinkedGateway() )
				return oxBase::exists($sOXID);
        }

        // Not a linked payment method
        return parent::exists($sOXID);
    }

    /**
     * Adopted code from oxuserpayment::_update indicated.
     * Overriding for same reason as for exists().  If payment
     * is linked type then value will be saved according to ID
     * rather than according to payment type and user.
     *
     * @return bool
     */
    protected function _update()
    {
        if ( isset($this->oxuserpayments__oxpaymentsid->value) )
        {
        	$oPayment = oxNew('oxPayment');
        	if ( $oPayment->load($this->oxuserpayments__oxpaymentsid->value) && $oPayment->v6cIsLinkedGateway() )
        	{
        		// BEGIN ADOPTED CODE
        	    //encode sensitive data
		        if ( $sValue = $this->oxuserpayments__oxvalue->value )
		        {
		            $sEncodedValue = oxDb::getDb()->getOne( "select encode( " . oxDb::getDb()->quote( $sValue ) . ", '" . $this->getPaymentKey() . "' )" );
		            $this->oxuserpayments__oxvalue->setValue($sEncodedValue);
		        }

		        $blRet = oxBase::_update();

	            //restore, as encoding was needed only for saving
		        if ( $sEncodedValue )
		        {
		            $this->oxuserpayments__oxvalue->setValue( $sValue );
		        }

		        return $blRet;
		        // END ADOPTED CODE
        	}
        }

        // Not a linked payment method
        return parent::_update();
    }

    /**
     * Overriding for same reason as for exists().  If payment
     * is linked type then value will be deleted according to ID
     * rather than according to payment type and user.
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null)
    {
        if ( isset($this->oxuserpayments__oxpaymentsid->value) )
        {
        	$oPayment = oxNew('oxPayment');
        	if ( $oPayment->load($this->oxuserpayments__oxpaymentsid->value) && $oPayment->v6cIsLinkedGateway() )
				return oxBase::delete($sOXID);
        }

        // Not a linked payment method
        return parent::delete($sOXID);
    }


	/////////////////////// EXTENSIONS ////////////////////////////


	/////////////////////// ADDITIONS ////////////////////////////

}