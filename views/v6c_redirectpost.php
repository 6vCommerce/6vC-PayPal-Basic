<?php

class v6c_RedirectPost extends oxUBase
{
    /////////////////////// ADOPTED /////////////////////////////

    /**
    * Copied from oxOrder::getPayment, update accordingly!
    * Template variable getter. Returns payment object
    *
    * @return object
    */
    public function getPayment()
    {
        if ( $this->_oPayment === null ) {
            $this->_oPayment = false;

            $oBasket = $this->getBasket();
            $oUser = $this->getUser();

            // payment is set ?
            $sPaymentid = $oBasket->getPaymentId();
            $oPayment   = oxNew( 'oxpayment' );

            if ( $sPaymentid && $oPayment->load( $sPaymentid ) &&
                $oPayment->isValidPayment( oxSession::getVar( 'dynvalue' ),
                                           $this->getConfig()->getShopId(),
                                           $oUser,
                                           $oBasket->getPriceForPayment(),
                                           oxSession::getVar( 'sShipSet' ) ) ) {
                $this->_oPayment = $oPayment;
            }
        }
        return $this->_oPayment;
    }

    /**
     * Copied from oxOrder::getBasket, update accordingly!
     * Template variable getter. Returns active basket
     *
     * @return object
     */
    public function getBasket()
    {
        if ( $this->_oBasket === null ) {
            $this->_oBasket = false;
            if ( $oBasket = $this->getSession()->getBasket() ) {
                $this->_oBasket = $oBasket;
            }
        }
        return $this->_oBasket;
    }

