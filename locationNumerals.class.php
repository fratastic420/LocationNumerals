<?php

abstract class LocationNumerals
{
    protected $value;
    protected $alphaBet = 'abcdefghijklmnopqrstuvwxwz';
    
    function __construct($value, $method = false)
    {
        $this->value = $value == null ? $value : false;
        if($value != false && $method != false)
        {
            return LocationNumerals::$method($value);
        }
        return false;
    }
    
    /*
     * @param integer
     * This method will invoke the main method 
     *
     * @return string 
     */
    public function integerToAbbreviated($value = null)
    {
        $str = false;
        $value = $value == null ? $this->value : $value;
        // loop through integers to widdle down -> start with z's work down !===== I think I got this
        $remainder = false;
        
        
        $str = LocationNumerals::traverseAbbreviation($str);
        return $str;
    }
    
    /*
     * @param string
     * Method to take in location code and convert it to an integer
     * @return integer
     */
    public function locationToInteger($value = null)
    {
        $int = false;
        $value = $value == null ? $this->value : $value;
        for($i = 0; $i < strlen($value); $i++)
        {
            $int+=LocationNumerals::valueOfChar(substr($value,$i,1));
        }
        return $int;
    }
    
    /*
     * @param string
     *
     *
     * @return string
     */
    public function locationToAbbreviated($value = null)
    {
        $str = false;
        $value = $value == null ? $this->value : $value;
        $str = LocationNumerals::traverseAbbreviation($value);
        return $str;
    }
    
      
    /*
     * @param integer
     * Returns the highest Power of two for that ad number \
     * Currently will be done via loop, will refactor to more optimal
     * (I know this loop could theoretically could be so large It can kill a server or browser [if in JS])
     * This is super dirty.... :P
     * @return integer
     */ 
    protected function powerofTwo($value = null)
    {
        if($value != null)
        {
            $power = 0;
            $curValue = 0;
            while($curValue < $value)
            {
                $curValue = pow(2,$power);
                $power++;
            }
            return $power;
        }
        return false;
    }
    
    /*
     * @param integer
     *
     * @return string;
     */
    protected function getCharByPower($value = null)
    {
        $char = false;
        
        return $char;
    
    }
    
    /*
     * @param string
     * Super ugly loop to traver a string and continually
     * widdle down the string if two chars next to each other
     * @return string
     */
    protected function traverseAbbreviation($value = null)
    {
        if($value == null) return false;
        $noMatches = false;
        
        while(!$noMatches)
        {
            $str = $value;
            for($i = 1; $i < strlen($value); $i++)
            {
                $char1 = substr($value, ($i - 1), 1);
                $char2 = substr($value, $i, 1);
                if($char1 == $char2)
                {
                    $newChar = substr($this->alphaBet,strpos($this->alphaBet, $char1) + 1, 1);
                    $partOne = $i < 2 ? "" : substr($value, 0, $i);
                    $partTwo = substr($value, $i+1);
                    $str = $partOne . $newChar . $partTwo;
                    exit();
                }
            }
            if($str == $value) $noMatches = true;
            else
            {
                $value = $str;
            }
        }
        return $value;
        
    }
    
    /*
     * @param string
     * Takes string and reverse it
     * @return string
     */
    protected function flipStr($value = null)
    {  
        if($value == null) return false;
        return strrev($value);
    }
    
    /*
     * @param integer
     * @param integer
     *
     * @return 
     *
     */
    protected function reversePow($num, $base)
    {
        return log($num) / log($base);
    }
    
    /*
     * @param string
     * 
     * @return integer
     *
     */
    protected function valueOfChar($value = null)
    {
        $pos = strpos($this->alphaBet, $value);
        return pow(2, $pos);
    }
    
    /*
     *
     * Just some error handling for bad input
     *
     */
    protected function errorReport($messageCode)
    {
        
    }
    
    
}



?>