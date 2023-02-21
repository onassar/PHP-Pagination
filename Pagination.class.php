<?php

/**
 * Pagination
 *
 * Supplies an API for setting pagination details, and renders the resulting
 * pagination markup (html) through the included render.inc.php file.
 *
 * @note    The SEO methods (canonical/rel) were written following Google's
 *          suggested patterns. Namely, the canoical url excludes any
 *          peripheral parameters that don't relate to the pagination
 *          series. Whereas the prev/next rel link tags include any params
 *          found in the request.
 * @link    https://github.com/onassar/PHP-Pagination
 * @author  Oliver Nassar <onassar@gmail.com>
 * @todo    add setter parameter type and range checks w/ exceptions
 * @example
 * <code>
 *     // source inclusion
 *     require_once APP . '/vendors/PHP-Pagination/Pagination.class.php';
 *
 *     // determine page (based on <_GET>)
 *     $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
 *
 *     // instantiate with page and records as constructor parameters
 *     $pagination = (new Pagination($page, 200));
 *     $markup = $pagination->parse();
 * </code>
 * @example
 * <code>
 *     // source inclusion
 *     require_once APP . '/vendors/PHP-Pagination/Pagination.class.php';
 *
 *     // determine page (based on <_GET>)
 *     $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
 *
 *     // instantiate; set current page; set number of records
 *     $pagination = (new Pagination());
 *     $pagination->setCurrent($page);
 *     $pagination->setTotal(200);
 *
 *     // grab rendered/parsed pagination markup
 *     $markup = $pagination->parse();
 * </code>
 */
class Pagination
{
    /**
     * Sets default variables for the rendering of the pagination markup.
     */
    protected $_variables = array(
        'classes' => array('clearfix', 'pagination'),
        'crumbs' => 5,
        'rpp' => 10,
        'key' => 'page',
        'target' => '',
        'next' => 'Next &raquo;',
        'previous' => '&laquo; Previous',
        'alwaysShowPagination' => false,
        'clean' => false
    );

    public function __construct(int $current = null, int $total = null)
    {
        // current instantiation setting
        if (is_null($current) === false) {
            $this->setCurrent($current);
        }

        // total instantiation setting
        if (is_null($total) === false) {
            $this->setTotal($total);
        }

        // Pass along get (for link generation)
        $this->_variables['get'] = $_GET;
    }

    /**
     * Checks the current (page) and total (records) parameters to ensure
     * they've been set. Throws an exception otherwise.
     *
     * @access  protected
     * @return  void
     */
    protected function _check()
    {
        if (isset($this->_variables['current']) === false) {
            throw new RuntimeException('Pagination::current must be set.');
        } elseif (isset($this->_variables['total']) === false) {
            throw new RuntimeException('Pagination::total must be set.');
        }
    }

    /**
     * Sets the classes to be added to the pagination div node.
     * Useful with Twitter Bootstrap (eg. pagination-centered, etc.)
     *
     * @see http://twitter.github.com/bootstrap/components.html#pagination
     * @param mixed $classes
     * @return void
     */
    public function addClasses($classes): void
    {
        $this->_variables['classes'] = array_merge(
            $this->_variables['classes'],
            (array)$classes
        );
    }

    /**
     * Tells the rendering engine to show the pagination links even if there
     * aren't any pages to paginate through.
     *
     * @access  public
     * @return  void
     */
    public function alwaysShowPagination()
    {
        $this->_variables['alwaysShowPagination'] = true;
    }

    public function getCanonicalUrl(): string
    {
        $target = $this->_variables['target'];
        if (empty($target) === true) {
            $target = $_SERVER['PHP_SELF'];
        }
        $page = (int)$this->_variables['current'];
        if ($page !== 1) {
            return $this->getProtocol() . $_SERVER['HTTP_HOST'] . $target . $this->getPageParam();
        }
        return $this->getProtocol() . $_SERVER['HTTP_HOST'] . $target;
    }

    public function getPageParam(?int $page = null): string
    {
        if ($page === null) {
            $page = (int)$this->_variables['current'];
        }
        $key = $this->_variables['key'];
        return '?' . ($key) . '=' . ((int)$page);
    }

    /**
     * @see https://www.designcise.com/web/tutorial/how-to-check-for-https-request-in-php
     */
    private function getProtocol(): string
    {
        $isHttps =
            $_SERVER['HTTPS']
            ?? $_SERVER['REQUEST_SCHEME']
            ?? $_SERVER['HTTP_X_FORWARDED_PROTO']
            ?? null;

        $isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);

