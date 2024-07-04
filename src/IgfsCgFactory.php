<?php

namespace PagOnline;

use Illuminate\Support\Facades\Config;
use PagOnline\Exceptions\ClassNotFoundException;

class IgfsCgFactory
{
    /**
     * @param string $namespace
     *
     * @throws ClassNotFoundException
     *
     * @return Init\IgfsCgInit|Init\IgfsCgSelector|Init\IgfsCgVerify|Mpi\IgfsCgMpiAuth|Mpi\IgfsCgMpiEnroll|PayByMail\IgfsCgPayByMailInit|PayByMail\IgfsCgPayByMailVerify|Tokenizer\IgfsCgTokenizerCheck|Tokenizer\IgfsCgTokenizerDelete|Tokenizer\IgfsCgTokenizerEnroll|Tran\IgfsCgAuth|Tran\IgfsCgConfirm|Tran\IgfsCgCredit|Tran\IgfsCgVoidAuth
     */
    public static function make(string $namespace): IgfsCgInterface
    {
        if (class_exists($namespace)) {
            /**
             * @var Init\IgfsCgInit|Init\IgfsCgSelector|Init\IgfsCgVerify
             * @var PayByMail\IgfsCgPayByMailInit|PayByMail\IgfsCgPayByMailVerify                                  $igfsCgClass
             * @var Mpi\IgfsCgMpiAuth|Mpi\IgfsCgMpiEnroll                                                          $igfsCgClass
             * @var Tokenizer\IgfsCgTokenizerCheck|Tokenizer\IgfsCgTokenizerDelete|Tokenizer\IgfsCgTokenizerEnroll $igfsCgClass
             * @var Tran\IgfsCgAuth|Tran\IgfsCgConfirm|Tran\IgfsCgCredit|Tran\IgfsCgVoidAuth                       $igfsCgClass
             */
            $igfsCgClass = new $namespace();
            // If Laravel helper function 'config' exists, I'll try to load configuration from .env file
            if (class_exists('Config')) {
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
        }

        throw new ClassNotFoundException("Class {$namespace} does not exists");
    }
}
