<?php

namespace hardworm\YandexXml;

use hardworm\YandexXml\Exceptions\YandexXmlException;
use SimpleXMLElement;

class YandexXmlClient
{
    /**
     * Base url to service
     */
    private string $baseUrl = 'https://yandex.ru/search/xml';

    /**
     * Response
     *
     * @see http://help.yandex.ru/xml/?id=362990
     */
    public SimpleXMLElement $response;

    /**
     * Wordstat array
     */
    public array $wordstat = [];

    /**
     * Response in array
     */
    public array $results = [];

    /**
     * Total results
     */
    public ?int $total = null;

    /**
     * Total results in human form
     *
     * @var string
     */
    public ?string $totalHuman = null;

    /**
     * User
     */
    protected string $user;

    /**
     * Key
     */
    protected string $key;

    /**
     * Query
     */
    protected string $query;

    /**
     * Request
     */
    protected string $request;

    /**
     * Host
     */
    protected ?string $host = null;

    /**
     * Site
     */
    protected ?string $site = null;

    /**
     * Domain
     */
    protected ?string $domain = null;

    /**
     * cat
     *
     * @see http://search.yaca.yandex.ru/cat.c2n
     */
    protected ?int $cat = null;

    /**
     * theme
     *
     * @see http://help.yandex.ru/site/?id=1111797
     */
    protected ?int $theme = null;

    /**
     * geo
     *
     * @see http://search.yaca.yandex.ru/geo.c2n
     */
    protected ?int $geo = null;

    /**
     * lr
     */
    protected ?int $lr = null;

    /**
     * Number of page
     */
    protected int $page = 0;

    /**
     * Number of results per page
     */
    protected int $limit = 10;

    /**
     * Sort By   'rlv' || 'tm'
     *
     * @see http://help.yandex.ru/xml/?id=316625#sort
     * @var string
     */
    public const SORT_RLV = 'rlv'; // relevation
    public const SORT_TM = 'tm';  // time modification

    protected string $sortBy = 'rlv';

    /**
     * Group By  '' || 'd'
     *
     * @see http://help.yandex.ru/xml/?id=316625#group
     * @var string
     */
    private const GROUP_DEFAULT = '';
    private const GROUP_SITE = 'd'; // group by site

    protected string $groupBy = '';

    /**
     * Group mode   'flat' || 'deep' || 'wide'
     *
     * @var string
     */
    public const GROUP_MODE_FLAT = 'flat';
    public const GROUP_MODE_DEEP = 'deep';
    public const GROUP_MODE_WIDE = 'wide';

    protected string $groupByMode = 'flat';

    /**
     * Options of search
     *
     * @var array
     */
    protected array $options = [
        'maxpassages'         => 2,    // from 2 to 5
        'max-title-length'    => 160, //
        'max-headline-length' => 160, //
        'max-passage-length'  => 160, //
        'max-text-length'     => 640, //
    ];

    /**
     * Proxy params
     * Default - no proxy
     * @var array
     */
    protected array $proxy = [
        'host' => '',
        'port' => 0,
        'user' => '',
        'pass' => ''
    ];

