[{assign var="template_title" value="ORDER_COMPLETEORDER"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

<!-- ordering steps -->
[{if $oView->v6cIsIntegratedLink()}][{include file="inc/steps_item.tpl" highlight=3}][{else}][{include file="inc/steps_item.tpl" highlight=4}][{/if}]

<div class="box notice">
    <p><b class="fs10 def_color_1"><big>
		[{if $oView->v6cIsErr() }]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_ERROR" }]
		[{elseif $oView->v6cIsCancel() }]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CANCEL" }]
		[{elseif $oView->v6cIsIntegratedLink()}]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO" }]
		[{else}]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_SAVING" }]
		[{/if}]
    </big></b></p>
</div>

[{if $oView->v6cIsErr() }]
	<p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_ERRORDESC" }]</p>
[{elseif $oView->v6cIsCancel() }]
	<p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CANCELDESC" }]</p>
[{else}]
	[{*-- In the case of an auto-post, the message to be displayed here is only applicable if java is enabled, thus use java to display it --*}]
	[{if $oView->v6cIsAutoPost() }]
		<script language="JavaScript">
			document.write('<p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING" }]</p>');
		</script>
	[{else}]
		<p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING" }]</p>
	[{/if}]
[{/if}]

<div class="bar prevnext terms" id="v6cOrderRedirectBottom" style="display:none">
	<form action="[{if $oView->v6cIsErr() }][{ $oViewConf->getSslSelfLink() }][{else}][{ $oView->getGatewayUrl() }][{/if}]" method="post" name="v6c_redirectpost"[{if $oView->v6cIsAutoPost() }] style="display:none"[{/if}]>
		<div>
			[{if $oView->v6cIsErr() }]
				<input type="hidden" name="cl" value="v6c_redirectpost">
			[{elseif !$oView->v6cIsIntegratedLink()}]
				[{foreach from=$aPostData item=val key=key }]
					<input type="hidden" name="[{$key}]" value="[{$val}]">
				[{/foreach}]
			[{/if}]
			<div class="right arrowright">
				<input id="cnx_OrderSubmitBackupBtn" type="submit" value="[{if $oView->v6cIsIntegratedLink() }][{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN" }][{else}][{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }][{/if}]">
			</div>
		</div>
	</form>
</div>

[{*-- Auto-post should never be used in cases of error or user cancellation --*}]
[{if $oView->v6cIsAutoPost() }]
	<script language="JavaScript">
		document.cnx_redirectpost.submit();
		window.setTimeout(function() { document.getElementById('v6cOrderRedirectBottom').style.display = ''; }, 10000)
	</script>

	<noscript>
		<div class="box notice"><p>Your browser does not support the redirect.  Please use the Submit Order button below to continue to the secure payment gateway.</p></div>
		<div class="bar prevnext terms" id="cnx_OrderSubmitBackupDiv" style="display:none">
			<form action="[{ $oView->getGatewayUrl() }]" method="post" id="v6cOrderRedirectBottomBackup">
				<div>
					[{if !$oView->v6cIsIntegratedLink()}]
						[{foreach from=$aPostData item=val key=key }]
							<input type="hidden" name="[{$key}]" value="[{$val}]">
						[{/foreach}]
					[{/if}]
					<div class="right arrowright">
						<input id="cnx_OrderSubmitBackupBtn" type="submit" value="[{if $oView->v6cIsIntegratedLink() }][{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN" }][{else}][{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }][{/if}]">
					</div>
				</div>
			</form>
		</div>
	</noscript>
[{/if}]

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]