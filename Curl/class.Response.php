<?php

namespace Xa\Lib\Network\Curl;

class Response
{
    const validHttpCode=200;

//CURLOPT_HEADERFUNCTION'<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /> ';

    private $_data;
    private $_headers;
    private $_charset;

    /**
     *
     * @var Component\Network\Curl
     */
    private $_curl;
    private $_connection;

    public function __construct($curl)
    {
        $this->_curl = $curl;
        $this->_curl->setOption(CURLOPT_HEADERFUNCTION, array($this, 'makeHeader'));
        $this->_connection = $this->_curl->getConnection();
    }

    public function make($data)
    {
        if (($code = $this->HTTP_CODE) != static::validHttpCode)
        {
            throw new Exceptions\HostReturnError($code);
        }
        $ct = $this->content_type;
        $this->_charset = \substr($ct, \strpos($ct, '=') + 1);
        $this->_data = \substr($data, $this->header_size);
    }

    public function makeHeader($c, $b)
    {
        $this->_headers[] = $b;
        return \mb_strlen($b);
    }

    public function encode($charset)
    {
        $this->_data = mb_convert_encoding($this->_data, $charset, $this->_charset);
        return $this;
    }

    public function toUTF()
    {
        return $this->encode('utf-8');
    }

    public function getData()
    {
        return $this->_data; //
    }

    public function getHeaders()
    {
        return $this->_headers;
    }

    public function getCharset()
    {
        return $this->_charset;
    }

    public function getInfo($param)
    {
        $param = \strtoupper($param);
        return \curl_getinfo($this->_connection, constant('CURLINFO_' . $param)); //2097154
    }

    public function __get($curlinfo)
    {
        return $this->getInfo($curlinfo);
    }

    public function __toString()
    {
        return $this->getData();
    }

}

?>