    /**
    * AGB 'if' condition adopted from oxOrder::execute, update accordingly!
    * Verify that all order requirements are met.  If not, return user to order page.
    *
    * @return string
    */
    public function execute()
    {
        $myConfig = $this->getConfig();

        // Check for agreement to terms and conditions, if applicable
        if ( !oxConfig::getParameter( 'ord_agb' ) && $myConfig->getConfigParam( 'blConfirmAGB' ) ) {
            //oxUtils::getInstance()->redirect($this->getConfig()->getShopHomeURL().'cl=order&fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken'));
            if ($this->getConfig()->getConfigParam( 'v6c_blCompactChkOut' ))
                return 'v6c_ctrl_Options?fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken');
            else
                return 'order?fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken');
        }
    }

    /**
    * AGB 'if' condition adopted from oxOrder::execute, update accordingly!
    * Forward order information to appropriate external URL
    *
    * @return  string
    */
    public function render()
    {
        $myConfig = $this->getConfig();

        // Check for agreement to terms and conditions, if applicable
        if (!$this->v6cIsIntegratedLink() && !oxConfig::getParameter( 'ord_agb' ) && $myConfig->getConfigParam( 'blConfirmAGB' ) ) {
            //oxUtils::getInstance()->redirect($this->getConfig()->getShopHomeURL().'cl=order&fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken'));
            if ($this->getConfig()->getConfigParam( 'v6c_blCompactChkOut' ))
            {
                // Do nothing, checked by v6c_ctrl_Options.
            }
            else
            {
                oxUtils::getInstance()->redirect($this->getConfig()->getShopHomeURL().'cl=order&fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken'));
                return; //'order?fnc=execute&ord_agb=0&stoken='.oxConfig::getParameter('stoken');
            }
        }

        parent::render();
        $oPayment = $this->getPayment();
        $oPaymentGateway = oxNew( 'oxPaymentGateway' );

        // Continue according to linked merchant gateway type
        if ($this->v6cIsIntegratedLink())
        {
            // Init integrated transaction
            $oBasket = $this->getBasket();
            if ($oPaymentGateway->v6cInitPayment($oPayment,$oBasket) === false)
            {
                $this->_v6cSetError(oxLang::getInstance()->translateString('V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT'));
            }
            else $aGatewayParms = $oPaymentGateway->getGatewayParms();
        } else {
            // Save order info
            $oOrder = oxNew( 'oxorder' );
            $oOrder->v6cSaveOrder();
            $this->_aViewData['aPostData'] = $oPayment->v6cGetGatewayParms(array('v6c_orderid' => $oOrder->getId()));
        }

        // Make sure post URL is available
        $sPostUrl = $oPaymentGateway->v6cGetGatewayUrl($oPayment);
        if ($sPostUrl === false) $this->_v6cSetError(oxLang::getInstance()->translateString('V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL'));
        else $this->_v6c_sPostUrl = $sPostUrl;

        // Don't show mini baskets
        $this->setShowTopBasket(false);
        $this->setShowRightBasket(false);

        return 'page/checkout/v6c_redirectpost.tpl';
    }


    /////////////////////// ADDITIONS ////////////////////////////

    /**
     * Active basket
     * @var object
     */
    protected $_oBasket = null;

    /**
     * Payment object
     * @var object
     */
    protected $_oPayment = null;

    /**
     * Test mode
     * @var bool
     */
    protected $_v6c_bTestMode = null;

    /**
    * Using integrated merchant gateway
    * @var bool
    */
    protected $_v6c_bIntegratedLink = null;

    /**
    * Post URL
    * @var string
    */
    protected $_v6c_sPostUrl = null;

    /**
    * Auto-post flag
    * @var bool
    */
    protected $_v6c_bAutoPost = true;

    /**
    * Error flag
    * @var bool
    */
    protected $_v6c_bErr = false;

    /**
    * Flags that user cancelled transaction at merchant gateway site
    * @var bool
    */
    protected $_v6c_bCancel = false;

    /**
     * If available, return URL string of merchant link, else
     * returns false.
     *
     * @return mixed
     */
	public function getGatewayUrl()
	{
		return $this->_v6c_sPostUrl;
	}

	/**
	 * Returns TRUE if selected payment method is an integrated linked
	 * gateway for entering payment info only (returns to checkout).
	 * Otherwise returns FALSE.
	 *
	 * @return bool
	 */
	public function v6cIsIntegratedLink()
	{
	    if (!isset($this->_v6c_bIntegratedLink))
	    {
    	    $oPayment = $this->getPayment();
    	    if ( $oPayment === false ) {
    	        $this->_v6c_bIntegratedLink = false;
    	    }
    	    else $this->_v6c_bIntegratedLink = $oPayment->v6cIsLinkedGateway() && $oPayment->v6cGetGatewayLinkType() == 1;
	    }
	    return $this->_v6c_bIntegratedLink;
	}

	/**
	* Check whether or not to auto-post form.
	*
	* @return bool
	*/
	public function v6cIsAutoPost()
	{
	    return $this->_v6c_bAutoPost;
	}

	/**
	* Check whether or not an error or cancellation occurred.
	*
	* @return bool
	*/
	public function v6cIsErr()
	{
	    return $this->_v6c_bErr;
	}

	/**
	* Check whether or not user cancelled processing at merchant gateway.
	*
	* @return bool
	*/
	public function v6cIsCancel()
	{
	    return $this->_v6c_bCancel;
	}

	/**
	 * Flags cancelation of payment by user.
	 *
	 * @return null
	 */
	public function v6cLinkedPayCancel()
	{
	    $this->_v6c_bAutoPost = false;
	    $this->_v6c_bCancel = true;
	    oxUtilsView::getInstance()->addErrorToDisplay( oxLang::getInstance()->translateString('V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL') );
	}

	/**
	* Set an error.
	*
	* @param string $sErrMsg
	*
	* @return null
	*/
	protected function _v6cSetError($sErrMsg)
	{
	    $this->_v6c_bAutoPost = false;
	    $this->_v6c_bErr = true;
	    oxUtilsView::getInstance()->addErrorToDisplay( $sErrMsgd );
	}
}