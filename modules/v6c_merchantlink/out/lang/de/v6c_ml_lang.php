<?php
$sLangName  = "English";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
'charset'										                        => 'ISO-8859-15',

// Used in templates (tpl files)
'V6C_EMAIL_ORDER_OWNER_PAYMENTEXTRA'								=> "Zusätzliche Info zum Händlerportal:",
'V6C_PAGE_CHECKOUT_REDIRECT_TITLE'								=> "Weiter zum sicheren Händlerportal",
'V6C_PAGE_CHECKOUT_REDIRECT_SAVING'								=> "Speichere Ihre Bestellinformationen und leite dann zum sicheren Händlerportal weiter...",
'V6C_PAGE_CHECKOUT_REDIRECT_SUBMITTING'								=> "Wenn Sie nicht innerhalb von 10 Sekunden weitergeleitet werden, können Sie den Button unten benutzen, um die Seite des sicheren Händlerportals manuell aufzurufen.", // NOTE: this is fed into a javascript write function, so single quotes must be escaped.
'V6C_PAGE_CHECKOUT_REDIRECT_NOJAVA'								=> "Ihr Browser unterstützt die automatisches Weiterleitung zum sicheren Händlerportal nicht. Bitte benutzen Sie den Button unten, um manuell fortzufahren.",
'V6C_PAGE_CHECKOUT_REDIRECT_PAYINFO'								=> 'Weiterleitung zum sicheren Händlerportal, um Zahlungsinformationen einzuholen...',
'V6C_PAGE_CHECKOUT_REDIRECT_ERROR'								=> 'Ein Fehler wurde verursacht, bevor die Zahlungsinformationen eingeholt werden konnten.',
'V6C_PAGE_CHECKOUT_REDIRECT_ERRORDESC'								=> 'Sie können versuchen, den &quot;Weiter&quot; Button unten sofort noch einmal zu bedienen, sonst versuchen sie es bitte später noch einmal. Wir entschuldigen uns für diese Unannehmlichkeit.',
'V6C_PAGE_CHECKOUT_REDIRECT_CANCEL'								=> 'Das Einsammeln der Zahlungsinformationen wurde unterbrochen',
'V6C_PAGE_CHECKOUT_REDIRECT_CANCELDESC'								=> 'Sie haben das Händlerportal verlassen, bevor alle Zahlungsinformationen gesammelt werden konnten. Sie können den &quot;Weiter&quot; Button unten benutzen, um zum Händlerportal zurückzukehren oder versuchen Sie diesen Schritt später erneut.',
'V6C_PAGE_CHECKOUT_REDIRECT_CONTBTN'								=> 'Weiter',
'V6C_PAGE_CHECKOUT_THANKYOU_CONFIRMFAIL'							=> 'Ihre Zahlung wurde von unserem Zahlungsportal durchgeführt aber die Bestellbestätigung schlug fehl. Unser Team wurde darüber informiert und wird sich schnellstmöglich darum kümmern. Wir treten mit Ihnen in Kontakt, falls wir Ihre Bestellung nicht ausführen können.',
'V6C_PAGE_CHECKOUT_PAYMENT_ERRINIT'								=> 'Fehler während der Übertragung der Informationen zum sicheren Händlerportal. Bitte versuchen Sie es erneut oder kontaktieren Sie uns, wenn der Fehler weiterhin auftritt.',
'V6C_PAGE_CHECKOUT_PAYMENT_USRCANCEL'								=> 'Transaktion wurde durch den Benutzer abgebrochen.',
'V6C_PAGE_CHECKOUT_PAYMENT_ERRNOURL'								=> 'Konnte die Adresse (URL) des Händlerportals nicht aufrufen. Bitte versuchen Sie es erneut oder kontaktieren Sie uns, wenn der Fehler weiterhin auftritt.', // Used in code (php files)
'V6C_THKYOU_NOORDER'							                        => 'Ein interner Fehler ist aufgetreten: Die bestätigte Bestellung konnte nicht in der Datenbank gefunden werden.',
'V6C_THKYOU_NOSESSION'							                        => '',
'V6C_PAYPAL_NOCONNECT'							                        => 'Konnte PayPal-Server nicht erreichen.',
'V6C_PAYPAL_UNKWNRQ'							                        => 'Unbekannter Bestätigungs-Typ.',
'V6C_PAYPAL_MISSINGPARMS'						                        => 'Angeforderte Daten konnten nicht von PayPal empfangen werden.',
'V6C_PAYPAL_BADID'								                => 'Verkäufer-ID in der Anfrage ist anders als die Verkäufer-ID des Ziels.',
'V6C_PAYPAL_PDTFAIL'							                        => 'Fehler beim Empfang der PDT-Übertragungsdaten vom PayPal-Server.',
'V6C_PAYPAL_IPNINVALID'							                        => 'Fehler beim Empfang der IPN-Übertragungsdaten vom PayPal-Server.',
'V6C_ORDER_ITEMNAME'							                        => 'Bestellung Gesamt (Hinweis: die Menge 1 von PayPal ist normal und gibt nicht die Anzahl der Produkte wieder, die Sie bestellt haben)',
'V6C_ORDER_ERRBADSESSION'						                        => 'Fehler beim Auslesen des Warenkorbs oder des Benutzers aus der Session.',
'V6C_ORDER_ERRBSKLDFAIL'						                        => 'Fehler beim Laden des Warenkorbs für den Benutzer, der dieser Bestellung zugeordnet ist.',
'V6C_ORDER_ERRPAYLDFAIL'						                        => 'Fehler beim Laden der Zahlung aus der Bestellinformation.',
'V6C_ORDER_ERRPAYMISMATCH'						                        => 'Der Betrag, der vom Händlerportal bestätigt wurde, ist anders als der Betrag der Bestellung.',
);
