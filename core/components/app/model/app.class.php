<?php

class App
{
    /** @var modX $modx */
    public $modx;
    /** @var pdoFetch $pdo */
    public $pdoTools;
    public $config = [];

    const assets_version = '1.00';


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/app/';
        $assetsUrl = MODX_ASSETS_URL . 'components/app/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);
    }


    /**
     * Initialize App
     */
    public function initialize()
    {
        $this->pdoTools = $this->modx->getService('pdoFetch');
        if (!isset($_SESSION['csrf-token'])) {
            $_SESSION['csrf-token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }

        //$this->modx->addPackage('app', $this->config['modelPath']);
        /** @noinspection PhpIncludeInspection */
        //require_once $this->config['corePath'] . 'vendor/autoload.php';
    }


    /**
     * @param $action
     * @param array $data
     *
     * @return array|bool|mixed
     */
    public function runProcessor($action, array $data = [])
    {
        $action = 'web/' . trim($action, '/');
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, ['processors_path' => $this->config['processorsPath']]);
        if ($response) {
            $data = $response->getResponse();
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            return $data;
        }

        return false;
    }


    /**
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function handleEvent(modSystemEvent $event, array $scriptProperties)
    {
        extract($scriptProperties);
        switch ($event->name) {
            case 'pdoToolsOnFenomInit':
                /** @var Fenom|FenomX $fenom */
                $fenom->addAllowedFunctions([
                    'array_keys',
                    'array_values',
                ]);
                $fenom->addAccessorSmart('en', 'en', Fenom::ACCESSOR_PROPERTY);
                $fenom->en = $this->modx->getOption('cultureKey') == 'en';

                $fenom->addAccessorSmart('assets_version', 'assets_version', Fenom::ACCESSOR_PROPERTY);
                $fenom->assets_version = $this::assets_version;
                break;

            case 'OnHandleRequest':
                if ($this->modx->context->key == 'mgr') {
                    return;
                }

                // Remove slash and question signs at the end of url
                $uri = $_SERVER['REQUEST_URI'];
                if ($uri != '/' && in_array(substr($uri, -1), ['/', '?'])) {
                    $this->modx->sendRedirect(rtrim($uri, '/?'), ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                }

                // Remove .html extension
                if (preg_match('#\.html$#i', $uri)) {
                    $this->modx->sendRedirect(preg_replace('#\.html$#i', '', $uri),
                        ['responseCode' => 'HTTP/1.1 301 Moved Permanently']
                    );
                }

                // Switch context - uncomment it if you have more than one context
                /*
                $c = $this->modx->newQuery('modContextSetting', [
                    'key' => 'http_host',
                    'value' => $_SERVER['HTTP_HOST'],
                ]);
                $c->select('context_key');
                $tstart = microtime(true);
                if ($c->prepare() && $c->stmt->execute()) {
                    $this->modx->queryTime += microtime(true) - $tstart;
                    $this->modx->executedQueries++;
                    if ($context = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                        if ($context != 'web') {
                            $this->modx->switchContext($context);
                        }
                    }
                }
                */
                break;
            case 'OnLoadWebDocument':
                break;
            case 'OnPageNotFound':
                break;
            case 'OnWebPagePrerender':
                // Compress output html for Google
                $this->modx->resource->_output = preg_replace('#\s+#', ' ', $this->modx->resource->_output);
                break;
        }
    }

}