    /**
     * @throws YandexXmlException
     */
    public function __construct(string $user, string $key)
    {
        if (empty($user) || empty($key)) {
            throw new YandexXmlException(YandexXmlException::solveMessage(0));
        }
        $this->user = $user;
        $this->key = $key;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setCat(int $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getCat(): int
    {
        return $this->cat;
    }

    public function setGeo(int $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getGeo(): int
    {
        return $this->geo;
    }

    public function setTheme(int $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): ?int
    {
        return $this->theme;
    }

    public function setLr(int $lr): self
    {
        $this->lr = $lr;

        return $this;
    }

    public function getLr(): int
    {
        return $this->lr;
    }

    public function setSortBy(string $sortBy): self
    {
        if ($sortBy === self::SORT_RLV || $sortBy === self::SORT_TM) {
            $this->sortBy = $sortBy;
        }

        return $this;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setGroupBy(string $groupBy, string $mode = self::GROUP_MODE_FLAT): self
    {
        if ($groupBy === self::GROUP_DEFAULT || $groupBy === self::GROUP_SITE) {
            $this->groupBy = $groupBy;
            if ($groupBy == self::GROUP_DEFAULT) {
                $this->groupByMode = self::GROUP_MODE_FLAT;
            } else {
                $this->groupByMode = $mode;
            }
        }

        return $this;
    }

    public function getGroupBy(): string
    {
        return $this->groupBy;
    }

    public function getGroupByMode(): string
    {
        return $this->groupByMode;
    }

    /**
     * free setter for options
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return YandexXmlClient
     */
    public function setOption(string $option, $value = null): self
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Set proxy fo request
     *
     * @param string      $host
     * @param integer     $port
     * @param string|null $user
     * @param string|null $pass
     *
     * @return YandexXmlClient
     */
    public function setProxy(string $host = '', int $port = 80, ?string $user = null, ?string $pass = null): self
    {
        $this->proxy = [
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pass' => $pass,
        ];

        return $this;
    }

    /**
     * Apply proxy before each request
     *
     * @param resource $ch
     */
    protected function applyProxy($ch): void
    {
        $optArr = [
            CURLOPT_PROXY        => $this->proxy['host'],
            CURLOPT_PROXYPORT    => $this->proxy['port'],
        ];

        if (!empty($this->proxy['user']) && !empty($this->proxy['pass'])) {
            $optArr[CURLOPT_PROXYUSERPWD] = $this->proxy['user'] . ':' . $this->proxy['pass'];
        }

        curl_setopt_array($ch, $optArr);
    }

    /**
     * send request
     * @return YandexXmlClient
     * @throws YandexXmlException
     */
    public function request(): self
    {
        if (empty($this->query) && empty($this->host)) {
            throw new YandexXmlException(YandexXmlException::solveMessage(2));
        }

        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><request></request>");

        // add query to request
        $query = $this->query;

        // if isset "host"
        if ($this->host) {
            $host_query = 'host:"' . $this->host . '"';

            if (!empty($query) && $this->host) {
                $query .= ' ' . $host_query;
            } elseif (empty($query) && $this->host) {
                $query .= $host_query;
            }
        }

        // if isset "site"
        if ($this->site) {
            $site_query = 'site:"' . $this->site . '"';

            if (!empty($query) && $this->site) {
                $query .= ' ' . $site_query;
            } elseif (empty($query) && $this->site) {
                $query .= $site_query;
            }
        }

        // if isset "domain"
        if ($this->domain) {
            $domain_query = 'domain:' . $this->domain;

            if (!empty($query) && $this->domain) {
                $query .= ' ' . $domain_query;
            } elseif (empty($query) && $this->domain) {
                $query .= $domain_query;
            }
        }

        // if isset "cat"
        if ($this->cat) {
            $query .= ' cat:' . ($this->cat + 9000000);
        }

        // if isset "theme"
        if ($this->theme) {
            $query .= ' cat:' . ($this->theme + 4000000);
        }

        // if isset "geo"
        if ($this->geo) {
            $query .= ' cat:' . ($this->geo + 11000000);
        }

        $xml->addChild('query', $query);
        $xml->addChild('page', $this->page);
        $groupings = $xml->addChild('groupings');
        $groupby = $groupings->addChild('groupby');
        $groupby->addAttribute('attr', $this->groupBy);
        $groupby->addAttribute('mode', $this->groupByMode);
        $groupby->addAttribute('groups-on-page', $this->limit);
        $groupby->addAttribute('docs-in-group', 1);

        $xml->addChild('sortby', $this->sortBy);
        $xml->addChild('maxpassages', $this->options['maxpassages']);
        $xml->addChild('max-title-length', $this->options['max-title-length']);
        $xml->addChild('max-headline-length', $this->options['max-headline-length']);
        $xml->addChild('max-passage-length', $this->options['max-passage-length']);
        $xml->addChild('max-text-length', $this->options['max-text-length']);

        $this->request = $xml;

        $ch = curl_init();

        $url = $this->getBaseUrl()
            . '?user=' . $this->user
            . '&key=' . $this->key;

        if ($this->lr) {
            $url .= '&lr=' . $this->lr;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/xml"]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/xml"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->asXML());
        curl_setopt($ch, CURLOPT_POST, true);

        if (!empty($this->proxy['host'])) {
            $this->applyProxy($ch);
        }

        $data = curl_exec($ch);

        $this->response = new \SimpleXMLElement($data);
        $this->response = $this->response->response;

        $this->checkErrors();
        $this->bindData();

        return $this;
    }

    /**
     * Get last request as string
     *
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * check response errors
     * @throws YandexXmlException
     */
    protected function checkErrors(): void
    {
        if (isset($this->response->error)) {
            $code = (int)$this->response->error->attributes()->code[0];
            throw new YandexXmlException(YandexXmlException::solveMessage($code, $this->response->error), $code);
        }
    }

    /**
     * bindData
     *
     * @return void
     */
    protected function bindData(): void
    {
        $wordstat = explode(",", $this->response->wordstat);
        $this->wordstat = [];
        if (empty((array)$this->response->wordstat)) {
            return;
        }
        foreach ($wordstat as $word) {
            [$word, $count] = explode(":", $word);
            $this->wordstat[$word] = (int)trim($count);
        }
    }

    /**
     * get total results
     */
    public function getTotal(): int
    {
        if ($this->total === null) {
            $res = $this->response->xpath('found[attribute::priority="all"]');
            $this->total = (int)$res[0];
        }

        return $this->total;
    }

    /**
     * get total results in human form
     */
    public function getTotalHuman(): string
    {
        if ($this->totalHuman === null) {
            $res = $this->response->xpath('found-human');
            $this->totalHuman = $res[0];
        }

        return $this->totalHuman;
    }

    /**
     * get total pages
     */
    public function getPages(): int
    {
        if (empty($this->pages)) {
            $this->pages = ceil($this->getTotal() / $this->limit);
        }

        return $this->pages;
    }

    /**
     * return associated array of groups
     */
    public function getResults(): array
    {
        $this->results = [];
        if ($this->response) {
            foreach ($this->response->results->grouping->group as $group) {
                $res = new \stdClass();
                $res->url = (string)$group->doc->url;
                $res->domain = (string)$group->doc->domain;
                $res->title = isset($group->doc->title) ? self::highlight($group->doc->title) : $res->url;
                $res->headline = isset($group->doc->headline) ? self::highlight($group->doc->headline) : null;
                $res->passages = isset($group->doc->passages->passage) ? self::highlight($group->doc->passages) : null;
                $res->sitelinks = isset($group->doc->snippets->sitelinks->link) ? self::highlight(
                    $group->doc->snippets->sitelinks->link
                ) : null;

                $this->results[] = $res;
            }
        }

        return $this->results;
    }

    /**
     * Return pagebar array
     *
     * @return array
     */
    public function getPageBar(): array
    {
        // FIXME: not good
        $pages = $this->getPages();

        $pagebar = [];

        if ($pages < 10) {
            $pagebar = array_fill(0, $pages, ['type' => 'link', 'text' => '%d']);
            $pagebar[$this->page] = ['type' => 'current', 'text' => '<b>%d</b>'];
        } elseif ($pages >= 10 && $this->page < 9) {
            $pagebar = array_fill(0, 10, ['type' => 'link', 'text' => '%d']);
            $pagebar[$this->page] = ['type' => 'current', 'text' => '<b>%d</b>'];
        } elseif ($pages >= 10 && $this->page >= 9) {
            $pagebar = array_fill(0, 2, ['type' => 'link', 'text' => '%d']);
            $pagebar[] = ['type' => 'text', 'text' => '..'];
            $pagebar += array_fill($this->page - 2, 2, ['type' => 'link', 'text' => '%d']);
            if ($pages > ($this->page + 2)) {
                $pagebar += array_fill($this->page, 2, ['type' => 'link', 'text' => '%d']);
            }
            $pagebar[$this->page] = ['type' => 'current', 'text' => '<b>%d</b>'];
        }

        return $pagebar;
    }

    /**
     * Highlight text
     *
     * @param \simpleXMLElement $xml
     *
     * @return string
     */
    public static function highlight(simpleXMLElement $xml): string
    {
        // FIXME: very strangely method
        $text = $xml->asXML();

        $text = str_replace('<hlword>', '<strong>', $text);
        $text = str_replace('</hlword>', '</strong>', $text);
        $text = strip_tags($text, '<strong>');

        return $text;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
