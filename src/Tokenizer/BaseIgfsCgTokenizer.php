<?php

namespace PagOnline\Tokenizer;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgTokenizer.
 */
abstract class BaseIgfsCgTokenizer extends BaseIgfsCg
{
    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->shopID || '' == $this->shopID) {
            throw new IgfsMissingParException('Missing shopID');
        }
    }

    /**
     * @return string
     */
    protected function getServicePort()
    {
        return 'TokenizerGatewayPort';
    }
}
