<?php
    include "base32lib.php";
    /**
     * Generate a 6 digit TOTP in accordance with RFC 6238 and RFC 4226
     * @param string $key Shared secret encoded in Base32
     * @param int $step Time step 
     * @param int $drift Drift of time step
     * @return string 6 digit OTP
    */
    function generateTOTP(string $key, int $drift = 0, int $step = 30): string {
        $hmachash = hash_hmac("sha1", pack("N", 0).pack("N", floor(time()/$step)+$drift), hex2bin(base32_decode($key)), true);
        $offset = ord($hmachash[19])&0xf;

        return str_pad(((ord($hmachash[$offset])& 0x7f) << 24 | ord($hmachash[$offset+1]) << 16 | ord($hmachash[$offset+2]) << 8 | ord($hmachash[$offset+3])) % (pow(10, 6)), 6, "0", STR_PAD_LEFT);
    }

    /** 
     * Verify a TOTP code
     * @param int|string $otp 6 digit OTP supplied by the user
     * @param string $key Shared secret encoded in Base32 
     * @param int $driftMargin Time step margins of the generator
     * @return bool Returns true if the OTP matches within the drift margins, false otherwise
    */
    function verifyTOTP(string|int $otp, string $key, int $driftMargin = 0, int $timeStep = 30): bool {
        if($driftMargin === 0){
            return (strval($otp) === generateTOTP($key, 0, $timeStep));
        }
        for($i = $driftMargin*(-1); $i < $driftMargin; $i++){
            if(strval($otp) === generateTOTP($key, $i, $timeStep)) return true;
        }
        return false;
    }
?>