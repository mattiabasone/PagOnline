<?php

namespace PagOnline\Init;

/**
 * Class InitTerminalInfo
 * @package PagOnline\Init
 */
class InitTerminalInfo
{
    public $tid;
    public $payInstrToken;
    public $billingID;

    /**
     * @param $tname
     * @return string
     */
    public function toXml($tname)
    {
        $sb = '';
        $sb .= '<'.$tname.'>';
        if (null != $this->tid) {
            $sb .= '<tid><![CDATA[';
            $sb .= $this->tid;
            $sb .= ']]></tid>';
        }
        if (null != $this->payInstrToken) {
            $sb .= '<payInstrToken><![CDATA[';
            $sb .= $this->payInstrToken;
            $sb .= ']]></payInstrToken>';
        }
        if (null != $this->billingID) {
            $sb .= '<billingID><![CDATA[';
            $sb .= $this->billingID;
            $sb .= ']]></billingID>';
        }
        $sb .= '</'.$tname.'>';

        return $sb;
    }
}
