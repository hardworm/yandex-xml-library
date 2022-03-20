<?php

namespace hardworm\YandexXml;

use hardworm\YandexXml\Exceptions\YandexXmlException;

/**
 * Class YandexXml for work with Yandex.XML
 *
 * @author   Anton Shevchuk <AntonShevchuk@gmail.com>
 * @author   Mihail Bubnov <bubnov.mihail@gmail.com>
 * @link     http://anton.shevchuk.name
 * @link     http://yandex.hohli.com
 *
 * @package  YandexXml
 */
class YandexXmlClient
{
    /**
     * Base url to service
     */
    private $baseUrl = 'https://yandex.ru/search/xml';

    /**
     * Response
     *
     * @see http://help.yandex.ru/xml/?id=362990
     * @var \SimpleXMLElement
     */
    public $response;

    /**
     * Wordstat array
     *
     * @var array
     */
    public $wordstat = array();

    /**
     * Response in array
     *
     * @var array
     */
    public $results = array();

    /**
     * Total results
     *
     * @var integer
     */
    public $total = null;

    /**
     * Total results in human form
     *
     * @var string
     */
    public $totalHuman = null;

    /**
     * User
     *
     * @var string
     */
    protected $user;

    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * Query
     *
     * @var string
     */
    protected $query;

    /**
     * Request
     *
     * @var string
     */
    protected $request;

    /**
     * Host
     *
     * @var string
     */
    protected $host;

    /**
     * Site
     *
     * @var string
     */
    protected $site;

    /**
     * Domain
     *
     * @var string
     */
    protected $domain;

    /**
     * cat
     *
     * @see http://search.yaca.yandex.ru/cat.c2n
     * @var integer
     */
    protected $cat;

    /**
     * theme
     *
     * @see http://help.yandex.ru/site/?id=1111797
     * @var integer
     */
    protected $theme;

    /**
     * geo
     *
     * @see http://search.yaca.yandex.ru/geo.c2n
     * @var integer
     */
    protected $geo;

    /**
     * lr
     *
     * @var integer
     */
    protected $lr;

    /**
     * Number of page
     *
     * @var integer
     */
    protected $page = 0;

    /**
     * Number of results per page
     *
     * @var integer
     */
    protected $limit = 10;

    /**
     * Sort By   'rlv' || 'tm'
     *
     * @see http://help.yandex.ru/xml/?id=316625#sort
     * @var string
     */
    const SORT_RLV = 'rlv'; // relevation
    const SORT_TM = 'tm';  // time modification

    protected $sortBy = 'rlv';

    /**
     * Group By  '' || 'd'
     *
     * @see http://help.yandex.ru/xml/?id=316625#group
     * @var string
     */
    const GROUP_DEFAULT = '';
    const GROUP_SITE = 'd'; // group by site

    protected $groupBy = '';

    /**
     * Group mode   'flat' || 'deep' || 'wide'
     *
     * @var string
     */
    const GROUP_MODE_FLAT = 'flat';
    const GROUP_MODE_DEEP = 'deep';
    const GROUP_MODE_WIDE = 'wide';

    protected $groupByMode = 'flat';

    /**
     * Options of search
     *
     * @var array
     */
    protected $options = array(
        'maxpassages' => 2,    // from 2 to 5
        'max-title-length' => 160, //
        'max-headline-length' => 160, //
        'max-passage-length' => 160, //
        'max-text-length' => 640, //
    );

    /**
     * Proxy params
     * Default - no proxy
     * @var array
     */
    protected $proxy = array(
        'host' => '',
        'port' => 0,
        'user' => '',
        'pass' => ''
    );

    /**
     * __construct
     *
     * @param  string $user
     * @param  string $key
     * @throws YandexXmlException
     */
    public function __construct($user, $key)
    {
        if (empty($user) or empty($key)) {
            throw new YandexXmlException(YandexXmlException::solveMessage(0));
        }
        $this->user = $user;
        $this->key = $key;
    }

