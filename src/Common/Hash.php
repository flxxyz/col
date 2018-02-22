<?php

namespace Col\Common;

/**
 * Class Hash
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.9
 */
class Hash
{
    public static function make($value, $cost = 8)
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, [
            'cost' => $cost,
        ]);

        return $hash;
    }

    public static function check($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    public static function info($hash)
    {
        return password_get_info($hash);
    }
}