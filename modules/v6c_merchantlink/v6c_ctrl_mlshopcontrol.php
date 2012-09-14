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
 * This class extends the main controller class oxShopControl and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	oxshopcontrol => v6c_merchantlink/v6c_ctrl_mlshopcontrol
 */
class v6c_ctrl_mlShopControl extends v6c_ctrl_mlShopControl_parent
{
	/////////////////////// OVERRIDES ////////////////////////////


	/////////////////////// EXTENSIONS ////////////////////////////

	/**
	 * TODO: May not need this anymore for integrated payment gateways.
	 *
     * For PayPal.  Use of PayPal requires access to both GET and
     * POST variables when a POST is given.  OXID function
     * oxConfig::getParameter does not allow this so this extension
     * allows for some exceptions where GET variables can be used
     * to define 'cl' and 'fnc' even when a POST is received.  Without
     * this extension, the 'cl' GET variable is never retrieved and
     * the request if sent to cl=start by default.
     *
     * NOTE: Ideally this would be changed in oxConfig::getParameter
     * but since oxConfig is not extendable doing it here.
     * Furthermore, changing how 'cl' and 'fnc' is set within
     * oxShopControl::start would be better but using _process instead
     * to avoid a complete override of oxShopControl::start.
     *
     * @param string $sClass    Name of class
     * @param string $sFunction Name of function
     *
     * @return null
     */
    protected function _process( $sClass, $sFunction )
    {
    	// Trying to minimize overhead if special case does not apply.
		if (array_key_exists('v6c_gateway', $_GET))
		{
			if (	strcmp(strtolower($_GET['v6c_gateway']), 'paypal') == 0 &&
					strcmp($sClass, 'start') == 0 &&
					strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0)
			{
				if (array_key_exists('cl',$_GET))
				{
					$sClass = $_GET['cl'];
				}
				if (array_key_exists('fnc',$_GET)) $sFunction = $_GET['fnc'];
			}
		}

    	parent::_process($sClass, $sFunction);
    }

	/////////////////////// ADDITIONS ////////////////////////////


}