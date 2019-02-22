<?php

include __DIR__.'/config.php';

// ====================================================================
// = impostazione parametri per l’inizializzazione richiesta di
// = pagamento.
// = NB: I parametri riportati sono solo a titolo di esempio
// ====================================================================

$server = $config['servers'][$config['enabled_server']];

$shopID = \uniqid();
\file_put_contents(__DIR__.'/shopID.txt', $shopID);

$init = new \PagOnline\Init\IgfsCgInit();
$init->setRequestTimeout(15);

$init->serverURL = $server['url'];
$init->tid = $server['tid'];
$init->kSig = $server['kSig'];
$init->shopID = $shopID;
$init->shopUserRef = 'email@example.org';
$init->trType = 'AUTH';
$init->currencyCode = 'EUR';
$init->amount = 100;
$init->langID = 'IT';
$init->notifyURL = $config['base_url'].'verify.php';
$init->errorURL = $config['base_url'].'error.php';
$init->addInfo1 = 'myFirstAddintionalInfo';

// ====================================================================
// =esecuzione richiesta di inizializzazione
// ====================================================================
if (!$init->execute()) {
    // ====================================================================
    // = redirect del client su pagina di errore definita dall’Esercente =
    // ====================================================================
    \header('location: error.php?rc='.\urlencode($init->rc).'&errorDesc='.
        \urlencode($init->errorDesc));
    die();
}

\file_put_contents(__DIR__.'/paymentID.txt', $init->paymentID);

// NOTA: Salvo il $init->paymentID relativo alla richiesta (es. sul DB)...
// var_dump($init);
// die();
// ====================================================================
// = redirect del client verso URL PagOnline BuyNow
// ====================================================================
\header('location: '.$init->redirectURL);
