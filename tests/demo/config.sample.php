<?php

include __DIR__.'/../../vendor/autoload.php';

$config['base_url'] = 'http://my-local-domain.test/demo/';

$config['enabled_server'] = 'UC';
$config['servers'] = [
    'UC' => [
        'url' => 'https://testeps.netswgroup.it/UNI_CG_SERVICES/services',
        'tid' => 'UNI_MYBK',
        'kSig' => 'UNI_TESTKEY',
    ],
    'BNL' => [
        'url' => 'https://merchant.s2stest.bnlpositivity.it/BNL_CG_SERVICES/services',
        'tid' => '08000001_S',
        'kSig' => 'xHosiSb08fs8BQmt9Yhq3Ub99E8',
    ],
];
