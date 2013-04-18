[{if $oView->getClassName() eq "start"}]
<div class="box">
    <h3>[{ oxmultilang ident="TYPE_OF_PAYMENT" }]</h3>
    <div class="content">
    [{oxifcontent ident="oxdeliveryinfo" object="oCont"}]
    <a href="[{ $oCont->getLink() }]" rel="nofollow"><img src="[{ $oViewConf->getModuleUrl('v6c_merchantlink','out/src/paypal.png')}]" alt="" title="PayPal"></a>
    [{/oxifcontent}]
    </div>
</div>
[{$smarty.block.parent}]
[{else}]
[{$smarty.block.parent}]
<div class="box">
    <h3>[{ oxmultilang ident="TYPE_OF_PAYMENT" }]</h3>
    <div class="content">
    [{oxifcontent ident="oxdeliveryinfo" object="oCont"}]
    <a href="[{ $oCont->getLink() }]" rel="nofollow"><img src="[{ $oViewConf->getModuleUrl('v6c_merchantlink','out/src/paypal.png')}]" alt="" title="PayPal"></a>
    [{/oxifcontent}]
    </div>
</div>
[{/if}]   