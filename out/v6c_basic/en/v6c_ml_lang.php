<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @package   lang
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: cust_lang.php 27030 2010-04-06 07:05:43Z arvydas $
 */

$sLangName  = "English";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(

'charset'										=> 'ISO-8859-15',

'V6C_THKYOU_NOORDER'							=> 'An internal error occurred: order being confirmed could not be found in database.',
'V6C_THKYOU_NOSESSION'							=> '',
'V6C_THKYOU_CONFIRMERR'							=> 'Problem confirming order',
'V6C_THKYOU_CONFIRMFAIL'						=> 'Your payment has been processed by our payment gateway but confirmation of the order has failed.  Our team has been notified of the failure and will investigate immediately.  We will contact you if we cannot process your order.',
'V6C_PAYPAL_NOCONNECT'							=> 'Failed to connect to PayPal server.',
'V6C_PAYPAL_UNKWNRQ'							=> 'Unknown confirmation type.',
'V6C_PAYPAL_MISSINGPARMS'						=> 'Required data was not received from PayPal.',
'V6C_PAYPAL_BADID'								=> 'Seller ID in request does not match seller ID at destination.',
'V6C_PAYPAL_PDTFAIL'							=> 'Failed to retrieve PDT transaction data from PayPal server.',
'V6C_PAYPAL_IPNINVALID'							=> 'Failed to retrieve IPN transaction data from PayPal server.',
'V6C_ORDER_ITEMNAME'							=> 'Order Total (Note: quantity of 1 given by PayPal is normal and does not reflect the quantity of items you ordered)',
'V6C_ORDER_ERRBADSESSION'						=> 'Failed to get basket or user from session.',
'V6C_ORDER_ERRBSKLDFAIL'						=> 'Unable to load basket for user assigned to order.',
'V6C_ORDER_ERRPAYLDFAIL'						=> 'Unable to load payment from order info.',
'V6C_ORDER_ERRPAYMISMATCH'						=> 'Amount confirmed by payment gateway does not match amount for order.',
'V6C_REDIRECT_SAVING'							=> 'Saving your order information and then redirecting to secure payment gateway...',
'V6C_REDIRECT_SUBMITTING'						=> 'If you are not automatically redirected within 10 seconds, please use the &quot;Submit Order&quot; button that will appear below.', // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_EMAIL_ORDER_OWNER_PAYMENTEXTRA'			=> 'Additional Payment Info:',
'V6C_PAGE_CHECKOUT_REDIRECT_TITLE'										=> "Continue To Secure Merchant Gateway",
'V6C_PAGE_CHECKOUT_REDIRECT_SAVING'										=> "Saving your order information and then redirecting to secure payment gateway...",
'V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING'									=> "If you are not automatically redirected within 10 seconds, you can use the button that will appear below to manually enter the secure merchant gateway site.", // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_PAGE_CHECKOUT_REDIRECT_NOJAVA'									    => "Your browser does not support an automatic redirect to the secure merchant gateway.  Please use the button below to manaully continue.",
'V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO'									=> 'Redirecting to secure merchant gateway to collect payment information...',
'V6C_PAGE_CHECKOUT_REDIRECT_ERROR'										=> 'An error was encountered before payment information could be collected',
'V6C_PAGE_CHECKOUT_REDIRECT_ERRORDESC'									=> 'You may try again immediately using the &quot;Continue&quot; button below, otherwise please try again later.  Sorry for the inconvenience.',
'V6C_PAGE_CHECKOUT_REDIRECT_CANCEL'										=> 'Collection of payment information was cancelled',
'V6C_PAGE_CHECKOUT_REDIRECT_CANCELDESC'									=> 'You have returned from the merchant gateway before payment information was collected.  You may use the &quot;Continue&quot; button below to return to the merchant gateway or continue browsing and return to this step later.',
'V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN'									=> 'Continue',
'V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT'								        => 'Error transferring information to the secure merchant gateway, please try again or contact us if the issue persists.',
'V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL'								    => 'Transaction was cancelled by user.',
'V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL'								    => 'Could not determine address (URL) of merchant gateway, please try again or contact us if the issue persists.',
);

/*
[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]
*/
