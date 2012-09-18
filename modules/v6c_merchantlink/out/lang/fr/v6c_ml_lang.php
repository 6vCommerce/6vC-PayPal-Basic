<?php
$sLangName  = "Français";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
'charset'										                        => 'ISO-8859-15',

// Used in templates (tpl files)
'V6C_EMAIL_ORDER_OWNER_PAYMENTEXTRA'									=> "Informations supplémentaires sur le portail sécuritaire de paiement:",
'V6C_PAGE_CHECKOUT_REDIRECT_TITLE'										=> "Acheminement vers le portail sécuritaire de paiement",
'V6C_PAGE_CHECKOUT_REDIRECT_SAVING'										=> "Enregistrement de votre commande et acheminement vers le portail sécuritaire de paiement...",
'V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING'									=> "Si vous n\\'êtes pas redirigé dans les 10 secondes qui suivent, veuillez réactiver le bouton &quot;Soumettre la commande&quot; qui apparaîtra ci-bas.", // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_PAGE_CHECKOUT_REDIRECT_NOJAVA'									    => "Votre navigateur ne peut pas se rediriger automatiquement au portail de paiement. Veuillez s'il vous plaît utiliser le bouton ci-dessous pour continuer.",
'V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO'									=> "Acheminement vers le portail sécuritaire de paiement en cours...",
'V6C_PAGE_CHECKOUT_REDIRECT_ERROR'										=> "Une erreur s'est produite avant que les informations de paiement ne puissent être recueillies",
'V6C_PAGE_CHECKOUT_REDIRECT_ERRORDESC'									=> "Vous pouvez essayer de nouveau en utilisant le bouton &quot;Continuer&quot; ci-dessous, ou ré-essayer plus tard. Désolé de cette inconvénience.",
'V6C_PAGE_CHECKOUT_REDIRECT_CANCEL'										=> "Le rassemblement des informations de paiement a été annulé",
'V6C_PAGE_CHECKOUT_REDIRECT_CANCELDESC'									=> "Vous avez annulé le transfert de vos informations.  Veuillez utiliser le bouton &quot;Continuer&quot; ci-dessous pour retourner au portail ou ré-essayer plus tard.",
'V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN'									=> 'Continuer',
'V6C_PAGE_CHECKOUT_THANKYOU_CONFIRMFAIL'								=> "Votre paiement a été traité par notre portail sécuritaire, cependant, la commande n'a pas être confirmée. Nous avons été avisé du problème et commençons immédiatement une enquête. Nous vous contacterons sous peu si votre commande ne peut être traitée.",
'V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT'								        => "Une erreur s'est produite lors du transfert de vos informations vers le portail de paiement. Veuillez essayer de nouveau ou nous aviser si cette erreur persiste.",
'V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL'								    => "La transaction a été annulée.",
'V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL'								    => "L'adresse (URL) du portail de paiement n'a pu être retrouvée. Veuillez essayer de nouveau ou nous aviser si cette erreur persiste.",

// Used in code (php files)
'V6C_THKYOU_NOORDER'							                        => 'An internal error occurred: order being confirmed could not be found in database.',
'V6C_THKYOU_NOSESSION'							                        => '',
'V6C_PAYPAL_NOCONNECT'							                        => 'Failed to connect to PayPal server.',
'V6C_PAYPAL_UNKWNRQ'							                        => 'Unknown confirmation type.',
'V6C_PAYPAL_MISSINGPARMS'						                        => 'Required data was not received from PayPal.',
'V6C_PAYPAL_BADID'								                        => 'Seller ID in request does not match seller ID at destination.',
'V6C_PAYPAL_PDTFAIL'							                        => 'Failed to retrieve PDT transaction data from PayPal server.',
'V6C_PAYPAL_IPNINVALID'							                        => 'Failed to retrieve IPN transaction data from PayPal server.',
'V6C_ORDER_ITEMNAME'							                        => 'Order Total (Note: quantity of 1 given by PayPal is normal and does not reflect the quantity of items you ordered)',
'V6C_ORDER_ERRBADSESSION'						                        => 'Failed to get basket or user from session.',
'V6C_ORDER_ERRBSKLDFAIL'						                        => 'Unable to load basket for user assigned to order.',
'V6C_ORDER_ERRPAYLDFAIL'						                        => 'Unable to load payment from order info.',
'V6C_ORDER_ERRPAYMISMATCH'						                        => 'Amount confirmed by payment gateway does not match amount for order.',
);