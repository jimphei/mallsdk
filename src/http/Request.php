<?php
// +----------------------------------------------------------------------
// | Bwsaas
// +----------------------------------------------------------------------
// | Copyright (c) 2015~2020 http://www.buwangyun.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Gitee ( https://gitee.com/buwangyun/bwsaas )
// +----------------------------------------------------------------------
// | Author: buwangyun <hnlg666@163.com>
// +----------------------------------------------------------------------
// | Date: 2020-9-28 10:55:00
// +----------------------------------------------------------------------
namespace Jimphei\mallsdk\http;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;

/**
 * Class Request 请求类二次封装
 * @package http
 */
class Request
{
    /**
     * \GuzzleHttp\Client单例
     * @var array
     */
    private static $instances = [];

    /**
     * \GuzzleHttp\Client;
     * @var Client
     */
    protected $client;

    /**
     * Body格式
     * @var string
     */
    protected $bodyFormat;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $promises = [];

    /**
     * 并发次数
     * @var
     */
    protected $concurrency;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->client = $this->getInstance();

        $this->bodyFormat = 'form_params';
        $this->options    = [
            'http_errors' => false,
        ];
    }

    /**
     *  Request destructor.
     */
    public function __destruct()
    {
        if (!empty($this->promises)) {
            Promise\settle($this->promises)->wait();
        }
    }

    /**
     * 获取单例
     * @return mixed
     */
    public function getInstance()
    {
        $name = get_called_class();

        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new Client();
        }

        return self::$instances[$name];
    }

    public function asForm()
    {
        $this->bodyFormat = 'form_params';
        $this->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);

        return $this;
    }

    public function asJson()
    {
        $this->bodyFormat = 'json';
        $this->withHeaders(['Content-Type' => 'application/json']);

        return $this;
    }

    public function asMultipart(string $name, string $contents, string $filename = null, array $headers = [])
    {
        $this->bodyFormat = 'multipart';

        $this->options = array_filter([
            'name'     => $name,
            'contents' => $contents,
            'headers'  => $headers,
            'filename' => $filename,
        ]);

        return $this;
    }

    public function withOptions(array $options)
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    public function withCert(string $path, string $password)
    {
        $this->options['cert'] = [$path, $password];

        return $this;
    }

    public function withHeaders(array $headers)
    {
        $this->options = array_merge_recursive($this->options, [
            'headers' => $headers,
        ]);

        return $this;
    }

    public function withBasicAuth(string $username, string $password)
    {
        $this->options['auth'] = [$username, $password];

        return $this;
    }

    public function withDigestAuth(string $username, string $password)
    {
        $this->options['auth'] = [$username, $password, 'digest'];

        return $this;
    }

    public function withUA(string $ua)
    {
        $this->options['headers']['User-Agent'] = trim($ua);

        return $this;
    }

    public function withToken(string $token, string $type = 'Bearer')
    {
        $this->options['headers']['Authorization'] = trim($type . ' ' . $token);

        return $this;
    }

    public function withCookies(array $cookies, string $domain)
    {
        $this->options = array_merge_recursive($this->options, [
            'cookies' => CookieJar::fromArray($cookies, $domain),
        ]);

        return $this;
    }

    public function withProxy($proxy)
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    public function withVersion($version)
    {
        $this->options['version'] = $version;

        return $this;
    }

    public function withRedirect($redirect = false)
    {
        $this->options['allow_redirects'] = $redirect;

        return $this;
    }

    public function withVerify($verify = false)
    {
        $this->options['verify'] = $verify;

        return $this;
    }

    public function withStream($boolean = false)
    {
        $this->options['stream'] = $boolean;

        return $this;
    }

    public function concurrency(int $times)
    {
        $this->concurrency = $times;

        return $this;
    }

    public function delay(int $seconds)
    {
        $this->options['delay'] = $seconds * 1000;

        return $this;
    }

    public function timeout(int $seconds)
    {
        $this->options['timeout'] = $seconds * 1000;

        return $this;
    }

    public function attach(string $name, string $contents, string $filename = null, array $headers = [])
    {
        $this->options['multipart'] = array_filter([
            'name'     => $name,
            'contents' => $contents,
            'headers'  => $headers,
            'filename' => $filename,
        ]);

        return $this;
    }

    public function get(string $url, array $query = [])
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $result);

        $this->options['query'] = array_merge($result, $query);

        return $this->request('GET', $url, $query);
    }

    public function post(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('POST', $url, $data);
    }

    public function patch(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('PATCH', $url, $data);
    }

    public function put(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('PUT', $url, $data);
    }

    public function delete(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('DELETE', $url, $data);
    }

    public function head(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('HEAD', $url, $data);
    }

    public function options(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('OPTIONS', $url, $data);
    }

    public function getAsync(string $url, $query = null, callable $success = null, callable $fail = null)
    {
        is_callable($query) || $this->options['query'] = $query;

        return $this->requestAsync('GET', $url, $query, $success, $fail);
    }

    public function postAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('POST', $url, $data, $success, $fail);
    }

    public function patchAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('PATCH', $url, $data, $success, $fail);
    }

    public function putAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('PUT', $url, $data, $success, $fail);
    }

    public function deleteAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('DELETE', $url, $data, $success, $fail);
    }

    public function headAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('HEAD', $url, $data, $success, $fail);
    }

    public function optionsAsync(string $url, $data = null, callable $success = null, callable $fail = null)
    {
        is_callable($data) || $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('OPTIONS', $url, $data, $success, $fail);
    }

    public function multiAsync(array $promises, callable $success = null, callable $fail = null)
    {
        $count = count($promises);

        $this->concurrency = $this->concurrency ? : $count;

        $requests = function () use ($promises) {
            foreach ($promises as $promise) {
                yield function() use ($promise) {
                    return $promise;
                };
            }
        };

        $fulfilled = function ($response, $index) use ($success){
            if (!is_null($success)) {
                $response = $this->response($response);
                call_user_func_array($success, [$response, $index]);
            }
        };

        $rejected = function ($exception, $index) use ($fail){
            if (!is_null($fail)) {
                $exception = $this->exception($exception);
                call_user_func_array($fail, [$exception, $index]);
            }
        };

        $pool = new Pool($this->client, $requests(), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => $fulfilled,
            'rejected'    => $rejected,
        ]);

        $pool->promise();

        return $pool;
    }

    protected function request(string $method, string $url, array $options = [])
    {
        isset($this->options[$this->bodyFormat]) && $this->options[$this->bodyFormat] = $options;
        try {
            $response = $this->client->request($method, $url, $this->options);
            return $this->response($response);
        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

    protected function requestAsync(string $method, string $url, $options = null, callable $success = null, callable $fail = null)
    {
        if (is_callable($options)) {
            $successCallback = $options;
            $failCallback    = $success;
        } else {
            $successCallback = $success;
            $failCallback    = $fail;
        }

        isset($this->options[$this->bodyFormat]) && $this->options[$this->bodyFormat] = $options;

        try {
            $promise = $this->client->requestAsync($method, $url, $this->options);

            $fulfilled = function ($response) use ($successCallback){
                if (!is_null($successCallback)) {
                    $response = $this->response($response);
                    call_user_func_array($successCallback, [$response]);
                }
            };

            $rejected = function ($exception) use ($failCallback){
                if (!is_null($failCallback)) {
                    $exception = $this->exception($exception);
                    call_user_func_array($failCallback, [$exception]);
                }
            };

            $promise->then($fulfilled, $rejected);

            $this->promises[] = $promise;

            return $promise;

        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

    protected function response($response)
    {
        return new Response($response);
    }

    protected function exception($exception)
    {
        return new RequestException($exception);
    }
}
