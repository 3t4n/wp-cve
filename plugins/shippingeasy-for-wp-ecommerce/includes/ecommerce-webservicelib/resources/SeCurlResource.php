<?php
/**
 * SeCurlResource.php.
 *
 * PHP Version 5.3.1
 *
 * @category  Resource
 * @package   Shippingeasy
 * @author    Saturized - The Interactive Agency <office@saturized.com>
 * @copyright 2010 Saturized - The Interactive Agency
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 * @version   SVN: $Id: nebojsa $
 */

/**
 * This class represents an abstract implementation of every resource.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeCurlResource.v.0.1
 */
abstract class SeCurlResource
{
  /**
   * Request method (GET or POST).
   *
   * @var string
   */
  protected $requestMethod;

  /**
   * Request headers
   *
   * @var array
   */
  protected $headers=array();

  /**
   * Request parameters.
   *
   * @var array
   */
  protected $parameters=array();

  /**
   * Response object.
   *
   * @var SeCurlGetItemsForShipping or SeCurlMarkShippedItems
   */
  protected $responseObject;

  /**
   * Response result.
   *
   * @var
   */
  protected $result;

  /**
   * Generated ApiKey.
   *
   * @var string
   */
  protected $generatedApiKey;

  /**
   * Determine if resource should call checkRequest() method during initialization.
   *
   * @var boolean
   */
  protected $shouldCheckRequest=true;

  /**
   * Abstract resource executor.
   *
   * @param
   *
   * @return
   */
  abstract public function execute();

  /**
   * Abstract resource parser.
   *
   * @param
   *
   * @return
   */
  abstract public function parse();

  /**
   * Abstract resource rollback method.
   *
   * @param
   *
   * @return
   */
  abstract public function rollback();

  /**
   * Returns requestMethod.
   *
   * @param
   *
   * @return requestMethod
   */
  public function getRequestMethod()
  {
    return $this->requestMethod;
  }

  /**
   * Sets requestMethod.
   *
   * @param requestMethod
   * @return
   */
  public function setRequestMethod($requestMethod)
  {
    $this->requestMethod = $requestMethod;
  }

  /**
   * Returns headers.
   *
   * @param
   *
   * @return headers
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Returns single header item.
   *
   * @param $key
   *
   * @return header item
   */
  public function getHeaderItem($key)
  {
    if (isset($this->headers[$key]))
    {
      return $this->headers[$key];
    }
    else
    {
      return null;
    }
  }

  /**
   * Sets array of headers.
   *
   * @param headers
   * @return
   */
  private function setHeaders($headers)
  {
    $this->headers = $headers;
  }

  public function getParameters()
  {
    return $this->parameters;
  }

  /**
   * Returns single parameter.
   *
   * @param $key
   *
   * @return parameter item
   */
  public function getParameter($key)
  {
    if (isset($this->parameters[$key]))
    {
      return $this->parameters[$key];
    }
    else
    {
      return null;
    }
  }

  /**
   * Sets array of parameters.
   *
   * @param parameters
   * @return
   */
  public function setParameters($parameters)
  {
    $this->parameters = $parameters;
  }

  /**
   * Sets single parameter item.
   *
   * @param $key, $value
   * @return
   */
  public function setParameter($key, $value)
  {
    $this->parameters[$key] = $value;
  }

  /**
   * Returns result.
   *
   * @param
   *
   * @return result
   */
  public function getResult()
  {
    return $this->result;
  }

  /**
   * Sets result.
   *
   * @param result
   * @return
   */
  public function setResult($result)
  {
    $this->result = $result;
  }

  /**
   * Returns responseObject.
   *
   * @param
   *
   * @return responseObject
   */
  public function getResponseObject()
  {
    return $this->responseObject;
  }

  /**
   * Sets responseObject.
   *
   * @param responseObject
   * @return
   */
  public function setResponseObject($responseObject)
  {
    $this->responseObject = $responseObject;
  }

  /**
   * Returns generatedApiKey.
   *
   * @param
   *
   * @return generatedApiKey
   */
  public function getGeneratedApiKey()
  {
    return $this->generatedApiKey;
  }

  /**
   * Sets generatedApiKey.
   *
   * @param generatedApiKey
   * @return
   */
  public function setGeneratedApiKey($generatedApiKey)
  {
    $this->generatedApiKey = $generatedApiKey;
  }

  /**
   * Returns shouldCheckRequest.
   *
   * @param
   *
   * @return shouldCheckRequest
   */
  public function getShouldCheckRequest()
  {
    return $this->shouldCheckRequest;
  }

