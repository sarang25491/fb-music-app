<?php
#################################################################
#            BURST DEVELOPMENT DISCLAIMER NOTICE                #
# This project has been secured and copyrighted, do not attempt #
# to copy or publish this project without written approval      #
# from a company executive. Any violoations are punishable by   #
# law and the individual must pay and repair for all damages.   #
#																#
#         Developed by Steven Lu <slu@burst-dev.com>            #
#################################################################
?>

<?php
class encryption_class {

    var $scramble1;     // 1st string of ASCII characters
    var $scramble2;     // 2nd string of ASCII characters

    var $errors;        // array of error messages
    var $adj;           // 1st adjustment value (optional)
    var $mod;           // 2nd adjustment value (optional)

    function encryption_class ()
    {
        $this->errors = array();

        $this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
        $this->scramble2 = 'kJ3emNjL05KoHgatqr6382ynvhgdgUFGRGufgwuYGyufdlugaggGUIFIUGDFSG83Uljagflgui38dglG8I23HUAGLlga'; // change this to  whatever you like

        if (strlen($this->scramble1) <> strlen($this->scramble2)) {
            trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
        } // if

        $this->adj = 1.75;
        $this->mod = 3;

    }

    function decrypt ($key, $source)
    {
        $this->errors = array();

        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for decryption';
            return;
        } 

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a character from $source
            $char2 = substr($source, $i, 1);

            // identify its position in $scramble2
            $num2 = strpos($this->scramble2, $char2);
            if ($num2 === false) {
                $this->errors[] = "Source string contains an invalid character ($char2)";
                return;
            } 

            $adj     = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;
            $num1    = $num2 - round($factor1);
            $num1    = $this->_checkRange($num1);
            $factor2 = $factor1 + $num2;

            $char1 = substr($this->scramble1, $num1, 1);

            $target .= $char1;

        } 

        return rtrim($target);

    }

    function encrypt ($key, $source, $sourcelen = 0)
    {
        $this->errors = array();

        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for encryption';
            return;
        }

        while (strlen($source) < $sourcelen) {
            $source .= ' ';
        }

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            $char1 = substr($source, $i, 1);

            $num1 = strpos($this->scramble1, $char1);
            if ($num1 === false) {
                $this->errors[] = "Source string contains an invalid character ($char1)";
                return;
            }

            $adj     = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;
            $num2    = round($factor1) + $num1;
            $num2    = $this->_checkRange($num2);
            $factor2 = $factor1 + $num2;

            $char2 = substr($this->scramble2, $num2, 1);

            $target .= $char2;

        }

        return $target;

    }

    function getAdjustment ()

    {
        return $this->adj;

    }


    function getModulus ()

    {
        return $this->mod;

    }


    function setAdjustment ($adj)

    {
        $this->adj = (float)$adj;

    }

    function setModulus ($mod)
    {
        $this->mod = (int)abs($mod);

    }


    function _applyFudgeFactor (&$fudgefactor)
    {
        $fudge = array_shift($fudgefactor);
        $fudge = $fudge + $this->adj;
        $fudgefactor[] = $fudge;

        if (!empty($this->mod)) {
            if ($fudge % $this->mod == 0) {
                $fudge = $fudge * -1;
            }
        }

        return $fudge;

    }

    function _checkRange ($num)
    {
        $num = round($num);

        $limit = strlen($this->scramble1);

        while ($num >= $limit) {
            $num = $num - $limit;
        } // while
        while ($num < 0) {
            $num = $num + $limit;
        }

        return $num;

    }

    function _convertKey ($key)
    {
        if (empty($key)) {
            $this->errors[] = 'No value has been supplied for the encryption key';
            return;
        }

        $array[] = strlen($key);

        $tot = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            $char = substr($key, $i, 1);

            $num = strpos($this->scramble1, $char);
            if ($num === false) {
                $this->errors[] = "Key contains an invalid character ($char)";
                return;
            }

            $array[] = $num;
            $tot = $tot + $num;
        }

        $array[] = $tot;

        return $array;

    }

}
?>