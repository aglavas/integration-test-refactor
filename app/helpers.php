<?php

/**
 * Strpos for multiple strings
 *
 * @param $string
 * @param $check
 * @param bool $getResults
 * @return array|bool|int
 */
function multi_strpos($string, $check, $getResults = false)
{
    $result = array();
    $check = (array) $check;

    foreach ($check as $s)
    {
        $pos = strpos($string, $s);

        if ($pos !== false)
        {
            if ($getResults)
            {
                $result[$s] = $pos;
            }
            else
            {
                return $pos;
            }
        }
    }

    return empty($result) ? false : $result;
}
