<?php

namespace PagOnline\XmlEntities;

use SimpleXMLElement;
use PagOnline\IgfsUtils;

/**
 * Class Level3InfoProduct.
 */
class Level3InfoProduct extends BaseXmlEntity
{
    public $productCode;
    public $productDescription;
    public $items;
    public $amount;
    public $imgURL;

    /**
     * @param $xml
     * @param $tname
     *
     * @return \PagOnline\XmlEntities\Level3InfoProduct|null
     */
    public static function fromXml($xml, $tname): ?self
    {
        if (empty($xml)) {
            return null;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return null;
        }

        $response = IgfsUtils::parseResponseFields($dom);
        $product = null;
        if (isset($response) && \count($response) > 0) {
            $product = new self();
            $product->productCode = (IgfsUtils::getValue($response, 'productCode'));
            $product->productDescription = (IgfsUtils::getValue($response, 'productDescription'));
            $product->items = (IgfsUtils::getValue($response, 'items'));
            $product->amount = (IgfsUtils::getValue($response, 'amount'));
            $product->imgURL = (IgfsUtils::getValue($response, 'imgURL'));
        }

        return $product;
    }
}
