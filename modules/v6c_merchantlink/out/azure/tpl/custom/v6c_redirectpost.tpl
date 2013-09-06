[{capture append="oxidBlock_content"}]

    [{* ordering steps *}]
    [{if $oView->v6cIsIntegratedLink()}][{include file="page/checkout/inc/steps.tpl" active=3 }][{else}][{include file="page/checkout/inc/steps.tpl" active=4 }][{/if}]

    [{block name="v6c_checkout_redirect_main"}]

		<h3 class="blockHead">[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_TITLE" }]</h3>
		<p><b>
		[{if $oView->v6cIsErr() }]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_ERROR" }]
		[{elseif $oView->v6cIsCancel() }]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CANCEL" }]
		[{elseif $oView->v6cIsIntegratedLink()}]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO" }]
		[{else}]
			[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_SAVING" }]
		[{/if}]
		</b></p>

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

        [{block name="v6c_checkout_redirect_form"}]
            <form action="[{if $oView->v6cIsErr() }][{ $oViewConf->getSslSelfLink() }][{else}][{ $oView->getGatewayUrl() }][{/if}]" method="post" name="v6c_redirectpost" id="v6cOrderRedirectBottom"[{if $oView->v6cIsAutoPost() }] style="display:none"[{/if}]>
				[{if $oView->v6cIsErr() }]
					<input type="hidden" name="cl" value="v6c_redirectpost">
				[{elseif !$oView->v6cIsIntegratedLink()}]
					[{foreach from=$aPostData item=val key=key }]
						<input type="hidden" name="[{$key}]" value="[{$val}]">
					[{/foreach}]
				[{/if}]
                <div class="lineBox clear">
                	<a href="[{ oxgetseourl ident=$oViewConf->getPaymentLink() }]" class="btn previous prevStep submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_BACKSTEP" }]</a>
                    <button type="submit" class="btn submitButton nextStep largeButton">[{if $oView->v6cIsIntegratedLink() }][{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN" }][{else}][{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }][{/if}]</button>
                </div>
            </form>
        [{/block}]

		[{*-- Auto-post should never be used in cases of error or user cancellation --*}]
		[{if $oView->v6cIsAutoPost() }]
			<script language="JavaScript">
				document.v6c_redirectpost.submit();
				window.setTimeout(function() { document.getElementById('v6cOrderRedirectBottom').style.display = ''; }, 10000)
			</script>

			<noscript>
		        [{block name="v6c_checkout_redirect_backup"}]
		        	<div style="color:red"><p>[{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_NOJAVA" }]</p></div>
		            <form action="[{ $oView->getGatewayUrl() }]" method="post" id="v6cOrderRedirectBottomBackup">
						[{if !$oView->v6cIsIntegratedLink()}]
							[{foreach from=$aPostData item=val key=key }]
								<input type="hidden" name="[{$key}]" value="[{$val}]">
							[{/foreach}]
						[{/if}]
		                <div class="lineBox clear">
		                    <button type="submit" class="submitButton nextStep largeButton">[{if $oView->v6cIsIntegratedLink() }][{ oxmultilang ident="V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN" }][{else}][{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }][{/if}]</button>
		                </div>
		            </form>
		        [{/block}]
			</noscript>
		[{/if}]

    [{/block}]
    [{insert name="oxid_tracker" title=$template_title }]
[{/capture}]

[{*assign var="template_title" value="PAGE_CHECKOUT_ORDER_TITLE"|oxmultilangassign*}]
[{include file="layout/page.tpl" title=$template_title location=$template_title}]