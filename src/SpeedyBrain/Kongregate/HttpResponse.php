<?php
namespace SpeedyBrain\Kongregate;

class HttpResponse
{
    /**
     * Http Status
     * @var int
     */
    protected $status;

    /**
     * Http Response Body
     * @var string
     */
    protected $body;

    /**
     * Http Response Content-Type
     * @var string
     */
    protected $contentType;

    /**
     * Curl/Http Error
     * @var string
     */
    protected $error;

    /**
     * Construct
     * @param int    $status      Http Status
     * @param string $body        Http Response Body
     * @param string $contentType Http Response Content-Type
     * @param string $error       Curl/Http Error
     */
    public function __construct(int $status, string $body, string $contentType = null, string $error = "")
    {
      $this->status = $status;
      $this->body = $body;
      $this->contentType = $contentType;
      $this->error = $error;
    }

    /**
     * @return int Http Status
     */
    public function status():int{return $this->status;}

    /**
     * @return string Http Response Body
     */
    public function body():string{return $this-$body;}

    /**
     * @return string Curl/Http Error
     */
    public function error():string{return $this->error;}

    /**
     * @param string $error Error Message
     */
    public function setError(string $error){$this->error = $error;}

    /**
     * Returns the decoded Http Body.
     * @return mixed
     */
    public function parsedBody()
    {
      if($this->contentType === null){return $this->body;}
      if(strpos($this->contentType,'application/json') !== false){
        return json_decode($this->body);
      }
      return $this->body;
    }

    /**
     * Returns true if the Http request was received, understood and accepted.
     * @return bool
     */
    public function success():bool
    {
      if (substr($this->status, 0, 1)!="2") {
        return false;
      }
      return true;
    }

    /**
     * Check if Http Response has an error message.
     * @return boolean
     */
    public function hasError():bool
    {
      if($this->error == null || $this->error == ""){
        return false;
      }
      return true;
    }
}
