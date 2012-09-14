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

    	// Check if module has been installed.  If so, disable install button.
    	$oDB = oxDb::getDb();
    	$aInstallSteps = array();

        	// Class extensions installed/updated?
    	if ( !v6cIsModuleClassesSet(oxConfig::getInstance()->getConfigParam('aModules'), $this->_v6c_aClsExt, $this->_v6c_aModuleName) )
    	{
    	    $aInstallSteps[] = 'InstModExt';
    	}

    	// v6link field added?
    	$sQ = "SHOW COLUMNS FROM `oxpayments` LIKE 'V6LINK'";
    	$rs = $oDB->execute($sQ);
    	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddColV6LINK';

        // TPL override blocks installed?
        $sQ = "SELECT * FROM `oxtplblocks` WHERE `OXMODULE` LIKE 'v6c_merchantlink'";
        $rs = $oDB->execute($sQ);
        if ($rs != false && $rs->recordCount() != 1) $aInstallSteps[] = 'InstMlTplBlks';

    	// default payment methods added?
    	$sQ = "select oxid from oxpayments where oxid = 'v6c_paypalstd'";
    	$rs = $oDB->execute($sQ);
    	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddRowsMlDefaults';

    	// PayPal Express installed?
    	$sQ = "SHOW COLUMNS FROM `oxpayments` LIKE 'V6LNKTYP'";
    	$rs = $oDB->execute($sQ);
    	if ($rs != false && $rs->recordCount() == 0) $aInstallSteps[] = 'AddPayPalXpr';


    	// disable button if fully installed
    	if (empty($aInstallSteps))
    	{
	    	if ($this->_aViewData["readonly"] === true)
				$sVal = '';
			else
	    		$sVal = 'disabled';
    	} else {
    		$sVal = '';
    		oxSession::setVar('v6c_aMlInstSteps', $aInstallSteps);
    	}

    	$this->_aViewData["v6c_sMlInstalled"] = $sVal;

    	return $ret;
    }

	/////////////////////// ADDITIONS ////////////////////////////

    /**
    * Sub-folder for this module under 'modules' folder.
    * @var array
    */
    protected $_v6c_aModuleName = 'v6c_merchantlink';

    /**
     * Class extensions for this module.
     * @var array
     */
    protected $_v6c_aClsExt = array(
    	'oxcmp_user' => 'v6c_merchantlink/v6c_cmp_mluser',
        'order' => 'v6c_merchantlink/v6c_ctrl_mlorder',
        'payment' => 'v6c_merchantlink/v6c_ctrl_mlpayment',
        'oxshopcontrol' => 'v6c_merchantlink/v6c_ctrl_mlshopcontrol',
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
    	//TODO: add v6c_googlechkout to insall when supported
    	$oDB = oxDb::getDb();
    	$aInstallSteps = oxSession::getVar('v6c_aMlInstSteps');
    	if (!isset($aInstallSteps)) return;

        	// Install/update module class extensions
    	if (in_array('InstModExt', $aInstallSteps))
    	{
    	    v6cSetModuleClasses($this->_v6c_aModuleName, $this->_v6c_aClsExt, oxConfig::getInstance());
    	}

    	if (in_array('AddColV6LINK', $aInstallSteps)) $oDB->execute("ALTER TABLE `oxpayments` ADD COLUMN `V6LINK` VARCHAR(45) NOT NULL DEFAULT ''");

    	if (in_array('AddRowsMlDefaults', $aInstallSteps)) $oDB->execute("insert into oxpayments (oxid, oxactive, oxdesc, oxaddsum, oxaddsumtype, oxfromboni, oxfromamount, oxtoamount, oxchecked, oxlongdesc, oxsort, v6link)
    			values ('v6c_paypalstd', 0, 'PayPal Standard', 0, 'abs', 0, 0, 1000000, 0, 'Pay by credit or debit card as a guest or pay using an existing PayPal account.', 0, 'v6c_paypalstd')
    			ON DUPLICATE KEY update oxid = oxid");

    	if (in_array('AddPayPalXpr', $aInstallSteps))
    	{
    	    $oDB->execute("ALTER TABLE `oxpayments` ADD COLUMN `V6LNKTYP` INT(1) NOT NULL DEFAULT '0'");
    	    $oDB->execute("insert into oxpayments (oxid, oxactive, oxdesc, oxaddsum, oxaddsumtype, oxfromboni, oxfromamount, oxtoamount, oxchecked, oxlongdesc, oxsort, v6link, v6lnktyp)
    	        			values ('v6c_paypalxpr', 0, 'PayPal Express', 0, 'abs', 0, 0, 1000000, 0, 'Pay by credit or debit card as a guest or pay using an existing PayPal account.', 0, 'v6c_paypalxpr', 1)
    	        			ON DUPLICATE KEY update oxid = oxid");
    	}

    	if (in_array('InstMlTplBlks', $aInstallSteps)) $oDB->execute
    	("
            REPLACE INTO `oxtplblocks` (`OXID`, `OXACTIVE`, `OXSHOPID`, `OXTEMPLATE`, `OXBLOCKNAME`, `OXPOS`, `OXFILE`, `OXMODULE`) VALUES
            ('v6c_ml_checkout_thankyou_info', 1, 'oxbaseshop', 'page/checkout/thankyou.tpl', 'checkout_thankyou_info', 1, 'v6c_ml_checkout_thankyou_info', 'v6c_merchantlink')
        ");
    }
}
