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
     *     require_once APP . '/vendors/PHP-Pagination/Pagination.class.php';
     *     $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
     *     $pagination = (new Pagination($page, 200));
     *     $markup = $pagination->parse();
     * </code>
     * @example
     * <code>
     *     require_once APP . '/vendors/PHP-Pagination/Pagination.class.php';
     *     $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
     *     $pagination = (new Pagination());
     *     $pagination->setCurrent($page);
     *     $pagination->setTotal(200);
     *     $markup = $pagination->parse();
     * </code>
     */
    class Pagination
    {
        /**
         * _variables. Sets default variables for the rendering of the
         *     pagination markup.
         * 
         * @var array
         * @access protected
         */
        protected $_variables = array(
            'crumbs' => 5,
            'rpp' => 10,
            'key' => 'page',
            'target' => '',
            'next' => 'next',
            'previous' => 'previous',
            'clean' => false
        );

        /**
         * __construct function.
         * 
         * @access public
         * @param integer $current (default: null)
         * @param integer $total (default: null)
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
         * _check function. Checks the current (page) and total (records)
         *     parameters to ensure they've been set. Throws an exception
         *     otherwise.
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
         * _encode function.
         * 
         * @access protected
         * @param mixed $mixed
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
         * parse function. Parses the pagination markup based on the parameters
         *     set and the logic found in the render.inc.php file.
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
         * setClean function. Sets the pagination to exclude page numbers, and
         *     only output previous/next markup. The counter-method of this is
         *     self::setFull.
         * 
         * @access public
         * @return void
         */
        public function setClean()
        {
            $this->_variables['clean'] = true;
        }

        /**
         * setCrumbs function. Sets the maximum number of 'crumbs' (eg.
         *     numerical page items) available.
         * 
         * @access public
         * @param int $crumbs
         * @return void
         */
        public function setCrumbs($crumbs)
        {
            $this->_variables['crumbs'] = $crumbs;
        }

        /**
         * setCurrent function. Sets the current page being viewed.
         * 
         * @access public
         * @param int $current
         * @return void
         */
        public function setCurrent($current)
        {
            $this->_variables['current'] = $current;
        }

        /**
         * setFull function. See self::setClean for documentation.
         * 
         * @access public
         * @return void
         */
        public function setFull()
        {
            $this->_variables['clean'] = false;
        }

        /**
         * setKey function. Sets the key of the _GET array that contains, and
         *     ought to contain, paging information.
         * 
         * @access public
         * @param string $key
         * @return void
         */
        public function setKey($key)
        {
            $this->_variables['key'] = $key;
        }

        /**
         * setNext function. Sets the copy of the next anchor.
         * 
         * @access public
         * @param string $str
         * @return void
         */
        public function setNext($str)
        {
            $this->_variables['next'] = $str;
        }

        /**
         * setPrevious function. Sets the copy of the previous anchor.
         * 
         * @access public
         * @param string $str
         * @return void
         */
        public function setPrevious($str)
        {
            $this->_variables['previous'] = $str;
        }

        /**
         * setRPP function. Sets the number of records per page (used for
         *     determining total page counts).
         * 
         * @access public
         * @param int $rpp
         * @return void
         */
        public function setRPP($rpp)
        {
            $this->_variables['rpp'] = $rpp;
        }

        /**
         * setTarget function. Sets the leading path for anchors.
         * 
         * @access public
         * @param string $target
         * @return void
         */
        public function setTarget($target)
        {
            $this->_variables['target'] = $target;
        }

        /**
         * setTotal function. Sets the total number of records available for
         *     pagination.
         * 
         * @access public
         * @param int $total
         * @return void
         */
        public function setTotal($total)
        {
            $this->_variables['total'] = $total;
        }
    }
