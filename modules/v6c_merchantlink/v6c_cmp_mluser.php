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
 * This class extends the oxcmp_user component control class and should be configured in the
 * module extension settings of admin as given below.
 *
 * 	oxcmp_user => v6c_merchantlink/v6c_cmp_mluser
 */
class v6c_cmp_mlUser extends v6c_cmp_mlUser_parent
{
	/////////////////////// OVERRIDES ////////////////////////////

	/////////////////////// EXTENSIONS ////////////////////////////

	/**
     * Calls parent function.  Returns different class for next order
     * step if appropriate config parm is set.
     *
     * @param oxuser $oUser user object
     *
     * @return string
     */
    protected function _afterLogin( $oUser )
    {
    	$sRes = parent::_afterLogin($oUser);

        if (!$this->getConfig()->getConfigParam( 'v6c_blCompactChkOut' ))
    	{
    		return $sRes;
    	}
    	elseif (strcmp($sRes, 'payment') == 0)
    	{
    		return 'v6c_ctrl_options';
    	}
    }

	/**
     * Calls parent function.  Returns different class for next order
     * step if appropriate config parm is set.
     *
     * @see oxcmp_user::_changeUser_noRedirect()
     *
     * @return  mixed    redirection string or true if user is registered, false otherwise
     */
    public function changeUser( )
    {
    	$sRes = parent::changeUser();
        if (!$this->getConfig()->getConfigParam( 'v6c_blCompactChkOut' ))
    	{
    		return $sRes;
    	}
    	elseif (is_string($sRes))
    	{
    		if (strcmp($sRes, 'payment') == 0)
    		{ return 'v6c_ctrl_options'; }
    	}
    }

	/**
     * Calls parent function.  Returns different class for next order
     * step if appropriate config parm is set.
     *
     * Template variables:
     * <b>usr_err</b>
     *
     * Session variables:
     * <b>usr_err</b>, <b>usr</b>
     *
     * @return  mixed    redirection string or true if successful, false otherwise
     */
     public function createUser()
     {
    	$sRes = parent::createUser();
    	if (!$this->getConfig()->getConfigParam( 'v6c_blCompactChkOut' ))
    	{
    		return $sRes;
    	}
    	elseif (is_string($sRes))
    	{
    		if (strcmp($sRes, 'payment') == 0)
    		{ return 'v6c_ctrl_options'; }
    	}
	}

	/////////////////////// ADDITTIONS ////////////////////////////
}