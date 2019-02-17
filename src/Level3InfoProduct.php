<?php

namespace PagOnline;

use SimpleXMLElement;

/**
 * Class Level3InfoProduct.
 */
class Level3InfoProduct
{
    public $productCode;
    public $productDescription;
    public $items;
    public $amount;
    public $imgURL;

    public function toXml($tname)
    {
        $sb = '';
        $sb .= '<'.$tname.'>';
        if (null != $this->productCode) {
            $sb .= '<productCode><![CDATA[';
            $sb .= $this->productCode;
            $sb .= ']]></productCode>';
        }
        if (null != $this->productDescription) {
            $sb .= '<productDescription><![CDATA[';
            $sb .= $this->productDescription;
            $sb .= ']]></productDescription>';
        }
        if (null != $this->items) {
            $sb .= '<items><![CDATA[';
            $sb .= $this->items;
            $sb .= ']]></items>';
        }
        if (null != $this->amount) {
            $sb .= '<amount><![CDATA[';
            $sb .= $this->amount;
            $sb .= ']]></amount>';
        }
        if (null != $this->imgURL) {
            $sb .= '<imgURL><![CDATA[';
            $sb .= $this->imgURL;
            $sb .= ']]></imgURL>';
        }
        $sb .= '</'.$tname.'>';

        return $sb;
    }

    public static function fromXml($xml, $tname)
    {
        if ('' == $xml || null == $xml) {
            return;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return;
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
