<?php

    /**
     * Pagination class. Supplies an API for setting pagination details, and
     *     renders the resulting pagination markup (html) through the included
     *     render.inc.php file.
     * 
     * @todo add setter parameter type and range checks w/ exceptions
     */
    class Pagination
    {
        /**
         * __variables. Sets default variables for the rendering of the
         *     pagination markup.
         * 
         * @var array
         * @access protected
         */
        protected $__variables = array(
            'crumbs' => 5,
            'rpp' => 10,
            'key' => 'page',
            'target' => '',
            'next' => 'next',
            'previous' => 'previous',
            'clean' => false
        );

        /**
         * __check function. Checks the current (page) and total (records)
         *     parameters to ensure they've been set. Throws an exception
         *     otherwise.
         * 
         * @access private
         * @return void
         */
        private function __check()
        {
            if (!isset($this->__variables['current'])) {
                throw new Exception('Pagination::current must be set.');
            } elseif (!isset($this->__variables['total'])) {
                throw new Exception('Pagination::total must be set.');
            }
        }

        /**
         * __construct function.
         * 
         * @access public
         * @return void
         */
        public function __construct()
        {
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
            $this->__check();

            // bring variables forward
            foreach ($this->__variables as $__name => $__value) {
                $$__name = $__value;
            }

            // buffer handling
            ob_start();
            include 'render.inc.php';
            $__response = ob_get_contents();
            ob_end_clean();
            return $__response;
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
            $this->__variables['clean'] = true;
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
            $this->__variables['crumbs'] = $crumbs;
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
            $this->__variables['current'] = $current;
        }

        /**
         * setFull function. See self::setClean for documentation.
         * 
         * @access public
         * @return void
         */
        public function setFull()
        {
            $this->__variables['clean'] = false;
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
            $this->__variables['key'] = $key;
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
            $this->__variables['next'] = $str;
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
            $this->__variables['previous'] = $str;
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
            $this->__variables['rpp'] = $rpp;
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
            $this->__variables['target'] = $target;
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
            $this->__variables['total'] = $total;
        }
    }

?>

