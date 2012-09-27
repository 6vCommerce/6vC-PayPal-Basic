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
     * TODO: Check if this function is still needed with integrated payments.
     *
     * Loads/re-creates a basket from an order.  If supplied,
     * also loads applicable vouchers.
     *
     * @param oxOrder $oOrder order
     * @param bool $bValidate should basket items be validated (stock, active, etc.)
     *
     * @return null
     */
    public function v6cLoadFromOrder($oOrder, $bValidate = true)
    {
    	// set user
    	$oUser = $oOrder->getOrderUser();
        if ( $oUser == null ) { return; }
        $this->setBasketUser($oUser);

        // set currency
        $this->_oCurrency = oxConfig::getInstance()->getCurrencyObject($oOrder->oxorder__oxcurrency->value);

        /* Setting at least values used by order emails */

//         // set total product costs
//         //TODO: can add individual line totals instead of lump sum w/i set basket content loop that follows below
//         $this->_oProductsPriceList = oxNew( 'oxPriceList' );
//         $oPrice = oxNew( 'oxPrice' );
//         $oPrice->setPrice($oOrder->oxorder__oxtotalbrutsum->value);
//         $this->_oProductsPriceList->addToPriceList($oPrice);

        // set basket contents from order items and set individual properties available from order item data
        // add product price to price list (allows basket total cost to be calc'd)
        $this->_oProductsPriceList = oxNew( 'oxpricelist' );
        $aOrderItems = $oOrder->getOrderArticles();
        foreach ( $aOrderItems as $oItem )
        {
            try
            {
                // If order has not yet been confirmed, validate basket.  This means pending orders can fail if between the time
                // the order was created and the time the order was paid somebody else completed an order which depleted stock
                // pertinent to this order.
                if ($bValidate) $oBasketItem = $this->addToBasket( $oItem->oxorderarticles__oxartid->value, $oItem->oxorderarticles__oxamount->value, $oItem->getOrderArticleSelectList(), $oItem->getPersParams(), true );
                // Cannot use oxBasket->addToBasket if order is completed because validations (such a check stock) could fail since stock
                // has already been deducted.  Use custom fn to just load articles w/o validation since validation already occured when
                // order was created.
                else $oBasketItem = $this->_v6cForceAddToBasket( $oItem->oxorderarticles__oxartid->value, $oItem->oxorderarticles__oxamount->value, $oItem->getOrderArticleSelectList(), $oItem->getPersParams(), true );
		        // Incrementing product count. Would expect this to happen within addToBasket(), but it doesnt!
		        $this->_iProductsCnt++;
		        $this->_dItemsCnt += $oItem->oxorderarticles__oxamount->value;
                // set price
                // Note:
                //    oxprice = full regular price per item
                //    oxbprice = purchase price per item (after shop-wide discounts only because product specific discounts are deducted from cart total)
                //    oxbrutprice = line total
                $oPrice = oxNew( 'oxPrice' );
                $oPrice->setPrice($oItem->oxorderarticles__oxbrutprice->value);
                $oBasketItem->setPrice($oPrice);
                $this->_oProductsPriceList->addToPriceList($oPrice);
                // set wrapping
                $oBasketItem->setWrapping($oItem->oxorderarticles__oxwrapid->value);
                // used by order email templates, but don't know where/how this is set.  Just setting to empty.
                // think it's a deprecated class member, but don't know why it's still used in template.
                $oBasketItem->aDiscounts = array();
            } catch( oxArticleException $oEx ) {
                // caught and ignored, this way code will continue but error will be reported on rendered page
            }
        }

        // set discounts, entire amount bundled into the _aDiscount var, rather than separting into _aItemDiscounts & _aDiscounts
        $oDiscount = new OxStdClass();
        $oDiscount->sDiscount = ''; // unknown
        $oDiscount->sType     = 'abs';
        $oDiscount->dDiscount = $oOrder->oxorder__oxdiscount->value;
        $oDiscount->fDiscount = oxLang::getInstance()->formatCurrency( $oDiscount->dDiscount, $this->getBasketCurrency() );
        $this->_aDiscounts[] = $oDiscount;
        // this must be set or the basket will ignore above settings
        $this->setTotalDiscount($oOrder->oxorder__oxdiscount->value);

        // set voucher discount amount
        $this->_oVoucherDiscount = oxNew( 'oxPrice' );
        $this->_oVoucherDiscount->setPrice($oOrder->oxorder__oxvoucherdiscount->value);
        // set vouchers (get from vouchers table)
        $this->_aVouchers = array();
        $oDb = oxDb::getDb();
        $sSelect  = "select * from oxvouchers where oxorderid = ".$oDb->quote( $oOrder->getId() );
        $oVouchers = oxNew( 'oxlist' );
        $oVouchers->init( 'oxvoucher' );
        $oVouchers->selectstring( $sSelect );
        foreach ( $oVouchers as $oVoucher ) {
            $this->_aVouchers[$oVoucher->getId()] = $oVoucher->getSimpleVoucher();
        }

        // set wrapping cost
        $oPrice = oxNew( 'oxPrice' );
        $oPrice->setPrice($oOrder->oxorder__oxwrapcost->value, $oOrder->oxorder__oxwrapvat->value);
        $this->setCost('oxwrapping', $oPrice);
        // set gift card
        if ( $oOrder->oxorder__oxcardid->value != null )
        {
            $this->setCardId($oOrder->oxorder__oxcardid->value);
            $this->setCardMessage($oOrder->oxorder__oxcardtext->value);
        }

        // set delivery cost
        $oPrice = oxNew( 'oxPrice' );
        $oPrice->setPrice($oOrder->oxorder__oxdelcost->value, $oOrder->oxorder__oxdelvat->value);
        $this->setCost('oxdelivery', $oPrice);

        // set payment cost
        $oPrice = oxNew( 'oxPrice' );
        $oPrice->setPrice($oOrder->oxorder__oxpaycost->value, $oOrder->oxorder__oxpayvat->value);
        $this->setCost('oxpayment', $oPrice);

        // set TS protection cost
        $oPrice = oxNew( 'oxPrice' );
        $oPrice->setPrice($oOrder->oxorder__oxtsprotectcosts->value);
        $this->setCost('oxtsprotection', $oPrice);

        // set total price
        $this->_oPrice = oxNew( 'oxPrice' );
        // Check for orders processed using NA taxation
        if ($oOrder->oxorder__v6globaltax->value)
        {
            $this->_oPrice->v6cEnableTax();
            if (empty($oOrder->oxorder__v6taxes->value))
            {
                // Support old records without V6TAXES field
                $this->_oPrice->setBruttoPriceMode();
                $this->_oPrice-setVat($oOrder->oxorder__oxartvat1->value + $oOrder->oxorder__oxartvat2->value);
                $this->_oPrice->setPrice($oOrder->oxorder__oxtotalordersum->value);
                $aTaxVals = array();
                if ($oOrder->oxorder__oxartvatprice1->value > 0) $aTaxVals['GST/HST'] = $oOrder->oxorder__oxartvatprice1->value;
                if ($oOrder->oxorder__oxartvatprice2->value > 0) $aTaxVals['TVQ'] = $oOrder->oxorder__oxartvatprice1->value;
                $this->_oPrice->v6cSetTaxValues($aTaxVals);
            }
            else
            {
                $this->_oPrice->setVat(unserialize($oOrder->oxorder__v6taxes->rawValue));
                $this->_calcTotalPrice();
            }
        // Not using NA taxation
        } else {
            $this->_oPrice->setBruttoPriceMode();
            $this->_oPrice->setPrice($oOrder->oxorder__oxtotalordersum->value);
            $dTax = oxVatSelector::getInstance()->getUserVat($oUser);
            if ($dTax !== false) $this->_oPrice->setVat($dTax);
        }

        // set order id
        $this->setOrderId($oOrder->getId());
    }

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