<?php
namespace App\Helper;

class Str
{
    const NON_PARAM_RESERVED = [
                            'else',
                            'break',
                            'default',
                        ];

    /**
     * Check the first character of a string based on the given pattern
     * 
     *@param string $pattern
     *@param string $str
     *
     *@return bool
     *
    **/
    public static function startsWith($pattern, $str)
    {
        return $str[0] == $pattern ? true : false;
    }

    /**
     * Check the last character of a string based on the given pattern
     * 
     *@param string $pattern
     *@param string $str
     *
     *@return bool
     *
    **/
    public static function endsWith($pattern, $str)
    {
        return mb_substr($str, -1) == $pattern ? true : false;
    }

    /**
     * Remove the first character of a string
     * 
     *@param string $str
     *
     *@return string
     *
    **/
    public static function removeFirstChar($str)
    {
        return substr($str, 1);
    }

    /**
     * Remove the last character of a string
     * 
     *@param string $str
     *
     *@return string
     *
    **/
    public static function removeLastChar($str)
    {
        return mb_substr($str, 0, -1);
    }

    /**
     * Remove character based on the given start and length
     * 
     *@param string $str
     *
     *@return string
     *
    **/
    public static function removeChar($str, $length, $start = null)
    {
        if ($start !== null) {
            return mb_substr($str, $start, $length);
        }
        return mb_substr($str, $length);
    }

    /**
     * Remove characters based on the given start and length BOTH sides
     * 
     *@param string $content
     *
     *@return string
     *
    **/
    public static function removeBothSides($str, $length)
    {
        $str = self::removeChar($str, $length);
        return self::removeChar($str, -$length, 0);
    }

    /**
     * Search a string pattern
     * 
     *@param string $pattern
     *@param string $str
     *
     *@return bool
     *
    **/
    public static function Has($pattern, $str)
    {
        return preg_match("/{$pattern}*/", $str);
    }

    /**
     * Check if it has a first string portion based on the given pattern
     * 
     *@param string $content
     *
     *@return bool
     *
    **/
    public static function firstStringPortion($pattern, $str)
    {
        return preg_match("/^{$pattern}/", $str);
    }

    /**
     * Check if it has a last string portion based on the given pattern
     * 
     *@param string $pattern
     *@param string $str
     *
     *@return bool
     *
    **/
    public static function lastStringPortion($pattern, $str)
    {
        return preg_match("/{$pattern}+/", $str);
    }

    /**
     * Remove a string portion
     * 
     *@param string $content
     *
     *@return string
     *
    **/
    public function removeStringPortion($pattern, $str)
    {
        return preg_replace("/{$pattern}\s\D+/", "", $str);
    }

    /**
     * Check if the string is a non-parameter reserved word
     * 
     *@param string $str
     *
     *@return bool
     *
    **/
    public function isNonParamReserve($str)
    {
        return in_array($str, self::NON_PARAM_RESERVED);
    }
}


?>