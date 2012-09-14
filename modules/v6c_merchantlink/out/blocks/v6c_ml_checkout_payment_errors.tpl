[{*-- Keeping this file for reference but not actually used anymore --*}]

[{capture name="block_parent" assign="blk_prnt"}]
	[{$smarty.block.parent}]
[{/capture}]
[{capture name="block_new" assign="blk_new"}]
	[{ if $iPayError == 101}]
		<div class="status error">[{ oxmultilang ident="V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT" }]</div>
	[{ elseif $iPayError == 102}]
		<div class="status error">[{ oxmultilang ident="V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL" }]</div>
	[{ elseif $iPayError == 103}]
		<div class="status error">[{ oxmultilang ident="V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL" }]</div>
	[{/if}]
[{/capture}]

[{*-- Replace placeholder comment as well as extra repeated error message --*}]
[{$blk_prnt|regex_replace:"/<!--Add custom error message here-->(\s*\<div.*?\/div\>)?/s":$blk_new}]