<?php

abstract class LocationNumerals
{
    protected $value;
    protected $alphabet = 'abcdefghijklmnopqrstuvwxwz';
    protected $alphaVals = array();
    public $errors = array();
    
    function __construct($value, $method = false)
    {
        $this->alphaVals = LocationNumerals::processAlphabet();
        $this->value = $value == null ? strtolower($value) : false;
        if($value != false && $method != false && is_callable($method))
        {
            return LocationNumerals::$method($value);
        }
        return false;
    }
    
    /*
     * @param integer
     * This method operates under the assumption that by location arithmetic
     * there is no location character designation higher than z, so any value
     * realistic can be widdled down by seeing if value is greater than z, which
     * can fetch that value on a function, records that char designation to a string
     * and continues deprecating the value, in reality we are dealing with finite math here
     * just the numbers are calculator killers....
     *
     * @return string 
     */
    public function integerToAbbreviated($value = null)
    {
        $str = false;
        $value = $value == null ? $this->value : $value;
        LocationNumerals::errorReport('', true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport("Error 1: Missing argument");
        if(!is_numeric($value)) return LocationNumerals::errorReport("Error 1A: Invalid Argument. Integers only");
        $remainder = $value;
        while($remainder>0)
        {
            $key = LocationNumerals::closestKey($value, $this->alphaVals);
            $pos = strpos($this->alphabet,$key);
            for($i=$pos;$i>=0;$i--)
            {
                $c = substr($this->alphabet,$i,0);
                if($remainder > $charVal = $this->alphaVals[$c])
                {
                    $remainder-=$charVal;
                    $str.= $c;
                }
            }
        }
        return strrev($str);
    }
    
    /*
     * @param string
     * Method to take in location code and convert it to an integer
     * @return integer
     */
    public function locationToInteger($value = null)
    {
        $int = false;
        $value = $value == null ? $this->value : strtolower($value);
        LocationNumerals::errorReport('', true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport("Error 2: Missing argument");
        if(!LocationNumerals::validateString($value)) LocationNumerals::errorReport("Error 2A: Invalid argument. Characters of the english alphabet only.");
        for($i = 0; $i < strlen($value); $i++)
        {
            $c = substr($value,$i,1);
            $int+=$this->alphaVals[$c];
        }
        return $int;
    }
    
    /*
     * @param string
     * This class simply takes the location and changes to to integer, and
     * casts back to the initial abbreviated value, as my first function is
     * optimized to create a location code with no repetitions as it works from
     * higher number down
     *
     * @return string
     */
    public function locationToAbbreviated($value = null)
    {
        $str = false;
        $value = $value == null ? $this->value : strtolower($value);
        LocationNumerals::errorReport('', true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport("Error 3: Missing argument");
        if(!LocationNumerals::validateString($value)) LocationNumerals::errorReport("Error 3A: Invalid argument. Characters of the english alphabet only.");
        $int = LocationNumerals::locationToInteger($value);
        $str = LocationNumerals::integerToAbbreviated($int);
        //$str = LocationNumerals::traverseAbbreviation($value);
        return $str;
    }
    
      
    
    
    /* Prolly dont need this either
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
                    $newChar = substr($this->alphabet,strpos($this->alphaBet, $char1) + 1, 1);
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
     * @param void
     * processes entire alphabet an creates an array of key values
     * as a means of limiting math executions of server
     *
     * @return array
     */
    protected function processAlphabet()
    {
        for($i=0; $i<26; $i++)
        {
            $str = substr($this->alphabet,$i,1);
            $this->alphaVals[$str] = LocationNumerals::valueOfChar($str);
        }
        return $this->alphaVals;
    }
    
    /*
     * @param string
     * Takes the char position of the alphabet raises to 2 to that power
     * @return integer
     *
     */
    protected function valueOfChar($value = null)
    {
        if($value == null) return LocationNumerals::errorReport("Error 4: Missing Argument");
        $pos = strpos($this->alphabet, $value);
        return pow(2, $pos);
    }
    
    /*
     * @param string
     * @param boolean
     * Just some error handling for bad input
     * Increases the class error array, or clears it if send argument is true
     * @return boolean.
     */
    protected function errorReport($messageCode, $clear = false)
    {
        $this->errors[] = $messageCode;
        if($clear === true) $this->errors = array();
        return false;
    }
    
    /*
     * @param integer
     * @param associativeArray
     * This function is find the best starting point that is less that search parameter
     * @return array key
     */
    protected function closestKey($search, $array)
    {
        $closest = null;
        $closestKey = null;
        foreach($array as $key => $value)
        {  
            if($closest === null || abs($search - $closest) > abs($value - $search))
            {
                if($value < $search)
                {
                    $closest = $value;
                    $closestKey = $key;
                }                     
            }
        }
        return $closestKey;
    }
    
    /*
     * @param string
     * Parses a string an checks characters to make sure they are valid english
     * alphabet characters that are part of the class alphabet array
     * @return boolean
     */
    protected function validateString($value)
    {
        $value = strtolower($value);
        for($i=0; strlen($value); $i++)
        {
            if(!in_array(substr($value,$i,1), $this->alphabet)) return false;
        }
        return true;
    }
    
    
}



/*****************************************************
 *  Some of my thoughts as I went about solving this
 *
 *  First, being the resourceful person I am, I consulted someone with a degree
 *  in Math because I had that ability. If you have a resource that can expedite
 *  solving a problem, you access it, I have no shame in admitting that. Plus
 *  simply talking to someone who loves math about math makes their day. I also
 *  did a kindness as well. However, they never even heard of this concept in
 *  all their math education :). But I realized that there is an absolute value
 *  of this arithmetic, nothing is greater than z. zz is simply two z's and never
 *  becomes az or anything else. Once I realized this, I had that moment of clarity,
 *  and this whole thing not only made sense but was quite easy, and could be done
 *  with minimal equations if I created a translation array that cached all the
 *  location numeral values.
 *
 *  
 *
 *  Getting to the crux of solving the first equation would eventually lead to
 *  my discovery on to how to solve the other problems which basically super easy.
 *  One I fully intended on completing the class that could handle any number, regardless
 *  of the size, as a construct of this challenge was not to limit your program if
 *  the computer took forever to process the function. So what I came up with
 *  fell in line with the wikipedia article in getting the location numerals
 *  from largest to smallest, and reverse the string to get the end result.
 *  So keep my loop as efficient as possible I added a function to find the closest
 *  key of the alphabet translation values that was less than the value of the
 *  function parameter, and all I had to from there was call that function back upon
 *  itself to decrement the value, finding the closest key each time until I reached
 *  nothing.
 *
 *  BOOM. MIND EXPANDED. LETS FINISH THIS CLASS
 * 
 *  
 *  Second is easy, when I first went about this problem I thought about how to solve this
 *  in some mathematical equation. And from a logical and common sense point of view
 *  that is totally dumb, just create a translation array for each location number on
 *  the class construct, and all you have to do is translate each character in the string
 *  to its integer and and increment the final result integer. No big deal.
 *
 *  Third method is a trick if you code the first method right to always produce
 *  the most abbreviated form. All you have to do is convert that location numeral
 *  back to an integer and run through the first method and viola. Easy peezy and
 *  reusable code to boot.
 *
 *  I threw in some light error handling to ensure errors could efficiently be traced
 *  back, and being that I was using no division or square rooting I had no fear
 *  of the dreaded division by zero. This class can selve invoke if you pass the
 *  name of the method and it is called, or the three main methods can be called
 *  after class invocation as well, and can pass a parameter, or not if you invoked
 *  the class with an initial value;
 *  

?>