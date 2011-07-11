<?php

    /**
     * encode function.
     * 
     * @access public
     * @param mixed $mixed
     * @return mixed
     */
    function encode($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = encode($value);
            }
            return $mixed;
        }
        return htmlentities($mixed, ENT_QUOTES, 'UTF-8');
    }

    // total page count calculation
    $pages = ((int) ceil($total / $rpp));

    // encoded get parameters
    $_get = encode($_GET);

    // if it's an invalid page request
    if ($current < 1) {
        return;
    } elseif ($current > $pages) {
        return;
    }

    // if more items than pagination-limit
    if ($total > $rpp) {
?>
<div class="pagination">
    <ul class="clear">
<?php
        /**
         * Previous Link
         */

        // anchor classes and target
        $classes = array();
        $params = $_get;
        $params[$key] = ($current - 1);
        $href = ($target) . '?' . http_build_query($params);
        if ($current === 1) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
        <li class="copy previous"><a href="<?= ($href) ?>" class="<?= implode(' ', $classes) ?>"><?= ($previous) ?></a></li>
<?php
        /**
         * if this isn't a clean output for pagination (eg. show numerical
         * links)
         */
        if (!$clean) {

            /**
             * Calculates the number of leading page crumbs based on the minimum
             *     and maximum possible leading pages.
             */
            $leading = ((int) floor($crumbs / 2));
            for ($x = 0; $x < ((int) floor($crumbs / 2)); ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - ((int) floor($crumbs / 2)); $x < $pages; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $crumbs - ($pages - $x);
                    break;
                }
            }

            // calculate trailing crumb count based on inverse of leading
            $trailing = $crumbs - $leading;

            // print
            for ($x = 0; $x < $leading; ++$x) {

                // class/href setup
                $params = $_get;
                $params[$key] = ($current + $x - $leading);
                $href = ($target) . '?' . http_build_query($params);

?>
        <li class="number"><a href="<?= ($href) ?>"><?= ($current + $x - $leading) ?></a></li>
<?php
            }

            // print current page
?>
        <li class="number"><a href="#" class="current"><?= ($current) ?></a></li>
<?php
            // print
            for ($x = 0; $x < $trailing; ++$x) {

                // class/href setup
                $params = $_get;
                $params[$key] = ($current + $x + 1);
                $href = ($target) . '?' . http_build_query($params);
?>
        <li class="number"><a href="<?= ($href) ?>"><?= ($current + $x + 1) ?></a></li>
<?php
            }
        }

        /**
         * Next Link
         */

        // anchor classes and target
        $classes = array();
        $params = $_get;
        $params[$key] = ($current + 1);
        $href = ($target) . '?' . http_build_query($params);
        if ($current === $pages) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
        <li class="copy next"><a href="<?= ($href) ?>" class="<?= implode(' ', $classes) ?>"><?= ($next) ?></a></li>
    </ul>
</div>
<?php
    }
?>
