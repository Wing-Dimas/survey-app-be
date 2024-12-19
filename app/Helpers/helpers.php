<?php

if(!function_exists('flattenError')) {
    /**
     * Flattens a throwable into a string of the form "Message in file.php line 123"
     *
     * @param \Throwable $th
     *
     * @return string
     */
    function flattenError(\Throwable $th) {
        return $th->getMessage() . ' in ' . $th->getFile() . ' line ' . $th->getLine();
    }
}
