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
 * Metadata version
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
    'id'	            => 'v6c_merchantlink',
    'title'             => '6vC MerchantLink',
    'description'       => 'Module for interfacing with merchant gateways.',
    'thumbnail'         => 'logo-sqr.png',
    'version'           => '1.1.3',
    'author'            => '6vCommerce',
    'url'		        => 'http://www.6vcommerce.ca',
    'email'				=> 'info@6vcommerce.ca',
    'extend'            => array(
        'order' => 'v6c_merchantlink/v6c_ctrl_mlorder',
        'payment' => 'v6c_merchantlink/v6c_ctrl_mlpayment',
        'thankyou' => 'v6c_merchantlink/v6c_ctrl_mlthankyou',
        'oxbasket' => 'v6c_merchantlink/v6c_mlbasket',
        'oxbasketitem' => 'v6c_merchantlink/v6c_mlbasketitem',
        'oxemail' => 'v6c_merchantlink/v6c_mlemail',
        'oxorder' => 'v6c_merchantlink/v6c_mlorder',
        'oxpayment' => 'v6c_merchantlink/v6c_mlpayment',
        'oxpaymentgateway' => 'v6c_merchantlink/v6c_mlpaymentgateway',
    ),
    'files' => array(
        'v6c_BaseList' => 'v6c_merchantlink/admin/v6c_baselist.php',
    	'v6c_MerchantLink' => 'v6c_merchantlink/admin/v6c_merchantlink.php',
    	'v6c_MerchantLinkList' => 'v6c_merchantlink/admin/v6c_merchantlinklist.php',
    	'v6c_MerchantLinkMain' => 'v6c_merchantlink/admin/v6c_merchantlinkmain.php',
        'v6c_hPayPalIpn'=> 'v6c_merchantlink/views/v6c_hpaypalipn.php',
        'v6c_RedirectPost'=> 'v6c_merchantlink/views/v6c_redirectpost.php',
    ),
    'blocks' => array(
        array('template' => 'page/checkout/thankyou.tpl', 'block'=>'checkout_thankyou_info', 'file'=>'v6c_ml_checkout_thankyou_info.tpl'),
        array('template' => 'widget/sidebar/partners.tpl', 'block'=>'partner_logos', 'file'=>'/out/blocks/paypallogo.tpl'),

    ),
    'templates' => array(
    	'v6c_container.tpl' => 'v6c_merchantlink/out/admin/tpl/v6c_container.tpl',
    	'v6c_merchantlink_list.tpl' => 'v6c_merchantlink/out/admin/tpl/v6c_merchantlink_list.tpl',
    	'v6c_merchantlink_main.tpl' => 'v6c_merchantlink/out/admin/tpl/v6c_merchantlink_main.tpl',
    	'v6c_redirectpost.tpl' => 'v6c_merchantlink/out/azure/tpl/custom/v6c_redirectpost.tpl',
    )
);