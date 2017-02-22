<?php

/**
 * Admin shop system setting manager.
 * Collects shop system settings, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> System.
 * @package admin
 */
class v6c_MerchantLinkMain extends Shop_Config
{
	/////////////////////// OVERRIDES ////////////////////////////
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'v6c_merchantlink_main.tpl';

	/////////////////////// EXTENSIONS ////////////////////////////

    /**
     * Add check for install of module.
     *
     * @return string
     */
    public function render()
    {
    	$ret = parent::render();

    	$sVersion = $this->getConfig()->getActiveShop()->oxshops__oxversion->value;

    	// Check if module has been installed.  If so, disable install button.
    	$oDB = oxDb::getDb();
    	$aInstallSteps = array();

    	// v6link field added?
    	$sQ = "SHOW COLUMNS FROM `oxpayments` LIKE 'V6LINK'";
    	$rs = $oDB->execute($sQ);
    	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddColV6LINK';

    	// default payment methods added?
    	$sQ = "select oxid from oxpayments where oxid = 'v6c_paypalxpr'";
    	$rs = $oDB->execute($sQ);
    	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddRowsMlDefaults';

    	// Support for 4.5 until deemed uneccessary
        if ( strpos($sVersion, '4.5') === 0 ) $this->_v6cInstallCheck4_5($aInstallSteps);

    	// disable button if fully installed
    	if (empty($aInstallSteps))
    	{
	    	if ($this->_aViewData["readonly"] === true)
				$sVal = '';
			else
	    		$sVal = 'disabled';
    	} else {
    		$sVal = '';
            oxRegistry::getSession()->setVariable('v6c_aMlInstSteps', $aInstallSteps);
    	}

    	$this->_aViewData["v6c_sMlInstalled"] = $sVal;

    	return $ret;
    }

	/////////////////////// ADDITIONS ////////////////////////////

    /**
    * Support for 4.5 until deemed uneccessary
    * Sub-folder for this module under 'modules' folder.
    * @var array
    * @deprecated
    */
    protected $_v6c_aModuleName = 'v6c_merchantlink';

    /**
     * Support for 4.5 until deemed uneccessary
     * Class extensions for this module.
     * @var array
     * @deprecated
     */
    protected $_v6c_aClsExt = array(
        'order' => 'v6c_merchantlink/v6c_ctrl_mlorder',
        'payment' => 'v6c_merchantlink/v6c_ctrl_mlpayment',
        'thankyou' => 'v6c_merchantlink/v6c_ctrl_mlthankyou',
        'oxbasket' => 'v6c_merchantlink/v6c_mlbasket',
        'oxbasketitem' => 'v6c_merchantlink/v6c_mlbasketitem',
        'oxemail' => 'v6c_merchantlink/v6c_mlemail',
        'oxlang' => 'v6c_merchantlink/v6c_mllang',
        'oxorder' => 'v6c_merchantlink/v6c_mlorder',
        'oxpayment' => 'v6c_merchantlink/v6c_mlpayment',
        'oxpaymentgateway' => 'v6c_merchantlink/v6c_mlpaymentgateway',
    );

    public function v6cInstallML()
    {
    	$oDB = oxDb::getDb();
    	$sVersion = $this->getConfig()->getActiveShop()->oxshops__oxversion->value;
    	$aInstallSteps = oxRegistry::getSession()->getVariable('v6c_aMlInstSteps');
    	if (!isset($aInstallSteps)) return;

    	if (in_array('AddColV6LINK', $aInstallSteps)) $oDB->execute("ALTER TABLE `oxpayments` ADD COLUMN `V6LINK` VARCHAR(45) NOT NULL DEFAULT ''");

    	if (in_array('AddRowsMlDefaults', $aInstallSteps)) $oDB->execute("insert into oxpayments (oxid, oxactive, oxdesc, oxaddsum, oxaddsumtype, oxfromboni, oxfromamount, oxtoamount, oxchecked, oxlongdesc, oxsort, v6link)
    			values ('v6c_paypalxpr', 0, 'Credit Card or PayPal', 0, 'abs', 0, 0, 1000000, 0, 'Pay with any major credit card or use a PayPal account.', 0, 'v6c_paypalxpr')
    			ON DUPLICATE KEY update oxid = oxid");

    	// Support for 4.5 until deemed uneccessary
    	if ( strpos($sVersion, '4.5') === 0 ) $this->_v6cInstallExec4_5($aInstallSteps);
    }

    /**
    * Support for 4.5 version of shop.
    * @var array
    * @deprecated
    */
    private function _v6cInstallCheck4_5(&$aInstStps)
    {
        $oDB = oxDb::getDb();

        // Class extensions installed/updated?
    	if ( !v6cIsModuleClassesSet(oxRegistry::getConfig()->getConfigParam('aModules'), $this->_v6c_aClsExt, $this->_v6c_aModuleName) )
    	{
    	    $aInstStps[] = 'InstModExt';
    	}

        // TPL override blocks installed?
        $sQ = "SELECT * FROM `oxtplblocks` WHERE `OXMODULE` LIKE 'v6c_merchantlink'";
        $rs = $oDB->execute($sQ);
        if ($rs != false && $rs->recordCount() != 1) $aInstStps[] = 'InstMlTplBlks';

//     	// PayPal Express installed?
//     	$sQ = "SHOW COLUMNS FROM `oxpayments` LIKE 'V6LNKTYP'";
//     	$rs = $oDB->execute($sQ);
//     	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddPayPalXpr';
    }

    /**
    * Support for 4.5 version of shop.
    * @var array
    * @deprecated
    */
    private function _v6cInstallExec4_5($aInstallSteps)
    {
        $oDB = oxDb::getDb();

        // Install/update module class extensions
    	if (in_array('InstModExt', $aInstallSteps))
    	{
    	    v6cSetModuleClasses($this->_v6c_aModuleName, $this->_v6c_aClsExt, oxRegistry::getConfig());
    	}

//     	if (in_array('AddPayPalXpr', $aInstallSteps))
//     	{
//     	    $oDB->execute("ALTER TABLE `oxpayments` ADD COLUMN `V6LNKTYP` INT(1) NOT NULL DEFAULT '0'");
//     	    $oDB->execute("insert into oxpayments (oxid, oxactive, oxdesc, oxaddsum, oxaddsumtype, oxfromboni, oxfromamount, oxtoamount, oxchecked, oxlongdesc, oxsort, v6link, v6lnktyp)
//     	        			values ('v6c_paypalxpr', 0, 'PayPal Express', 0, 'abs', 0, 0, 1000000, 0, 'Pay by credit or debit card as a guest or pay using an existing PayPal account.', 0, 'v6c_paypalxpr', 1)
//     	        			ON DUPLICATE KEY update oxid = oxid");
//     	}

    	if (in_array('InstMlTplBlks', $aInstallSteps)) $oDB->execute
    	("
            REPLACE INTO `oxtplblocks` (`OXID`, `OXACTIVE`, `OXSHOPID`, `OXTEMPLATE`, `OXBLOCKNAME`, `OXPOS`, `OXFILE`, `OXMODULE`) VALUES
            ('v6c_ml_checkout_thankyou_info', 1, 'oxbaseshop', 'page/checkout/thankyou.tpl', 'checkout_thankyou_info', 1, 'v6c_ml_checkout_thankyou_info', 'v6c_merchantlink')
        ");
    }
}
