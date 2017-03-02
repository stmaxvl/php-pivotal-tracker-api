<?php

namespace PivotalTracker;

use PivotalTracker\Api\ApiInterface;
use PivotalTracker\Exception\InvalidArgumentException;
use PivotalTracker\Exception\BadMethodCallException;
use PivotalTracker\HttpClient\Builder;
use PivotalTracker\HttpClient\Plugin\Authentication;
use PivotalTracker\HttpClient\Plugin\PivotalTrackerExceptionThrower;
use PivotalTracker\HttpClient\Plugin\History;
use PivotalTracker\HttpClient\Plugin\PathPrepend;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Simple PHP Pivotal Tracker client
 *
 * @method Api\Project action()
 */
class Client
{
    /**
     * Constant for authentication method. Indicates the default.
     */
    const AUTH_API_TOKEN = 'api_token';

    /**
     * @var array
     */
    private $options = array(
        'base_url'    => 'https://www.pivotaltracker.com/services/',
        'user_agent'  => 'php-pivotal-tracker-api (http://github.com/bariton3/php-pivotal-tracker-api)',
        'timeout'     => 10,
        'api_limit'   => null,
        'api_version' => 'v5',
        'cache_dir'   => null,
    );

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * Instantiate a new Pivotal Tracker client.
     *
     * @param Builder|null $httpClientBuilder
     * @param string|null  $apiVersion
     */
    public function __construct(Builder $httpClientBuilder = null, $apiVersion = null)
    {
        $this->responseHistory = new History();
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new Builder();

        $builder->addPlugin(new PivotalTrackerExceptionThrower());
        $builder->addPlugin(new Plugin\HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new Plugin\RedirectPlugin());
        $builder->addPlugin(new Plugin\AddHostPlugin(UriFactoryDiscovery::find()->createUri($this->options['base_url'])));
        $builder->addPlugin(new PathPrepend(sprintf('/services/%s/', $this->options['api_version'])));
        $builder->addPlugin(new Plugin\HeaderDefaultsPlugin(array(
            'User-Agent' => $this->options['user_agent'],
        )));
    }

    /**
     * Create a PivotalTracker\Client using a HttpClient.
     *
     * @param HttpClient $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClient $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        switch ($name) {
            case 'epic':
            case 'epics':
            case 'Epic':
            case 'Epics':
                $api = new Api\Epic($this);
                break;

            case 'project':
            case 'projects':
            case 'Project':
            case 'Projects':
                $api = new Api\Project($this);
                break;

            case 'story':
            case 'stories':
            case 'Story':
            case 'Stories':
                $api = new Api\Story($this);
                break;

            case 'comment':
            case 'comments':
            case 'Comment':
            case 'Comments':
                $api = new Api\Comment($this);
                break;

            case 'search':
            case 'Search':
                $api = new Api\Search($this);
                break;

            case 'upload':
            case 'uploads':
                $api = new Api\Upload($this);
                break;

            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string      $tokenOrLogin Pivotal private api_token/username/client ID
     * @param null|string $password     Pivotal password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod   One of the AUTH_* class constants
     *
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate($tokenOrLogin, $password = null, $authMethod = null)
    {
        if (null === $tokenOrLogin && null === $authMethod) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }

        if (null === $authMethod) {
            $authMethod = self::AUTH_API_TOKEN;
        }

        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($tokenOrLogin, $password, $authMethod));
    }

    /**
     * Add a cache plugin to cache responses locally.
     *
     * @param CacheItemPoolInterface $cache
     * @param array                  $config
     */
    public function addCache(CacheItemPoolInterface $cachePool, array $config = [])
    {
        $this->getHttpClientBuilder()->addCache($cachePool, $config);
    }

    /**
     * Remove the cache plugin.
     */
    public function removeCache()
    {
        $this->getHttpClientBuilder()->removeCache();
    }

    /**
     * Generate multipart data for upload.
     */
    public function multipartBuild()
    {
        return $this->getHttpClientBuilder()->multipartBuild();
    }


    /**
     * @param string $name
     *
     * @throws BadMethodCallException
     *
     * @return ApiInterface
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }

    /**
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