  /**
   * Sets shouldCheckRequest.
   *
   * @param shouldCheckRequest
   * @return
   */
  public function setShouldCheckRequest($shouldCheckRequest)
  {
    $this->shouldCheckRequest = $shouldCheckRequest;
  }

  /*
   * This function collects mandatory parameters
   */
  public function fetchMandatoryParameters($parameters=array())
  {
    if ($this->getRequestMethod() == 'GET')
    {
      foreach($parameters as $key)
      {
        $value = SeApiUtils::getParameter($key);

        if (!is_null($value))
        {
          $this->setParameter($key, $value);
        }
        else
        {
          SeApiUtils::outputError('Parameter (' . $key . ') is not set.', 400);
        }
      }
    }
    elseif ($this->getRequestMethod() == 'POST')
    {
      $value = file_get_contents("php://input");

      if ($value !== FALSE)
      {
        $this->setParameter('postedJson', $value);
      }
      else
      {
        SeApiUtils::outputError('JSON object is not sent.', 400);
      }
    }
    else
    {
      if (isset($_SERVER['REQUEST_METHOD']))
      {
        SeApiUtils::outputError('Request method not allowed (' . $_SERVER['REQUEST_METHOD'] .')', 400);
      }
      else
      {
        SeApiUtils::outputError('Request method not allowed', 400);
      }
    }
  }

  /*
   * This function collects optional parameters
   */
  public function fetchOptionalParameters($parameters=array())
  {
    foreach($parameters as $key)
    {
      $value = SeApiUtils::getParameter($key);

      $this->setParameter($key, $value);
    }
  }

  /*
   * This function gets all headers of the request
   */
  private function getAllHeaders()
  {
    $headers = array();

    $heads = array_merge($_ENV, $_SERVER);

    $validHeaders = array(
      'X-ShippingEasy-PerPage',
      'X-ShippingEasy-Token',
      'X-ShippingEasy-Offset'
    );

    foreach ($heads as $key => $val)
    {
      if (strpos($key, 'HTTP_X_SHIPPINGEASY_') !== false)
      {
        $string = str_replace('HTTP_', '', $key);

        $string = strtolower(str_replace('_', '-', $string));

        foreach($validHeaders as $valid)
        {
          if (strtolower($valid) == $string)
          {
            $headers[$valid] = $val;
          }
        }
      }
    }

    ksort($headers);

    return $headers;
  }

  /*
   * This function returns string with specific number of stars.
   */
  public function addStars($count)
  {
    if ($count <= 0)
    {
      return '';
    }

    $string = '';

    for ($x = 0; $x < $count; $x++)
    {
      $string = $string . '*';
    }

    return $string;
  }

  /*
   * This function checks validity of the request
   */
  public function checkRequest()
  {
    if (trim($this->getGeneratedApiKey()) != trim($this->getHeaderItem('X-ShippingEasy-Token')))
    {
      $requested = trim($this->getHeaderItem('X-ShippingEasy-Token'));
      $stored = trim($this->getGeneratedApiKey());

      $reqLen = strlen(trim($requested));

      if ($reqLen > 20)
      {
        // get first 4
        $reqStart = substr($requested, 0, 4);

        // get last 4
        $reqEnd = substr($requested, -4);

        $stars = $this->addStars($reqLen - 8);

        $requested = $reqStart . $stars . $reqEnd;
      }

      $stoLen = strlen(trim($stored));

      if ($stoLen > 20)
      {
        // get first 4
        $stoStart = substr($stored, 0, 4);

        // get last 4
        $stoEnd = substr($stored, -4);

        $stars = $this->addStars($stoLen - 8);

        $stored = $stoStart . $stars . $stoEnd;
      }

      SeApiUtils::outputError('Generated APIKEY does not match. Requested: ' . $requested . ', Stored: ' . $stored, 401);
    }

    if (isset($_SERVER['REQUEST_METHOD']))
    {
      if (!in_array($_SERVER['REQUEST_METHOD'], array('POST', 'GET')))
      {
        SeApiUtils::outputError('Request method not allowed (' . $_SERVER['REQUEST_METHOD'] .')', 400);
      }
    }
  }

  /**
   * object's constructor
   *
   * @param $generatedApiKey
   *
   * @return
   */
  public function __construct($generatedApiKey)
  {
    $this->setGeneratedApiKey($generatedApiKey);

    $this->setHeaders($this->getAllHeaders());

    if ($this->getShouldCheckRequest())
    {
      $this->checkRequest();
    }

    $this->execute();

    $this->parse();
  }
}
?>