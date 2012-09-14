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
 * @package   modules
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: functions.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * Add custom functions here.
 */

/**
 * Check if class extensions for a module need updating
 *
 * @param array $aShopExt current shop class extensions
 * @param array $aModExt module class extensions
 * @param array|string $aModName module folder or array thereof
 *
 * @return bool
 */
 function v6cIsModuleClassesSet($aShopExt, $aModExt, $aModName)
 {
     $bInstClsExt = true;
     $iCnt = 0;
     if (is_string($aModName)) $aModName = array($aModName);

     foreach ($aShopExt as $sKey => $sModExt)
     {
         foreach ($aModName as $sModName)
         {
             // Check if ext exists but not for this module
             if (strpos($sModExt, $sModName) === false)
             {
                 if ( $bInstClsExt && array_key_exists($sKey, $aModExt) && strpos($aModExt[$sKey], $sModName) !== false )
                 { $bInstClsExt = false; }
             }
             // Ext exists for this module
             else
             {
                 // Check if ext shouldn't exist (e.g. removed in newer version)
                 if ( $bInstClsExt && (!array_key_exists($sKey, $aModExt) || strpos($aModExt[$sKey], $sModName) === false) )
                 { $bInstClsExt = false; }
                 // Track # of ext for this module
                 $iCnt++;
             }
         }
     }
     // Check for non-existing ext
     if ($bInstClsExt && $iCnt != count($aModExt)) $bInstClsExt = false;

     return $bInstClsExt;
 }

 /**
 * Update class extensions for a module
 *
 * @param array|string $aModName module folder or array thereof
 * @param array $aModExt module class extensions
 * @param oxConfig $oConfig shop config object
 *
 * @return null
 */
 function v6cSetModuleClasses($aModName, $aModExt, oxConfig $oConfig)
 {
     $aShopExt = $oConfig->getConfigParam('aModules');
     if (is_string($aModName)) $aModName = array($aModName);

     // Remove any ext no longer used and add to existing
     foreach ($aShopExt as $sKey => $sModExt)
     {
         foreach ($aModName as $sModName)
         {
             if (strpos($sModExt, $sModName) !== false)
             {
                 // remove existing
                 if ( !array_key_exists($sKey, $aModExt) || strpos($aModExt[$sKey], $sModName) === false )
                 {
                     // explode, remove, implode
                     $aExts = explode('&', $sModExt);
                     foreach ($aExts as $key => $sExt) if (strpos($sExt, $sModName) !== false) unset($aExts[$key]);
                     $aShopExt[$sKey] = implode('&', $aExts);
                 }
             } else {
                 // add to existing
                 if ( array_key_exists($sKey, $aModExt) && strpos($aModExt[$sKey], $sModName) !== false )
                 {
                     // explode, add, implode
                     $aExts = explode('&', $sModExt);
                     $aExts[] = $aModExt[$sKey];
                     $aShopExt[$sKey] = implode('&', $aExts);
                 }
             }
         }
     }
     // Add new ext
     foreach ($aModExt as $sKey => $sModExt)
     {
         if (!array_key_exists($sKey, $aShopExt)) $aShopExt[$sKey] = $sModExt;
     }

     // Save changes
     $oConfig->setConfigParam('aModules', $aShopExt);
     $oConfig->saveShopConfVar( 'aarr', 'aModules', $aShopExt );
 }