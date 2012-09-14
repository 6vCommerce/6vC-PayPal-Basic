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
 * This class extends the oxBasketItem core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	oxbasketitem => v6c_merchantlink/v6c_mlbasketitem
 *
 * IMPORTANT UPDATE NOTE:
 * Any new occurence of "getSession()->getBasket()" found in newer
 * versions of the oxBasketItem class should be overridden with
 * $this->v6cGetBasket().
 */
class v6c_mlBasketItem extends v6c_mlBasketItem_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

    /**
     * Overriding to make article validation optional.  Mods noted
     * in code.
     *
     * @param string $sProductId product id
     *
     * @throws oxNoArticleException exception
     *
     * @return null
     */
    protected function _setArticle( $sProductId )
    {
        $oConfig = $this->getConfig();
    	// BEGIN MOD: Pulling first argument in method call from new local variable.
        $oArticle = $this->getArticle( $this->_v6c_blSetArticleCheck, $sProductId );
        // END MOD

        // product ID
        $this->_sProductId = $sProductId;

        $this->_sTitle = null;
        $this->_sVarSelect = null;
        $this->getTitle();

        // icon and details URL's
        $this->_sIcon    = $oArticle->oxarticles__oxicon->value;
        $this->_sIconUrl = $oArticle->getIconUrl();
        $this->_blSsl    = $oConfig->isSsl();

        // removing force_sid from the link (incase it'll change)
        $this->_sLink    = oxUtilsUrl::getInstance()->cleanUrl( $oArticle->getLink(), array( 'force_sid' ) );

        // shop Ids
        $this->_sShopId       = $oConfig->getShopId();
        $this->_sNativeShopId = $oArticle->oxarticles__oxshopid->value;

        // SSL/NON SSL image paths
        $this->_sDimageDirNoSsl = $oArticle->nossl_dimagedir;
        $this->_sDimageDirSsl   = $oArticle->ssl_dimagedir;
    }

    /**
     * Overriding to allow basket object to be overridden.  Mods noted
     * in code.
     *
     * IMPORTANT UPDATE NOTE:
     * Any new occurence of "getSession()->getBasket()" found in newer
     * versions of the oxBasketItem class should be overridden with
     * $this->v6cGetBasket().
     *
     * @param double $dAmount    amount
     * @param bool   $blOverride overide current amoutn or not
     * @param string $sItemKey   item key
     *
     * @throws oxOutOfStockException, oxArticleInputException
     *
     * @return null
     */
    public function setAmount( $dAmount, $blOverride = true, $sItemKey = null )
    {
        try {
            //validating amount
            $dAmount = oxInputValidator::getInstance()->validateBasketAmount( $dAmount );
        } catch( oxArticleInputException $oEx ) {
            $oEx->setArticleNr( $this->getProductId() );
            $oEx->setProductId( $this->getProductId() );
            // setting additional information for excp and then rethrowing
            throw $oEx;
        }

        $oArticle = $this->getArticle();


        // setting default
        $iOnStock = true;

        if ( $blOverride ) {
            $this->_dAmount  = $dAmount;
        } else {
            $this->_dAmount += $dAmount;
        }

        // checking for stock
        if ( $this->getStockCheckStatus() == true ) {
        	// BEGIN MOD: Using local instance of oxBasket object.
            $dArtStockAmount = $this->v6cGetBasket()->getArtStockInBasket( $oArticle->getId(), $sItemKey );
            // END MOD
            $iOnStock = $oArticle->checkForStock( $this->_dAmount, $dArtStockAmount );
            if ( $iOnStock !== true ) {
                if ( $iOnStock === false ) {
                    // no stock !
                    $this->_dAmount = 0;
                } else {
                    // limited stock
                    $this->_dAmount = $iOnStock;
                    $blOverride = true;
                }
            }
        }

        // calculating general weight
        $this->_dWeight = $oArticle->oxarticles__oxweight->value * $this->_dAmount;

        if ( $iOnStock !== true ) {
            $oEx = oxNew( 'oxOutOfStockException' );
            $oEx->setMessage( 'EXCEPTION_OUTOFSTOCK_OUTOFSTOCK' );
            $oEx->setArticleNr( $oArticle->oxarticles__oxartnum->value );
            $oEx->setProductId( $oArticle->getProductId() );
            $oEx->setRemainingAmount( $this->_dAmount );
            throw $oEx;
        }
    }

	/////////////////////// EXTENSIONS ////////////////////////////

    //////////////////////// ADOPTED /////////////////////////////

	/////////////////////// ADDITIONS ////////////////////////////

    /**
     * Article get check status (check if article is buyable and visible
     * when _setArticle is called)
     *
     * @var bool
     */
    protected $_v6c_blSetArticleCheck = true;

    /**
     * Set basket that this basket item will be assoicated with.
     *
     * @var oxBasket
     */
    protected $_v6c_oBasket = null;

    /**
     * Set whether new articles should or shouldn't validated
     * (as visible & buyable).
     *
     * @param bool $bCheck check new articles
     *
     * @return null
     */
	public function v6cSetArticleCheck($bCheck)
	{
		$this->_v6c_blSetArticleCheck = $bCheck;
	}

    /**
     * Set basket object to associate this basket item to.
     *
     * @param oxBasket $oBasket basket object
     *
     * @return null
     */
	public function v6cSetBasket($oBasket)
	{
		$this->_v6c_oBasket = $oBasket;
	}

    /**
     * Get basket object associated to this basket item.
     *
     * @return oxBasket
     */
	public function v6cGetBasket()
	{
		if (!isset($this->_v6c_oBasket))
		{
			$this->_v6c_oBasket = $this->getSession()->getBasket();
		}
		return $this->_v6c_oBasket;
	}
}