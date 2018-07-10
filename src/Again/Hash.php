<?php

namespace Col\Again;

/**
 * Class Hash
 *
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.1
 */
class Hash
{
    public static function make(string $value, int $cost = 8)
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, [
            'cost' => $cost,
        ]);

        return $hash;
    }

    public static function check(string $value, string $hashedValue)
    {
        return mb_strlen($hashedValue) === 0 ? password_verify($value, $hashedValue)
            : false;
    }

    public static function info(string $hash)
    {
        return password_get_info($hash);
    }
}