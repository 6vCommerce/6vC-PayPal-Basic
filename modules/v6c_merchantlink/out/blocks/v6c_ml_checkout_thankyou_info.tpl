[{if $v6c_bCnfrmError}]
	<p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_THANKYOU_CONFIRMFAIL" }]</p>
[{else}]
[{$smarty.block.parent}]
[{/if}]