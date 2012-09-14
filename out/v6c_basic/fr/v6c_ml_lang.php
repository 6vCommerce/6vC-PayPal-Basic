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

$sLangName  = "Fran�ais";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(

'charset'										=> 'ISO-8859-15',

'V6C_THKYOU_NOORDER'							=> "Une erreur s'est produite � l'interne: la confirmation de votre commande n'a pu �tre retrouv�e dans nos donn�es.",
'V6C_THKYOU_NOSESSION'							=> '',
'V6C_THKYOU_CONFIRMERR'							=> "La commande n'a pas �tre confirm�e",
'V6C_THKYOU_CONFIRMFAIL'						=> "Votre paiement a �t� trait� par notre portail s�curitaire, cependant, la commande n'a pas �tre confirm�e. Nous avons �t� avis� du probl�me et commen�ons imm�diatement une enqu�te. Nous vous contacterons sous peu si votre commande ne peut �tre trait�e.",
'V6C_PAYPAL_NOCONNECT'							=> "La connexion au serveur PayPal a �chou�.",
'V6C_PAYPAL_UNKWNRQ'							=> 'Unknown confirmation type.', //EN
'V6C_PAYPAL_MISSINGPARMS'						=> 'Required data was not received from PayPal.', //EN
'V6C_PAYPAL_BADID'								=> 'Seller ID in request does not match seller ID at destination.', //EN
'V6C_PAYPAL_PDTFAIL'							=> 'Failed to retrieve PDT transaction data from PayPal server.', //EN
'V6C_PAYPAL_IPNINVALID'							=> 'Failed to retrieve IPN transaction data from PayPal server.', //EN
'V6C_ORDER_ITEMNAME'							=> 'Montant total (Note: la quantit� de 1 est normale et ne repr�sente pas les quantit�s dans votre panier)',
'V6C_ORDER_ERRBADSESSION'						=> 'Failed to get basket or user from session.', //EN
'V6C_ORDER_ERRBSKLDFAIL'						=> 'Unable to load basket for user assigned to order.', //EN
'V6C_ORDER_ERRPAYLDFAIL'						=> 'Unable to load payment from order info.', //EN
'V6C_ORDER_ERRPAYMISMATCH'						=> "Le montant confirm� par le portail de paiement ne correspond pas au montant sp�cifi� dans la commande.",
'V6C_REDIRECT_SAVING'							=> "Enregistrement de votre commande et acheminement vers le portail s�curitaire de paiement.",
'V6C_REDIRECT_SUBMITTING'						=> "Si vous n\\'�tes pas redirig� dans les 10 secondes qui suivent, veuillez r�activer le bouton &quot;Soumettre la commande&quot; qui appara�tra ci-bas.",  // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_EMAIL_ORDER_OWNER_PAYMENTEXTRA'			=> 'Additional Payment Info:',
'V6C_PAGE_CHECKOUT_REDIRECT_TITLE'										=> "Acheminement vers le portail s�curitaire de paiement",
'V6C_PAGE_CHECKOUT_REDIRECT_SAVING'										=> "Enregistrement de votre commande et acheminement vers le portail s�curitaire de paiement...",
'V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING'									=> "Si vous n\\'�tes pas redirig� dans les 10 secondes qui suivent, veuillez r�activer le bouton &quot;Soumettre la commande&quot; qui appara�tra ci-bas.", // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_PAGE_CHECKOUT_REDIRECT_NOJAVA'									    => "Votre navigateur ne peut pas se rediriger automatiquement au portail de paiement. Veuillez s'il vous pla�t utiliser le bouton ci-dessous pour continuer.",
'V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO'									=> "Acheminement vers le portail s�curitaire de paiement en cours...",
'V6C_PAGE_CHECKOUT_REDIRECT_ERROR'										=> "Une erreur s'est produite avant que les informations de paiement ne puissent �tre recueillies",
'V6C_PAGE_CHECKOUT_REDIRECT_ERRORDESC'									=> "Vous pouvez essayer de nouveau en utilisant le bouton &quot;Continuer&quot; ci-dessous, ou r�-essayer plus tard. D�sol� de cette inconv�nience.",
'V6C_PAGE_CHECKOUT_REDIRECT_CANCEL'										=> "Le rassemblement des informations de paiement a �t� annul�",
'V6C_PAGE_CHECKOUT_REDIRECT_CANCELDESC'									=> "Vous avez annul� le transfert de vos informations.  Veuillez utiliser le bouton &quot;Continuer&quot; ci-dessous pour retourner au portail ou r�-essayer plus tard.",
'V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN'									=> 'Continuer',
'V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT'								        => "Une erreur s'est produite lors du transfert de vos informations vers le portail de paiement. Veuillez essayer de nouveau ou nous aviser si cette erreur persiste.",
'V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL'								    => "La transaction a �t� annul�e.",
'V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL'								    => "L'adresse (URL) du portail de paiement n'a pu �tre retrouv�e. Veuillez essayer de nouveau ou nous aviser si cette erreur persiste.",
);

/*
[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]
*/
