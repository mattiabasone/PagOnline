<?php

namespace PagOnline;

use Illuminate\Support\Facades\Config;
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
            // If Laravel helper function 'config' exists, I'll try to load configuration from .env file
            if (\class_exists('Config')) {
                $igfsCgClass->serverURL = Config::get('pagonline.server_url');
                $igfsCgClass->tid = Config::get('pagonline.terminal_id');
                $igfsCgClass->kSig = Config::get('pagonline.signature_key');

                // Checking if the class have these two properties
                if (property_exists($igfsCgClass, 'currencyCode')) {
                    $igfsCgClass->currencyCode = Config::get('pagonline.currency_code');
                }

                if (property_exists($igfsCgClass, 'langID')) {
                    $igfsCgClass->langID = Config::get('pagonline.language_id');
                }

                // HTTP configuration
                $igfsCgClass->setRequestTimeout((int) Config::get('pagonline.request_timeout'));
                $igfsCgClass->setConnectTimeout((int) Config::get('pagonline.connect_timeout'));
                $igfsCgClass->setHttpProxy(Config::get('pagonline.http_proxy'));
                $igfsCgClass->setHttpAuthUser(Config::get('pagonline.http_basic_auth_user'));
                $igfsCgClass->setHttpAuthUser(Config::get('pagonline.http_basic_auth_pass'));
            }

            return $igfsCgClass;
        } else {
            throw new ClassNotFoundException("Class {$namespace} does not exists");
        }
    }
}
