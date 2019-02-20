<?php

include __DIR__.'/config.php';

// ====================================================================
// =importazione classi di riferimento
// ====================================================================
// ====================================================================
// = impostazione parametri per l’inizializzazione richiesta di
// = pagamento.
// = NB: I parametri riportati sono solo a titolo di esempio
// ====================================================================

$server = $config['servers'][$config['enabled_server']];

$verify = new \PagOnline\Init\IgfsCgInit();
$verify->serverURL = $server['url'];
$verify->tid = $server['tid']; //per servizio MyBank usare UNI_MYBK
$verify->kSig = $server['kSig'];
$verify->timeout = 15000;
$verify->shopID = \file_get_contents(__DIR__.'/shopID.txt'); // Chiave esterna UNIVOCA identificante il pagamento
$verify->paymentID = \file_get_contents(__DIR__.'/paymentID.txt'); // NOTA: Leggo il paymentID rilasciato in fase di init (es. dalDB)...

$errorURL = $config['base_url'].'error.php';
$esitoURL = $config['base_url'].'esito.php';

// ====================================================================
// = esecuzione richiesta di verifica
// ====================================================================
if (!$verify->execute()) {
    // ====================================================================
    // = redirect del client su pagina di errore definita dall’Esercente =
    // ====================================================================
    \header('location: '.$errorURL.'?rc='.$verify->rc.'&errorDesc='.$verify->errorDesc);

    return;
}
// ====================================================================
// = redirect del client verso URL Esito Pagamento Esercente =
// ====================================================================
    \header('location: '.$esitoURL.'?esito=OK&rc='.$verify->rc.'&tranID='.
        $verify->tranID.'&enrStatus='.$verify->enrStatus.'&authStatus='.
        $verify->authStatus);
