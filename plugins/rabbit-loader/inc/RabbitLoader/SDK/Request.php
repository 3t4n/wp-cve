<?php

namespace RabbitLoader\SDK;

class Request
{
    private $licenseKey = '';
    private $requestURL = "";
    private $requestURI = "";
    private $cacheFile = null;
    private $debug = false;
    private $rootDir = '';
    private $ignoreRead = false;
    private $ignoreWrite = false;
    private $ignoreReason = '';
    private $isNoOptimization = false;
    private $isWarmup = false;
    private $onlyAfter = 0;
    private $purgeCallback = null;
    private $meMode = false;
    private $rlTest = false;
    private $platform = [];

    const IG_PARAMS = ['_gl', 'epik', 'fbclid', 'gbraid', 'gclid', 'msclkid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'vgo_ee', 'wbraid', 'zenid', 'rltest', 'rlrand'];

    public function __construct($licenseKey, $rootDir)
    {
        $this->licenseKey = $licenseKey;
        $this->rootDir = $rootDir;
        $this->parse();
        $this->ignoreParams(self::IG_PARAMS);
        $this->cacheFile = new Cache($this->getURL(), $this->rootDir);
        if (empty($licenseKey)) {
            $this->ignoreRequest('disconnected');
        }
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        $this->cacheFile->setDebug($this->debug);
    }

    public function getURL()
    {
        return $this->requestURL;
    }

    public function ignoreRequest($reason = 'default')
    {
        //replace newlines form reason text to avoid issues in appendFooter
        $reason = str_replace(array("\n", "\r"), '', $reason);
        $this->ignoreRead = true;
        $this->ignoreWrite = true;
        $this->ignoreReason = $reason;
        Util::sendHeader('x-rl-skip: ' . $this->ignoreReason, true);
    }

    public function skipForCookies($cookieNames)
    {
        if (!empty($cookieNames)) {
            foreach ($cookieNames as $c) {
                if (isset($_COOKIE[$c])) {
                    $this->ignoreRequest("cookie-$c");
                    break;
                }
            }
        }
    }

    public function skipForPaths($patterns)
    {
        if (!empty($patterns)) {
            foreach ($patterns as $i => $path_pattern) {
                if (!empty($path_pattern) && !empty($this->requestURI)) {
                    $matched = fnmatch(trim($path_pattern), trim($this->requestURI));
                    if (!$matched) {
                        $matched = fnmatch(trim($path_pattern), rawurldecode($this->requestURI));
                    }
                    if (!$matched) {
                        $matched = fnmatch($path_pattern, rawurldecode($this->requestURI));
                    }
                    if ($matched) {
                        $this->ignoreRequest("skip-path-$path_pattern");
                        break;
                    }
                }
            }
        }
    }

    public function ignoreParams($paramNames)
    {
        if (empty($paramNames)) {
            return;
        }
        $parsed_url = parse_url($this->requestURL);
        $query = empty($parsed_url['query']) ? '' : trim($parsed_url['query']);
        if (!empty($query)) {
            try {
                parse_str($query, $qs_vars);

                if (!empty($paramNames)) {
                    foreach ($paramNames as $p) {
                        unset($qs_vars[trim($p)]);
                    }
                }

                $query = http_build_query($qs_vars);

                $this->requestURI = trim(@$parsed_url['path']) . (empty($query) ? '' : '?' . $query);;
                $host = trim(@$parsed_url['host']);
                $scheme = trim(@$parsed_url['scheme']);
                $this->requestURL = $scheme . '://' . $host . $this->requestURI;
            } catch (\Throwable $e) {
                Exc:: catch($e);
            }
        }
    }

    /**
     * @param array $ignore_params pass array containing the params to be ignored for caching.
     */
    private function parse()
    {
        $rm = Util::getRequestMethod();
        if (strcasecmp($rm, 'get') !== 0) {
            $this->ignoreRequest("request-method-$rm");
            return;
        }
        //process request
        if (isset($_SERVER['REQUEST_URI'])) {
            list($urlpart, $qspart) = array_pad(explode('?', $_SERVER['REQUEST_URI']), 2, '');
            parse_str($qspart, $qsvars);

            $varsLenO = count($qsvars);
            if (isset($qsvars['rl-no-optimization']) || isset($_SERVER['HTTP_RL_NO_OPTIMIZATION']) || isset($_SERVER['HTTP_RL_CSS'])) {
                unset($qsvars['rl-no-optimization']);
                $this->isNoOptimization = true;
                $this->ignoreRequest("no-optimization");
                unset($_GET['rl-no-optimization']);
            }

            if (isset($qsvars['norl'])) {
                unset($qsvars['norl']);
                $this->isNoOptimization = true;
                $this->ignoreRequest("no-optimization");
                unset($_GET['norl']);
            }

            if (isset($qsvars['rl-warmup'])) {
                unset($qsvars['rl-warmup']);
                $this->ignoreRead = true;
                $this->isWarmup = true;
                unset($_GET['rl-warmup']);
            }

            if (isset($qsvars['rltest'])) {
                $this->rlTest = true;
                unset($qsvars['rltest']);
                unset($_GET['rltest']);
            }

            if (isset($qsvars['rl-rand'])) {
                unset($qsvars['rl-rand']);
                unset($_GET['rl-rand']);
            }

            if (isset($qsvars['rl-only-after'])) {
                $this->onlyAfter = ($qsvars['rl-only-after'] / 1000);
                unset($qsvars['rl-only-after']);
                unset($_GET['rl-only-after']);
            }

            $varsLenM = count($qsvars);
            if ($varsLenO != $varsLenM) {
                $newqs = http_build_query($qsvars);
                $_SERVER['REQUEST_URI'] = $urlpart . (empty($newqs) ?  '' : '?' . $newqs);
            }
        }
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $raw_link = ($this->isHTTPS() ? "https" : "http") . "://$http_host$request_uri";

        $parsed_url = parse_url($raw_link);
        $query = empty($parsed_url['query']) ? '' : trim($parsed_url['query']);

        $this->requestURI = trim(@$parsed_url['path']) . (empty($query) ? '' : '?' . $query);;
        $host = trim(@$parsed_url['host']);
        $scheme = trim(@$parsed_url['scheme']);
        $this->requestURL  = $scheme . '://' . $host . $this->requestURI;
    }

    private function isHTTPS()
    {
        return (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) || (isset($_SERVER['HTTPS']) && strcmp($_SERVER['HTTPS'], "off") !== 0);
    }

    private function serve()
    {
        if ($this->isWarmup) {
            if ($this->cacheFile->fresh(Cache::TTL_LONG, $this->onlyAfter)) {
                if (!empty($this->purgeCallback)) {
                    call_user_func_array($this->purgeCallback, [$this->getURL()]);
                }
                Util::sendHeader('x-rl-cache: fresh', true);
                exit;
            } else {
                Util::sendHeader('x-rl-cache: stale', true);
            }
        } else {
            if ($this->meMode && !$this->rlTest) {
                Util::sendHeader('x-rl-skip: me-mode', true);
            } else if ($this->ignoreRead) {
                Util::sendHeader('x-rl-skip: ' . $this->ignoreReason, true);
            } else {
                if ($this->cacheFile->serve()) {
                    exit;
                } else {
                    Util::sendHeader('x-rl-cache: miss', true);
                }
            }
        }

        if ($this->isNoOptimization || $this->isWarmup) {
            Util::sendHeader('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);
            Util::sendHeader('Cache-Control: post-check=0, pre-check=0', false);
            Util::sendHeader('Pragma: no-cache', true);
        }

        ob_start([$this, 'addFooter']);
    }

    /**
     * Saves buffer and append footer to all requests
     */
    public function addFooter($buffer)
    {
        $code = http_response_code();
        if ($code != 200) {
            $this->ignoreRequest('status-' . $code);
        }

        $validBuffer = is_string($buffer);
        if ($this->debug) {
            $bufferLen = $validBuffer ? strlen($buffer) : 0;
            $level = ob_get_level();
            Util::sendHeader("x-rl-buffer: LN:$bufferLen LV:$level", false);
        }

        if ($validBuffer) {
            try {
                $bom = pack('H*', 'EFBBBF');
                if ($bom !== false) {
                    $buffer = preg_replace("/^($bom)*/", '', $buffer);
                }
                $headersList = headers_list();
                $headers = [];
                $contentType = NULL;
                foreach ($headersList as $h) {
                    $p = explode(':', $h, 2);
                    $headers[trim($p[0])] = [trim($p[1])];
                    if (strcasecmp(trim($p[0]), 'content-type') === 0) {
                        $contentType = $p[1];
                    }
                }
                $isHtml = ($contentType && stripos($contentType, 'text/html') !== false);
                $isAmp = preg_match("/<html.*?\s(amp|⚡)(\s|=|>)/", $buffer);

                if ($isHtml && !$isAmp) {
                    $this->appendFooter($buffer);
                    if ($this->isWarmup && !$this->ignoreWrite) {
                        $this->cacheFile->save(Cache::TTL_SHORT, $buffer, $headers);
                        $this->refresh($this->requestURL, true);
                    }
                }

                if ($this->debug) {
                    Util::sendHeader("x-rl-page: H:$isHtml A:$isAmp", false);
                }
            } catch (\Throwable $e) {
                if ($this->debug) {
                    $buffer = $e->getMessage();
                }
                Exc:: catch($e);
            } catch (\Exception $e) {
                if ($this->debug) {
                    $buffer = $e->getMessage();
                }
                Exc:: catch($e);
            }
        }
        return $buffer;
    }

    private function appendFooter(&$buffer)
    {
        $appended = Util::append($buffer, '<script data-rlskip="1" id="rl-sdk-js-0">!function(e,r,a,t){var n="searchParams",l="append",i="getTime",o="Date",d=e.rlPageData||{},f=d.rlCached;r.cookie="rlCached="+(f?"1":"0")+"; path=/;";let c=new e[o];function h(r){if(!r)return;let a=new e[o](r);return a&&a.getFullYear()>1970&&a<c}let u=h(d.exp),p=h(d.rlModified);(!f||u||p)&&!a&&setTimeout(function r(){let a=new e[o](p?d.rlModified:t);if(u){let f=new e[o](d.exp);f>a&&(a=f)}var h=new URL(location.href);h[n][l]("rl-warmup","1"),h[n][l]("rl-rand",c[i]()),h[n][l]("rl-only-after",a[i]()),fetch(h)},1e3)}(this,document,"' . $this->ignoreReason . '","' . date('c') . '");</script>');

        if ($this->debug) {
            Util::sendHeader("x-rl-footer: $appended", false);
        }
    }

    public function process()
    {
        try {
            $this->serve();
        } catch (\Throwable $e) {
            Exc:: catch($e);
        } catch (\Exception $e) {
            Exc:: catch($e);
        }
    }

    private function refresh($url, $force)
    {
        if ($this->cacheFile->get429()) {
            Util::sendHeader('x-rl-429: 1', true);
            return;
        }

        $api = new API($this->licenseKey, $this->platform);
        $api->setDebug($this->debug);

        //send HB before refresh to unblock previous un-installation if any
        if ($this->cacheFile->collectGarbage(strtotime('-15 minutes'))) {
            Util::sendHeader('x-rl-hb-pre: 1', true);
            $api->heartbeat();
            Util::sendHeader('x-rl-hb-post: 1', true);
        }

        $response = $api->refresh($this->cacheFile, $url, $force);

        $resJson = json_encode($response);
        if ($resJson) {
            //print resJson string if encode success
            Util::sendHeader('x-rl-debug-refresh1:' . $resJson, true);
        } else {
            //print raw response if encode fails
            Util::sendHeader('x-rl-debug-refresh2:' . $response, true);
        }

        if (!empty($response['saved']) && !empty($this->purgeCallback)) {
            call_user_func_array($this->purgeCallback, [$url]);
            Util::sendHeader('x-rl-refresh-saved: 1', true);
        } else {
            $this->cacheFile->set429();
            if (!empty($response['message'])) {
                Util::sendHeader('x-rl-debug-refresh1:' . $resJson, true);
                if (strcasecmp($response['message'], 'BQE') === 0) {
                    $this->cacheFile->deleteAll();
                }
            }
        }
        exit;
    }

    public function setVariant($variant)
    {
        $this->cacheFile->setVariant($variant);
    }

    public function isWarmUp()
    {
        return $this->isWarmup;
    }

    public function registerPurgeCallback($cb)
    {
        $this->purgeCallback = $cb;
    }

    public function setMeMode()
    {
        return $this->meMode = true;
    }

    public function setPlatform($data)
    {
        return $this->platform += $data;
    }
}