        return ($isHttps ? 'https' : 'http') . '://';
    }

    public function getPageUrl(?int $page = null): string
    {
        $target = $this->_variables['target'];
        if (empty($target) === true) {
            $target = $_SERVER['PHP_SELF'];
        }
        return $this->getProtocol() . $_SERVER['HTTP_HOST'] . $target . $this->getPageParam($page);
    }

    /**
     * @see     http://support.google.com/webmasters/bin/answer.py?hl=en&answer=1663744
     * @see     http://googlewebmastercentral.blogspot.ca/2011/09/pagination-with-relnext-and-relprev.html
     * @see     http://support.google.com/webmasters/bin/answer.py?hl=en&answer=139394
     * @access  public
     * @return  array
     */
    public function getRelPrevNextLinkTags(): array
    {
        // generate path
        $target = $this->_variables['target'];
        if (empty($target) === true) {
            $target = $_SERVER['PHP_SELF'];
        }
        $key = $this->_variables['key'];
        $params = $this->_variables['get'];
        $params[$key] = 'pgnmbr';
        $href = ($target) . '?' . http_build_query($params);
        $href = preg_replace(
            array('/=$/', '/=&/'),
            array('', '&'),
            $href
        );
        $href = $this->getProtocol() . $_SERVER['HTTP_HOST'] . $href;

        // Pages
        $currentPage = (int)$this->_variables['current'];
        $numberOfPages = (int)ceil($this->_variables['total'] / $this->_variables['rpp']);

        // On first page
        if ($currentPage === 1) {
            // There is a page after this one
            if ($numberOfPages > 1) {
                $href = str_replace('pgnmbr', 2, $href);
                return array(
                    '<link rel="next" href="' . ($href) . '" />'
                );
            }

            return array();
        }

        // Store em
        $prevNextTags = array(
            '<link rel="prev" href="' . (str_replace('pgnmbr', $currentPage - 1, $href)) . '" />'
        );

        // There is a page after this one
        if ($numberOfPages > $currentPage) {
            $prevNextTags[] = '<link rel="next" href="' . (str_replace('pgnmbr', $currentPage + 1, $href)) . '" />';
        }

        return $prevNextTags;
    }

    /**
     * Render the pagination markup based on the parameters set and the
     * logic found in the render.inc.php file.
     *
     * @return string
     */
    public function render(): string
    {
        // ensure required parameters were set
        $this->_check();

        // buffer handling
        ob_start();
        include_once(__DIR__ . '/render.inc.php');
        renderPaginatorHTML(
            $this->_variables['total'],
            $this->_variables['rpp'],
            $this->_variables['current'],
            $this->_variables['alwaysShowPagination'],
            $this->_variables['classes'],
            $this->_variables['get'],
            $this->_variables['key'],
            $this->_variables['target'],
            $this->_variables['previous'],
            $this->_variables['clean'],
            $this->_variables['crumbs'],
            $this->_variables['next']
        );
        $_response = ob_get_contents();
        ob_end_clean();

        if ($_response === false) {
            throw new RuntimeException("Cannot render the paginator HTML.");
        }

        return $_response;
    }

    /**
     * @see http://twitter.github.com/bootstrap/components.html#pagination
     * @param mixed $classes
     * @return void
     */
    public function setClasses($classes): void
    {
        $this->_variables['classes'] = (array)$classes;
    }

    /**
     * Sets the pagination to exclude page numbers, and only output
     * previous/next markup. The counter-method of this is self::setFull.
     *
     * @return void
     */
    public function setClean(): void
    {
        $this->_variables['clean'] = true;
    }

    /**
     * Sets the maximum number of 'crumbs' (eg. numerical page items)
     * available.
     *
     * @param int $crumbs
     * @return void
     */
    public function setCrumbs(int $crumbs): void
    {
        $this->_variables['crumbs'] = $crumbs;
    }

    /**
     * Sets the current page being viewed.
     *
     * @param int $current
     * @return void
     */
    public function setCurrent(int $current): void
    {
        $this->_variables['current'] = $current;
    }

    /**
     * See self::setClean for documentation.
     *
     * @return void
     */
    public function setFull(): void
    {
        $this->_variables['clean'] = false;
    }

    /**
     * Sets the key of the <_GET> array that contains, and ought to contain,
     * paging information (eg. which page is being viewed).
     *
     * @param string $key
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->_variables['key'] = $key;
    }

    /**
     * Sets the copy of the next anchor.
     *
     * @param string $str
     * @return void
     */
    public function setNext(string $str): void
    {
        $this->_variables['next'] = $str;
    }

    /**
     * Sets the copy of the previous anchor.
     *
     * @param string $str
     * @return void
     */
    public function setPrevious(string $str): void
    {
        $this->_variables['previous'] = $str;
    }

    /**
     * Sets the number of results per page (used for determining total
     * number of pages).
     *
     * @param int $resultPerPage
     * @return void
     */
    public function setResultPerPage(int $resultPerPage): void
    {
        $this->_variables['rpp'] = $resultPerPage;
    }

    /**
     * Sets the leading path for anchors.
     *
     * @param string $target
     * @return void
     */
    public function setTarget(string $target): void
    {
        $this->_variables['target'] = $target;
    }

    /**
     * Sets the total number of records available for pagination.
     *
     * @param integer $total
     * @return void
     */
    public function setTotal(int $total): void
    {
        $this->_variables['total'] = $total;
    }

    /**
     * Gets the total number of records available for pagination.
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->_variables['total'];
    }

}
