<?php

namespace Xa\Lib\Network\Curl;

class Client
{
    const chrome15 = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.872.0 Safari/535.2';
    const chrome14 = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.813.0 Safari/535.1';
    const chrome13 = 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.41 Safari/535.1';
    const chrome12 = 'Mozilla/5.0 Slackware/13.37 (X11; U; Linux x86_64; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/12.0.742.91';
    const firefox6 = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0a2) Gecko/20110613 Firefox/6.0a2';
    const firefox5 = 'Mozilla/5.0 (X11; U; Linux i586; de; rv:5.0) Gecko/20100101 Firefox/5.0';
    const firefox4 = 'Mozilla/5.0 (X11; U; Linux x86_64; pl-PL; rv:2.0) Gecko/20110307 Firefox/4.0';
    const firefox3_6 = 'Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.9) Gecko/20100915 Gentoo Firefox/3.6.9';
    const opera12 = 'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00';
    const opera11 = 'Opera/9.80 (X11; Linux x86_64; U; pl) Presto/2.7.62 Version/11.00';
    const opera10 = 'Opera/9.80 (X11; Linux x86_64; U; en) Presto/2.2.15 Version/10.00';
    const opera9 = 'Opera/9.00 (Windows NT 5.2; U; ru)';
    const msie10 = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)';
    const msie9 = 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))';
    const msie8 = 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    const msie7 = 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)';
    const msie6 = 'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)';
    const msie5 = 'Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)';
    const msie4 = 'Mozilla/4.0 (compatible; MSIE 4.0; Windows NT)';


    private $statusCode;
    private $contentType;
    private $_connection;
    private $_post = array();
    public $error = false;

    /**
     *
     * @var Component\Network\Curl\Response
     */
    public $response;

    public function __construct($browser = self::msie9)
    {
        $this->_connection = \curl_init();
        $this->response = new Response($this);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_HEADER, true);
        $this->setOption(CURLOPT_USERAGENT, $browser);
    }

    public function post(array $data)
    {
        $this->_post = \array_merge($this->_post, $data);
    }

    public function execute($url)
    {
        if ($this->_post)
        {
            $this->setOption(CURLOPT_POST, true);
            $this->setOption(CURLOPT_POSTFIELDS, http_build_query($this->_post));
        }

        $this->setOption(CURLOPT_URL, $url);


        if (!$r = \curl_exec($this->_connection))
        {
            throw new Exceptions\ConnectionError(curl_error($this->_connection));
        }
        $this->response->make($r);

        return $this->response;
    }

    public function setOption($name, $value)
    {
        \curl_setopt($this->_connection, $name, $value);
    }

    public function ___set($optName, $optValue)
    {
        $this->setOption($optName, $optValue);
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function getLastError()
    {
        return curl_error($this->_connection);
    }

}

?>