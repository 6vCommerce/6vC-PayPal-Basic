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
 * This class extends the oxBasket core class and should be configured in the
 * module extension settings of admin as given below.  If any errors occur after
 * adding this module try clearing your browsers cache and cookies.
 *
 * 	oxbasket => v6c_merchantlink/v6c_mlbasket
 */
class v6c_mlBasket extends v6c_mlBasket_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

    /**
     * Overriding to support referencing oxBaketItem object to proper basket object.
     * Mods noted in code.
     *
     * @param string $sProductID       id of product
     * @param double $dAmount          product amount
     * @param array  $aSel             product select lists (default null)
     * @param array  $aPersParam       product persistent parameters (default null)
     * @param bool   $blOverride       marker to acumulate passed amount or renew (default false)
     * @param bool   $blBundle         marker if product is bundle or not (default false)
     * @param string $sOldBasketItemId id if old basket item if to change it
     *
     * @throws oxOutOfStockException oxArticleInputException, oxNoArticleException
     *
     * @return object
     */
    public function addToBasket( $sProductID, $dAmount, $aSel = null, $aPersParam = null, $blOverride = false, $blBundle = false, $sOldBasketItemId = null )
    {
        // enabled ?
        if ( !$this->isEnabled() )
            return null;

        // basket exclude
        if ( $this->getConfig()->getConfigParam( 'blBasketExcludeEnabled' ) ) {
            if ( !$this->canAddProductToBasket( $sProductID ) ) {
                $this->setCatChangeWarningState( true );
                return null;
            } else {
                $this->setCatChangeWarningState( false );
            }
        }

        $sItemId = $this->getItemKey( $sProductID, $aSel, $aPersParam, $blBundle );
        if ( $sOldBasketItemId && ( strcmp( $sOldBasketItemId, $sItemId ) != 0 ) ) {
            if ( isset( $this->_aBasketContents[$sItemId] ) ) {
                // we are merging, so params will just go to the new key
                unset( $this->_aBasketContents[$sOldBasketItemId] );
                // do not override stock
                $blOverride = false;
            } else {
                // value is null - means isset will fail and real values will be filled
                $this->_changeBasketItemKey( $sOldBasketItemId, $sItemId );
            }
        }

        // after some checks item must be removed from basket
        $blRemoveItem = false;

        // initialting exception storage
        $oEx = null;

        if ( isset( $this->_aBasketContents[$sItemId] ) ) {

            //updating existing
            try {
                // setting stock check status
                $this->_aBasketContents[$sItemId]->setStockCheckStatus( $this->getStockCheckMode() );
                //validate amount
                //possibly throws exception
                $this->_aBasketContents[$sItemId]->setAmount( $dAmount, $blOverride, $sItemId );
            } catch( oxOutOfStockException $oEx ) {
                // rethrow later
            }

        } else {
            //inserting new
            $oBasketItem = oxNew( 'oxbasketitem' );
            try {
            	// BEGIN MOD: Adding new line to set proper basket object
            	$oBasketItem->v6cSetBasket($this);
            	// END MOD
                $oBasketItem->setStockCheckStatus( $this->getStockCheckMode() );
                $oBasketItem->init( $sProductID, $dAmount, $aSel, $aPersParam, $blBundle );
            } catch( oxNoArticleException $oEx ) {
                // in this case that the article does not exist remove the item from the basket by setting its amount to 0
                //$oBasketItem->dAmount = 0;
                $blRemoveItem = true;

            } catch( oxOutOfStockException $oEx ) {
                // rethrow later
            } catch ( oxArticleInputException $oEx ) {
                // rethrow later
                $blRemoveItem = true;
            }

            $this->_aBasketContents[$sItemId] = $oBasketItem;
        }

        //in case amount is 0 removing item
        if ( $this->_aBasketContents[$sItemId]->getAmount() == 0 || $blRemoveItem ) {
            $this->removeItem( $sItemId );
        } elseif ( $blBundle ) {
            //marking bundles
            $this->_aBasketContents[$sItemId]->setBundle( true );
        }

        //calling update method
        $this->onUpdate();

        if ( $oEx ) {
            throw $oEx;
        }

        // notifying that new basket item was added
        $this->_addedNewItem( $sProductID, $dAmount, $aSel, $aPersParam, $blOverride, $blBundle, $sOldBasketItemId );

        // returning basket item object
        return $this->_aBasketContents[$sItemId];
    }


    //////////////////////// ADOPTED /////////////////////////////

    /**
    * Code adopted from oxBasket::addToBasket.
    *
    * For adding order items from a pending order to a basket.  Since order is already pending
    * no validation occurs within this function, unlike oxBasket::addToBasket, and items are
    * gauranteed to be added.
    *
    * @param string $sProductID       id of product
    * @param double $dAmount          product amount
    * @param array  $aSel             product select lists (default null)
    * @param array  $aPersParam       product persistent parameters (default null)
    * @param bool   $blOverride       marker to acumulate passed amount or renew (default false)
    * @param bool   $blBundle         marker if product is bundle or not (default false)
    * @param string $sOldBasketItemId id if old basket item if to change it
    *
    * @return oxBasketItem
    */
    private function _v6cForceAddToBasket( $sProductID, $dAmount, $aSel = null, $aPersParam = null, $blOverride = false, $blBundle = false, $sOldBasketItemId = null )
    {
        // Generate oxBasketItem key
        $sItemId = $this->getItemKey( $sProductID, $aSel, $aPersParam, $blBundle );
        // Create new oxBasketItem
        $oBasketItem = oxNew( 'oxbasketitem' );
        $oBasketItem->v6cSetBasket($this);
        $oBasketItem->setStockCheckStatus( false );
        $oBasketItem->v6cSetArticleCheck(false);
        try {
            $oBasketItem->init( $sProductID, $dAmount, $aSel, $aPersParam, $blBundle );
        } catch( oxNoArticleException $oEx ) {
            throw $oEx;
        } catch( oxOutOfStockException $oEx ) {
            throw $oEx;
        } catch ( oxArticleInputException $oEx ) {
            throw $oEx;
        }
        // Add to basket
        $this->_aBasketContents[$sItemId] = $oBasketItem;
        // Mark bundles
        if ( $blBundle ) $this->_aBasketContents[$sItemId]->setBundle( true );
        // Flag basket for update/recalc
        $this->onUpdate();

        return $this->_aBasketContents[$sItemId];
    }


    /////////////////////// EXTENSIONS ////////////////////////////


	/////////////////////// ADDITIONS ////////////////////////////

    /**
     * If payment is known, returns a bool indicating whether or not
     * the payment type is linked.  Otherwise returns null.
     *
     * @return mixed
     */
    public function v6cIsPaymentLinked()
    {
    	$sPayId = $this->getPaymentId();
    	if (isset($sPayId))
    	{
    		$oPayment = oxNew('oxPayment');
    		if ($oPayment->load($sPayId));
    		{
    			return $oPayment->v6cIsLinkedGateway();
    		}
    	}
    }
}