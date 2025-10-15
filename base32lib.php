<?php
    /**
     * Convert hexadecimal string to binary string
     * @param string $inp Hexadecimal String
     * @return string Supplied string in binary
     */
    function hexToBin(string $inp): string {
        $res = "";
        foreach(str_split($inp) as $char){
            $res .= str_pad(base_convert($char, 16, 2), 4, "0", STR_PAD_LEFT);
        }
        return $res;
    }

    /**
     * Convert binary string to hexadecimal string
     * @param string $inp Binary String
     * @return string Supplied string in hexadecimal
     */
    function binToHex(string $inp): string {
        $res = "";
        foreach(str_split($inp, 4) as $nibble){
            $res .= base_convert($nibble, 2, 16);
        }
        return $res;
    }
    /**
     * Encode a hexadecimal string to base32
     * @param string $hex Hexadecimal string to be encoded
     * @return string Base32 encoded string
     */
    function base32_encode($hex): string {
        $key = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ234567");
        $pad = "=";
        $val = str_split(hexToBin($hex), 40);
        $res = "";
        
        foreach($val as $quantum){
            $quantumEncoded = "";
            strlen($quantum)%8 != 0 ? $quantum = str_repeat("0", 8-strlen($quantum)%8).$quantum : null;
            foreach(str_split($quantum, 5) as $char){
                $quantumEncoded .= $key[bindec($char)];
            }
            switch (strlen($quantum)) {
                case 32:
                    $quantumEncoded .= str_repeat($pad, 1);
                break;

                case 24:
                    $quantumEncoded .= str_repeat($pad, 3);
                break;

                case 16:
                    $quantumEncoded .= str_repeat($pad, 4);
                break;

                case 8:
                    $quantumEncoded .= str_repeat($pad, 6);
                break;
            }

            $res .= $quantumEncoded;
        }
        return $res;
    }
    /**
     * Decode a base32 encoded string to hexadecimal
     * @param string $base32 Base32 encoded string to be decoded
     * @return string Decoded hexadecimal string
     */
    function base32_decode(string $base32): string {
        $key = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ234567");
        $pad = "=";
        $res = "";

        foreach(str_split(str_replace($pad, "", $base32), 8) as $quantum){
            $bits = str_split($quantum);
            $octet = "";
            foreach($bits as $bit){
                $octet .= str_pad(strval(decbin(array_search($bit, $key))), 5, "0", STR_PAD_LEFT);
            }
            $res .= $octet;
        }

        return binToHex($res);
    }
?>