    /**
     * Search query
     *
     * @param  string $query
     * @return YandexXmlClient
     */
    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set query
     *
     * @access  public
     * @param  string $query
     * @return YandexXmlClient
     */
    public function setQuery($query)
    {
        return $this->query($query);
    }

    /**
     * Get query
     *
     * @access  public
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Page
     *
     * @param  integer $page
     * @return YandexXmlClient
     */
    public function page($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set page
     *
     * @param  integer $page
     * @return YandexXmlClient
     */
    public function setPage($page)
    {
        return $this->page($page);
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Limit
     *
     * @param  integer $limit
     * @return YandexXmlClient
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set limit
     *
     * @param  integer $limit
     * @return YandexXmlClient
     */
    public function setLimit($limit)
    {
        return $this->limit($limit);
    }

    /**
     * Get limit
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Host
     *
     * @param  string $host
     * @return YandexXmlClient
     */
    public function host($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set host
     *
     * @param  string $host
     * @return YandexXmlClient
     */
    public function setHost($host)
    {
        return $this->host($host);
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Site
     *
     * @param  string $site
     * @return YandexXmlClient
     */
    public function site($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Set site
     *
     * @param  string $site
     * @return YandexXmlClient
     */
    public function setSite($site)
    {
        return $this->site($site);
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Domain
     *
     * @param  string $domain
     * @return YandexXmlClient
     */
    public function domain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Set domain
     *
     * @param  string $domain
     * @return YandexXmlClient
     */
    public function setDomain($domain)
    {
        return $this->domain($domain);
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Cat
     *
     * @param  integer $cat
     * @return YandexXmlClient
     */
    public function cat($cat)
    {
        $this->cat = $cat;
        return $this;
    }

    /**
     * Set cat
     *
     * @param  integer $cat
     * @return YandexXmlClient
     */
    public function setCat($cat)
    {
        return $this->cat($cat);
    }

    /**
     * Get cat
     *
     * @return integer
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Geo
     *
     * @param  integer $geo
     * @return YandexXmlClient
     */
    public function geo($geo)
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * Set geo
     *
     * @param  integer $geo
     * @return YandexXmlClient
     */
    public function setGeo($geo)
    {
        return $this->geo($geo);
    }

    /**
     * Get geo
     *
     * @return integer
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * Theme
     *
     * @param  integer $theme
     * @return YandexXmlClient
     */
    public function theme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Set theme
     *
     * @param  integer $theme
     * @return YandexXmlClient
     */
    public function setTheme($theme)
    {
        return $this->theme($theme);
    }

    /**
     * Get theme
     *
     * @return integer
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * lr
     *
     * @param  integer $lr
     * @return YandexXmlClient
     */
    public function lr($lr)
    {
        $this->lr = $lr;
        return $this;
    }

    /**
     * Set lr
     *
     * @param  integer $lr
     * @return YandexXmlClient
     */
    public function setLr($lr)
    {
        return $this->lr($lr);
    }

    /**
     * Get lr
     *
     * @return integer
     */
    public function getLr()
    {
        return $this->lr;
    }

    /**
     * Sort by ..
     *
     * @param  string $sortBy
     * @return YandexXmlClient
     */
    public function sortBy($sortBy)
    {
        if ($sortBy == self::SORT_RLV || $sortBy == self::SORT_TM) {
            $this->sortBy = $sortBy;
        }

        return $this;
    }

    /**
     * Set sort by
     *
     * @param  string $sortBy
     * @return YandexXmlClient
     */
    public function setSortBy($sortBy)
    {
        return $this->sortBy($sortBy);
    }

    /**
     * Get sort by
     *
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Setup group by
     *
     * @param  string $groupBy
     * @param  string $mode
     * @return YandexXmlClient
     */
    public function groupBy($groupBy, $mode = self::GROUP_MODE_FLAT)
    {
        if ($groupBy == self::GROUP_DEFAULT || $groupBy == self::GROUP_SITE) {
            $this->groupBy = $groupBy;
            if ($groupBy == self::GROUP_DEFAULT) {
                $this->groupByMode = self::GROUP_MODE_FLAT;
            } else {
                $this->groupByMode = $mode;
            }
        }

        return $this;
    }

    /**
     * Set group by
     *
     * @param  string $groupBy
     * @param  string $mode
     * @return YandexXmlClient
     */
    public function setGroupBy($groupBy, $mode = self::GROUP_MODE_FLAT)
    {
        return $this->groupBy($groupBy, $mode);
    }

    /**
     * Get group by
     *
     * @return string
     */
    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     * Get group by mode
     *
     * @return string
     */
    public function getGroupByMode()
    {
        return $this->groupByMode;
    }

    /**
     * free setter for options
     *
     * @param  string $option
     * @param  mixed $value
     * @return YandexXmlClient
     */
    public function set($option, $value = null)
    {
        return $this->setOption($option, $value);
    }

    /**
     * free setter for options
     *
     * @param  string $option
     * @param  mixed $value
     * @return YandexXmlClient
     */
    public function setOption($option, $value = null)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Set proxy fo request
     *
     * @param  string $host
     * @param  integer $port
     * @param  string $user
     * @param  string $pass
     * @return YandexXmlClient
     */
    public function setProxy($host = '', $port = 80, $user = null, $pass = null)
    {
        $this->proxy = array(
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pass' => $pass,
        );
        return $this;
    }

    /**
     * Apply proxy before each request
     * @param resource $ch
     */
    protected function applyProxy($ch)
    {
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_PROXY => $this->proxy['host'],
                CURLOPT_PROXYPORT => $this->proxy['port'],
                CURLOPT_PROXYUSERPWD => $this->proxy['user'] . ':' . $this->proxy['pass']
            )
        );
    }

    /**
     * send request
     * @throws YandexXmlException
     * @return YandexXmlClient
     */
    public function request()
    {
        if (empty($this->query)
            && empty($this->host)
        ) {
            throw new YandexXmlException(YandexXmlException::solveMessage(2));
        }

        $xml = new \SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><request></request>");

        // add query to request
        $query = $this->query;

        // if isset "host"
        if ($this->host) {
            if (is_array($this->host)) {
                $host_query = '(host:"' . join('" | host:"', $this->host) . '")';
            } else {
                $host_query = 'host:"' . $this->host . '"';
            }

            if (!empty($query) && $this->host) {
                $query .= ' ' . $host_query;
            } elseif (empty($query) && $this->host) {
                $query .= $host_query;
            }
        }

        // if isset "site"
        if ($this->site) {
            if (is_array($this->site)) {
                $site_query = '(site:"' . join('" | site:"', $this->site) . '")';
            } else {
                $site_query = 'site:"' . $this->site . '"';
            }

            if (!empty($query) && $this->site) {
                $query .= ' ' . $site_query;
            } elseif (empty($query) && $this->site) {
                $query .= $site_query;
            }
        }

        // if isset "domain"
        if ($this->domain) {
            if (is_array($this->domain)) {
                $domain_query = '(domain:' . join(' | domain:', $this->domain) . ')';
            } else {
                $domain_query = 'domain:' . $this->domain;
            }
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/xml"));
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
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * check response errors
     * @throws YandexXmlException
     */
    protected function checkErrors()
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
    protected function bindData()
    {
        $wordstat = preg_split('/,/', $this->response->wordstat);
        $this->wordstat = array();
        if (empty((array)$this->response->wordstat)) {
            return;
        }
        foreach ($wordstat as $word) {
            list($word, $count) = preg_split('/:/', $word);
            $this->wordstat[$word] = intval(trim($count));
        }
    }

    /**
     * get total results
     *
     * @return integer
     */
    public function total()
    {
        return $this->getTotal();
    }

    /**
     * get total results
     *
     * @return integer
     */
    public function getTotal()
    {
        if ($this->total === null) {
            $res = $this->response->xpath('found[attribute::priority="all"]');
            $this->total = (int)$res[0];
        }

        return $this->total;
    }

    /**
     * get total results in human form
     *
     * @return string
     */
    public function totalHuman()
    {
        return $this->getTotalHuman();
    }

    /**
     * get total results in human form
     *
     * @return string
     */
    public function getTotalHuman()
    {
        if ($this->totalHuman === null) {
            $res = $this->response->xpath('found-human');
            $this->totalHuman = $res[0];
        }

        return $this->totalHuman;
    }

    /**
     * get total pages
     *
     * @return integer
     */
    public function pages()
    {
        return $this->getPages();
    }

    /**
     * get total pages
     *
     * @return integer
     */
    public function getPages()
    {
        if (empty($this->pages)) {
            $this->pages = ceil($this->getTotal() / $this->limit);
        }

        return $this->pages;
    }

    /**
     * return associated array of groups
     *
     * @return array
     */
    public function results()
    {
        return $this->getResults();
    }

    /**
     * return associated array of groups
     *
     * @return array
     */
    public function getResults()
    {
        $this->results = array();
        if ($this->response) {
            foreach ($this->response->results->grouping->group as $group) {
                $res = new \stdClass();
                $res->url = (string)$group->doc->url;
                $res->domain = (string)$group->doc->domain;
                $res->title = isset($group->doc->title) ? $this->highlight($group->doc->title) : $res->url;
                $res->headline = isset($group->doc->headline) ? $this->highlight($group->doc->headline) : null;
                $res->passages = isset($group->doc->passages->passage) ? $this->highlight($group->doc->passages) : null;
                $res->sitelinks = isset($group->doc->snippets->sitelinks->link) ? $this->highlight(
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
    public function pageBar()
    {
        return $this->getPageBar();
    }

    /**
     * Return pagebar array
     *
     * @return array
     */
    public function getPageBar()
    {
        // FIXME: not good
        $pages = $this->getPages();

        $pagebar = array();

        if ($pages < 10) {
            $pagebar = array_fill(0, $pages, array('type' => 'link', 'text' => '%d'));
            $pagebar[$this->page] = array('type' => 'current', 'text' => '<b>%d</b>');
        } elseif ($pages >= 10 && $this->page < 9) {
            $pagebar = array_fill(0, 10, array('type' => 'link', 'text' => '%d'));
            $pagebar[$this->page] = array('type' => 'current', 'text' => '<b>%d</b>');
        } elseif ($pages >= 10 && $this->page >= 9) {
            $pagebar = array_fill(0, 2, array('type' => 'link', 'text' => '%d'));
            $pagebar[] = array('type' => 'text', 'text' => '..');
            $pagebar += array_fill($this->page - 2, 2, array('type' => 'link', 'text' => '%d'));
            if ($pages > ($this->page + 2)) {
                $pagebar += array_fill($this->page, 2, array('type' => 'link', 'text' => '%d'));
            }
            $pagebar[$this->page] = array('type' => 'current', 'text' => '<b>%d</b>');
        }

        return $pagebar;
    }

    /**
     * Highlight text
     *
     * @param  \simpleXMLElement $xml
     * @return string
     */
    public static function highlight($xml)
    {
        // FIXME: very strangely method
        $text = $xml->asXML();

        $text = str_replace('<hlword>', '<strong>', $text);
        $text = str_replace('</hlword>', '</strong>', $text);
        $text = strip_tags($text, '<strong>');

        return $text;
    }

    /**
     * Set Base URL
     * @param String $baseUrl
     * @return YandexXmlClient
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get Base URL
     * @return String
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
