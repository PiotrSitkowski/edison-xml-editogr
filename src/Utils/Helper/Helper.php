<?php
namespace Helper;

class Helper
{

    public static function debug($tbl, $is_exit=true) {
        echo "<pre>";
        print_r($tbl);
        if ($is_exit) exit;
            else echo "</pre>";
    }

    public static function SafeUrlEnc ($string, $separator = '-') {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $string        = mb_strtolower( trim( $string ), 'UTF-8' );
        $tabchars      = Array('ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ź'=>'z','ż'=>'z','&'=>'and',"'"=>'');
        $string        = str_replace( array_keys($tabchars), array_values($tabchars), $string);
        $string        = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string        = preg_replace('/[^a-z0-9]/u', $separator, $string);
        $string        = preg_replace("/[$separator]+/u", $separator, $string);
        return $string;
    }


}