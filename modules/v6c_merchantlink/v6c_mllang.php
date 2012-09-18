<?php
/**
* The contents of this file are subject to the Common Public Attribution License
* Version 1.0 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
* http://www.6vcommerce.ca/CPAL.html. The License is based on the
* Mozilla Public License Version 1.1 but Sections 14 and 15 have been added to cover
* use of software over a computer network and provide for limited attribution for
* the Original Developer. In addition, Exhibit A has been modified to be consistent
* with Exhibit B.
*
* Software distributed under the License is distributed on an "AS IS" basis, WITHOUT
* WARRANTY OF ANY KIND, either express or implied. See the License for the specific
* language governing rights and limitations under the License.
*
* The Original Code is 6vCommerce MerchantLink Module.
*
* The Initial Developer of the Original Code is 6vCommerce.
* The Original Developer is the Initial Developer.
*
* All portions of the code written by 6vCommerce are Copyright (C) 6vCommerce.
* All Rights Reserved.
*
* Contributor(s):
*
* @link      http://www.6vcommerce.ca
* @copyright (C) 6vCommerce
*/

/**
 * This class extends the oxLang core class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	oxlang => v6c_merchantlink/v6c_mllang
 */
class v6c_mlLang extends v6c_mlLang_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////

	/**
	 * TODO: Is this still needed?  May not be used anymore since supporting
	 * PayPal NVP instead of PayPal Standard.
	 *
     * Handles special case of returning to site from a merchant gateway in which
     * the lang is set from a custom GET var.
     *
     * @return string
     */
    public function getBaseLanguage()
    {
    	if ( array_key_exists('v6c_gateway', $_GET)
    		 && strcmp(strtolower($_GET['v6c_gateway']), 'paypal') == 0
    		 && array_key_exists('cm', $_GET) )
		{
			$aCustParms = unserialize( stripslashes( htmlspecialchars_decode($_GET['cm']) ) );
			$this->_iBaseLanguageId = $aCustParms['lang'];
		}

        return parent::getBaseLanguage();
    }

	/////////////////////// ADDITIONS ////////////////////////////


}