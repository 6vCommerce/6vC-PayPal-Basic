<?php
/*
* Based off of shop.php.  Use that file as a basis for merging diffs for updates.
*/

/**
 * Admin shop manager.
 * Returns template, that arranges two other templates ("v6c_list.tpl"
 * and "v6c_merchantlinkmain.tpl") to frame.
 * Admin Menu: Main Menu -> Core Settings.
 * @package admin
 */
class v6c_MerchantLink extends oxAdminView
{
    /**
     * Executes parent method parent::render() and returns name of template
     * file "shop.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

            $sCurrentAdminShop = oxSession::getVar("currentadminshop");

            if (!$sCurrentAdminShop) {
                if (oxSession::getVar( "malladmin"))
                    $sCurrentAdminShop = "oxbaseshop";
                else
                    $sCurrentAdminShop = oxSession::getVar( "actshop");
            }

            $this->_aViewData["currentadminshop"] = $sCurrentAdminShop;
            oxSession::setVar("currentadminshop", $sCurrentAdminShop);


        return "v6c_container.tpl";
    }
}
