<?php

    /**
     * Pagination
     * 
     * Supplies an API for setting pagination details, and renders the resulting
     * pagination markup (html) through the included render.inc.php file.
     * 
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
         * _variables
         * 
         * Sets default variables for the rendering of the pagination markup.
         * 
         * @var    array
         * @access protected
         */
        protected $_variables = array(
            'classes' => array('pagination'),
            'crumbs' => 5,
            'rpp' => 10,
            'key' => 'page',
            'target' => '',
            'next' => 'Next &raquo;',
            'previous' => '&laquo; Previous',
            'clean' => false
        );

        /**
         * __construct
         * 
         * @access public
         * @param  integer $current (default: null)
         * @param  integer $total (default: null)
         * @return void
         */
        public function __construct($current = null, $total = null)
        {
            // current instantiation setting
            if (!is_null($current)) {
                $this->setCurrent($current);
            }

            // total instantiation setting
            if (!is_null($total)) {
                $this->setTotal($total);
            }

            // encoded get parameters
            $this->_variables['get'] = $this->_encode($_GET);
        }

        /**
         * _check
         * 
         * Checks the current (page) and total (records) parameters to ensure
         * they've been set. Throws an exception otherwise.
         * 
         * @access protected
         * @return void
         */
        protected function _check()
        {
            if (!isset($this->_variables['current'])) {
                throw new Exception('Pagination::current must be set.');
            } elseif (!isset($this->_variables['total'])) {
                throw new Exception('Pagination::total must be set.');
            }
        }

        /**
         * _encode
         * 
         * @access protected
         * @param  mixed $mixed
         * @return array
         */
        protected function _encode($mixed)
        {
            if (is_array($mixed)) {
                foreach ($mixed as $key => $value) {
                    $mixed[$key] = $this->_encode($value);
                }
                return $mixed;
            }
            return htmlentities($mixed, ENT_QUOTES, 'UTF-8');
        }

        /**
         * addClasses
         * 
         * Sets the classes to be added to the pagination div node.
         * Useful with Twitter Bootstrap (eg. pagination-centered, etc.)
         * 
         * @see    <http://twitter.github.com/bootstrap/components.html#pagination>
         * @access public
         * @param  mixed $classes
         * @return void
         */
        public function addClasses($classes)
        {
            $this->_variables['classes'] = array_merge(
                $this->_variables['classes'],
                (array) $classes
            );
        }

        /**
         * parse
         * 
         * Parses the pagination markup based on the parameters set and the
         * logic found in the render.inc.php file.
         * 
         * @access public
         * @return void
         */
        public function parse()
        {
            // ensure required parameters were set
            $this->_check();

            // bring variables forward
            foreach ($this->_variables as $_name => $_value) {
                $$_name = $_value;
            }

            // buffer handling
            ob_start();
            include 'render.inc.php';
            $_response = ob_get_contents();
            ob_end_clean();
            return $_response;
        }

        /**
         * setClasses
         * 
         * @see    <http://twitter.github.com/bootstrap/components.html#pagination>
         * @access public
         * @param  mixed $classes
         * @return void
         */
        public function setClasses($classes)
        {
            $this->_variables['classes'] = (array) $classes;
        }

        /**
         * setClean
         * 
         * Sets the pagination to exclude page numbers, and only output
         * previous/next markup. The counter-method of this is self::setFull.
         * 
         * @access public
         * @return void
         */
        public function setClean()
        {
            $this->_variables['clean'] = true;
        }

        /**
         * setCrumbs
         * 
         * Sets the maximum number of 'crumbs' (eg. numerical page items)
         * available.
         * 
         * @access public
         * @param  integer $crumbs
         * @return void
         */
        public function setCrumbs($crumbs)
        {
            $this->_variables['crumbs'] = $crumbs;
        }

        /**
         * setCurrent
         * 
         * Sets the current page being viewed.
         * 
         * @access public
         * @param  integer $current
         * @return void
         */
        public function setCurrent($current)
        {
            $this->_variables['current'] = $current;
        }

        /**
         * setFull
         * 
         * See self::setClean for documentation.
         * 
         * @access public
         * @return void
         */
        public function setFull()
        {
            $this->_variables['clean'] = false;
        }

        /**
         * setKey
         * 
         * Sets the key of the <_GET> array that contains, and ought to contain,
         * paging information (eg. which page is being viewed).
         * 
         * @access public
         * @param  string $key
         * @return void
         */
        public function setKey($key)
        {
            $this->_variables['key'] = $key;
        }

        /**
         * setNext
         * 
         * Sets the copy of the next anchor.
         * 
         * @access public
         * @param  string $str
         * @return void
         */
        public function setNext($str)
        {
            $this->_variables['next'] = $str;
        }

        /**
         * setPrevious
         * 
         * Sets the copy of the previous anchor.
         * 
         * @access public
         * @param  string $str
         * @return void
         */
        public function setPrevious($str)
        {
            $this->_variables['previous'] = $str;
        }

        /**
         * setRPP
         * 
         * Sets the number of records per page (used for determining total
         * number of pages).
         * 
         * @access public
         * @param  integer $rpp
         * @return void
         */
        public function setRPP($rpp)
        {
            $this->_variables['rpp'] = $rpp;
        }

        /**
         * setTarget
         * 
         * Sets the leading path for anchors.
         * 
         * @access public
         * @param  string $target
         * @return void
         */
        public function setTarget($target)
        {
            $this->_variables['target'] = $target;
        }

        /**
         * setTotal
         * 
         * Sets the total number of records available for pagination.
         * 
         * @access public
         * @param  integer $total
         * @return void
         */
        public function setTotal($total)
        {
            $this->_variables['total'] = $total;
        }
    }
