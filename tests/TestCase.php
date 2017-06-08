<?php

namespace Tests;

use Exception;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $response = null;

    protected $headers = [];

    protected function setUp()
    {
        parent::setUp();
    }

    public function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(Exception $e) {}
            public function render($request, Exception $e) {
                throw $e;
            }
        });

        return $this;
    }

    /**
     * Get the response
     *
     * @return     TestResponse | failure
     */
    public function response()
    {
        return $this->response ?? $this->fail("No response yet");
    }

    /**
     * Assert that the json data has the specified count
     * @method assertJsonCount
     *
     * @return   $this
     */
    public function assertJsonCount( $count )
    {
        $data = $this->response()->json();

        $this->assertCount($count, $data);

        return $this;
    }

    /**
     * Set some headers
     *
     * @param      <type>  $headers  The headers
     */
    public function headers($headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Set the referrer header
     * @method from
     *
     * @return   $this
     */
    public function from($from)
    {
        return $this->headers([ 'referer' => $from ]);
    }

    /**
     * Post to some endpoint with some data and save the response
     * @method post
     *
     * @return   $this
     */
    public function post($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        
        $this->response = parent::post($endpoint, $data, $this->headers);

        return $this;
    }

    /**
     * Get some endpoint with some data and save the response
     * @method get
     *
     * @return   $this
     */
    public function get($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        
        $this->response = parent::get($endpoint, $data, $this->headers);

        return $this;
    }

    /**
     * Patch some endpoint with some data and save the response
     * @method patch
     *
     * @return   $this
     */
    public function patch($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        
        $this->response = parent::patch($endpoint, $data, $this->headers);

        return $this;
    }

    /**
     * Put some endpoint with some data and save the response
     * @method put
     *
     * @return   $this
     */
    public function put($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        
        $this->response = parent::put($endpoint, $data, $this->headers);

        return $this;
    }

    /**
     * Delete some endpoint with some data and save the response
     * @method delete
     *
     * @return   $this
     */
    public function delete($endpoint, array $data = [], array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        
        $this->response = parent::delete($endpoint, $data, $this->headers);

        return $this;
    }
}
