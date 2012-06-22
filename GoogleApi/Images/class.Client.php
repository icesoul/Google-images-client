<?php
namespace Xa\Lib\Network\GoogleApi\Images;

/**
 * Googla images api guide - https://developers.google.com/image-search/v1/jsondevguide
 */
class Client
{

    /**
     * @var string IconImages icon restricts results to small images
     */
    const IconImages = 'icon';

    /**
     * @var string SmallImages restricts results to medium-sized images
     */
    const SmallImages = 'small';

    /**
     * @var string MediumImages restricts results to medium-sized images
     */
    const MediumImages = 'medium';

    /**
     * @var string LargeImages restricts results to medium-sized images
     */
    const LargeImages = 'large';

    /**
     * @var string XLargeImages restricts results to medium-sized images
     */
    const XLargeImages = 'xlarge';

    /**
     * @var string XXLargeImages restricts results to large images
     */
    const XXLargeImages = 'xxlarge';

    /**
     * @var string HugeImages restricts resykts to e
     */
    const HugeImages = 'huge';


    /**
     * @var string SafeActive active enables the highest level of safe search filtering.
     */
    const SafeActive = 'active';

    /**
     * @var string SafeModerate moderate enables moderate safe search filtering (default).
     */
    const SafeModerate = 'moderate';

    /**
     * @var string SafeOff off disables safe search filtering.
     */
    const SafeOff = 'off';


    /**
     * @var string ImgTypeFace face restricts results to images of faces.
     */
    const ImgTypeFace = 'face';

    /**
     * @var string ImgTypePhoto restricts results to photographic images.
     */
    const ImgTypePhoto = 'photo';

    /**
     * @var string ImgTypeClipart restricts results to clipart images.
     */
    const ImgTypeClipart = 'clipart';

    /**
     * @var string ImgTypeLineArt restricts results to line drawing images.
     */
    const ImgTypeLineArt = 'lineart';


    /**
     * @var array args list of valid attributes
     */
    static $args = array(
        'Query' => 'q',
        'Protocol' => 'v',
        'Filetype' => 'as_filetype',
        'Rights' => 'as_rights',
        'Site' => 'as_sitesearch',
        'Callback' => 'callback',
        'Context' => 'context',
        'Language' => 'hl',
        'Color' => 'imgcolor',
        'Size' => 'imgsz',
        'Type' => 'imgtype',
        'Restrict' => 'restrict',
        'Max' => 'rsz',
        'Safe' => 'safe',
        'Start' => 'start',
        'Ip' => 'userip'
    );

    /**
     * @var array _args list of current attributes
     */
    protected $_args = array('v' => '1.0', 'q' => 'sample query string', 'start' => 0, 'rsz' => 8);

    public function __construct(array $args = array())
    {
        $this->fromArray($args);
    }

    /**
     * Set query attrs from array
     * @param array $args an array of attributes (key=>value...etc)
     * @return Client
     */
    public function fromArray(array $args)
    {

        foreach ($args as $i => $arg)
        {
            if (array_key_exists($i, static::$args))
            {
                $this->_args[static::$args[$i]] = $arg;
            }
        }

        return $this;
    }

    public function __call($funcName, $args)
    {

        $preff = substr($funcName, 0, 3);
        $i = substr($funcName, 3);
        if (array_key_exists($i, static::$args))
        {
            if ($preff == 'set' && isset($args[0]))
            {
                if ($args[0] === false)
                {
                    unset($this->_args[static::$args[$i]]);
                }
                else
                {
                    $this->_args[static::$args[$i]] = $args[0];
                }
                return $this;
            }
            elseif ($preff == 'get')
            {
                return isset($this->_args[static::$args[$i]]) ? $this->_args[static::$args[$i]] : false;
            }
        }

        throw new AttributeNotFound('Attribute ' . $i . ' not found');

    }

    /**
     * RUN!!!
     * @return array
     * @throws GoogleHttpError
     */
    public function run()
    {
        $curl = new \Xa\Lib\Network\Curl\Client();
        $response = $curl->execute('https://ajax.googleapis.com/ajax/services/search/images?' . http_build_query($this->_args));

        $response = json_decode($response->getData());
        if ($response->responseStatus != 200)
        {
            throw new GoogleHttpError('Error:' . $response->responseDetails . ';Code:' . $response->responseStatus);
        }
        return $response->responseData->results;
    }


    protected function assign($name, $value = null)
    {
        if (is_null($value))
        {
            return $this->_args[$name];
        }
        elseif ($value === false && isset($this->_args[$name]))
        {
            unset($this->_args[$name]);
            return true;
        }

        return $this->_args[$name] = $value;

    }
}

class GoogleHttpError extends \Exception
{

}

class AttributeNotFound extends \Exception
{

}

?>