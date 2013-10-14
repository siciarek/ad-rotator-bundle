<?php

namespace Siciarek\AdRotatorBundle\Utils;

class Json
{
    /**
     * Beautifuler do JSONa, jeżeli string JSON ma niepoprawną składnię, rzuca wyjątek
     * @param string $json String zawierający dane w formacie JSON
     * @param string $sep separator wcinający dane z lewej strony domyślnie 4 spacje
     * @return string
     *
     * Użycie:
     * echo Json::format($json);
     */
    public static function format($json = null, $sep = '    ')
    {
        $temp = json_encode(json_decode($json, true));

        if ($temp == 'null' and $json != null)
        {
            throw new \Exception('Incorrect Json data');
        }

        $temp = preg_replace(array('/([,\{\[])/', '/([\]\}])/', '/(":)/'), array("$1\n", "\n$1", "$1 "), $temp);

        $offset = 0;
        $result = '';

        $temparr = explode("\n", $temp);

        foreach($temparr as $line)
        {
            $line = trim($line);
            if (preg_match('/[\]\}],?$/', $line)) $offset--;
            $result .= str_repeat($sep, $offset) . $line . "\n";
            if (preg_match('/[\[\{]$/', $line) > 0) $offset++;
        }

        $result = preg_replace('/\[\s*\]/', '[]', $result);
        $result = preg_replace('/\{\s*\}/', '{}', $result);
        return $result;
    }
}
