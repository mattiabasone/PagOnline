<?php

namespace PagOnline\Laravel;

use PagOnline\IgfsCgInterface;
use PagOnline\Exceptions\ClassNotFoundException;

/**
 * Class IgfsCgFactory.
 */
class IgfsCgFactory
{
    /**
     * @param string $namespace
     *
     * @throws \PagOnline\Exceptions\ClassNotFoundException
     *
     * @return \PagOnline\Init\IgfsCgInit|\PagOnline\Init\IgfsCgSelector|\PagOnline\Init\IgfsCgVerify|\PagOnline\Mpi\IgfsCgMpiAuth|\PagOnline\Mpi\IgfsCgMpiEnroll|\PagOnline\PayByMail\IgfsCgPayByMailInit|\PagOnline\PayByMail\IgfsCgPayByMailVerify|\PagOnline\Tokenizer\IgfsCgTokenizerCheck|\PagOnline\Tokenizer\IgfsCgTokenizerDelete|\PagOnline\Tokenizer\IgfsCgTokenizerEnroll|\PagOnline\Tran\IgfsCgAuth|\PagOnline\Tran\IgfsCgConfirm|\PagOnline\Tran\IgfsCgCredit|\PagOnline\Tran\IgfsCgVoidAuth
     */
    public static function make(string $namespace): IgfsCgInterface
    {
        if (\class_exists($namespace)) {
            /**
             * @var \PagOnline\Init\IgfsCgInit|\PagOnline\Init\IgfsCgVerify|\PagOnline\Init\IgfsCgSelector
             * @var \PagOnline\PayByMail\IgfsCgPayByMailInit|\PagOnline\PayByMail\IgfsCgPayByMailVerify                                             $igfsCgClass
             * @var \PagOnline\Mpi\IgfsCgMpiAuth|\PagOnline\Mpi\IgfsCgMpiEnroll                                                                     $igfsCgClass
             * @var \PagOnline\Tokenizer\IgfsCgTokenizerCheck|\PagOnline\Tokenizer\IgfsCgTokenizerEnroll|\PagOnline\Tokenizer\IgfsCgTokenizerDelete $igfsCgClass
             * @var \PagOnline\Tran\IgfsCgAuth|\PagOnline\Tran\IgfsCgConfirm|\PagOnline\Tran\IgfsCgCredit|\PagOnline\Tran\IgfsCgVoidAuth            $igfsCgClass
             */
            $igfsCgClass = new $namespace();
            $igfsCgClass->serverURL = config('pagonline.server_url');
            $igfsCgClass->timeout = config('pagonline.timeout');
            $igfsCgClass->tid = config('pagonline.terminal_id');
            $igfsCgClass->kSig = config('pagonline.signature_key');
            $igfsCgClass->currencyCode = config('pagonline.currency_code');
            $igfsCgClass->langID = config('pagonline.language_id');

            return $igfsCgClass;
        } else {
            throw new ClassNotFoundException("Class {$namespace} does not exists");
        }
    }
}
