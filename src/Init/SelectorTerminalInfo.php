<?php

namespace PagOnline\Init;

use SimpleXMLElement;
use PagOnline\IgfsUtils;

/**
 * Class SelectorTerminalInfo.
 */
class SelectorTerminalInfo
{
    public $tid;
    public $description;
    public $payInstr;
    public $payInstrDescription;
    public $imgURL;

    /**
     * @param $xml
     * @param $tname
     *
     * @return SelectorTerminalInfo|void|null
     */
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
        $terminal = null;
        if (isset($response) && \count($response) > 0) {
            $terminal = new self();
            $terminal->tid = (IgfsUtils::getValue($response, 'tid'));
            $terminal->description = (IgfsUtils::getValue($response, 'description'));
            $terminal->payInstr = (IgfsUtils::getValue($response, 'payInstr'));
            $terminal->payInstrDescription = (IgfsUtils::getValue($response, 'payInstrDescription'));

            if (isset($response['imgURL'])) {
                $imgURL = [];
                foreach ($dom->children() as $item) {
                    if ('imgURL' == $item->getName()) {
                        $imgURL[] = $item->__toString();
                    }
                }
                $terminal->imgURL = $imgURL;
            }
        }

        return $terminal;
    }
}
