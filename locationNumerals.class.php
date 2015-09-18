<?php

class LocationNumerals
{
    protected $value;
    protected $alphabet = 'abcdefghijklmnopqrstuvwxwz';
    protected $alphaVals = array();
    public $result;
    public $errors = array();
    public $errorCodes = array(
        "No Error: Init Code",
        "Error 1: Missing argument",                       
        "Error 1A: Invalid Argument. Integers only",
        "Error 2: Missing argument",
        "Error 2A: Invalid argument. Characters of the english alphabet only.",
        "Error 3: Missing argument",
        "Error 3A: Invalid argument. Characters of the english alphabet only.",
        "Error 4: Missing Argument"
    );
    
    /*
     * @param mixed
     *
     * Start the class off with a initial value, and method if provided
     * if method is valid it invokes
     *
     * @return boolean
     */
    
    function __construct($value, $method = false)
    {
        $this->alphaVals = LocationNumerals::processAlphabet();
        $this->value = $value == null ? strtolower($value) : false;
        if($value != false && is_callable($method, true, $callable))
        {

             $this->result = LocationNumerals::$method($value);
             return true;
        }
        return false;
    }
    
    /*
     * @param integer
     * 
     * This method operates under the assumption that by location arithmetic
     * there is no location character designation higher than z, so any value
     * realistic can be widdled down by seeing if value is greater than z, which
     * can fetch that value on a function, records that char designation to a string
     * and continues deprecating the value, in reality we are dealing with finite math here
     * just the numbers are calculator killers....
     *
     * @return mixed 
     */
    public function integerToAbbreviated($value = null)
    {
        $str = false;
        $value = $value == null ? $this->value : $value;
        LocationNumerals::errorReport($this->errorCodes[0], true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport($this->errorCodes[1]);
        if(!is_numeric($value)) return LocationNumerals::errorReport($this->errorCodes[2]);
        $remainder = $value;
        $counter = 0;
        while($remainder>0)
        {
            $key = LocationNumerals::closestKey($remainder, $this->alphaVals);
            $remainder = $remainder - $this->alphaVals[$key];
            $str.= $key;
        }
        $this->result = strrev($str);
        return $this->result;
    }
    
    /*
     * @param string
     * 
     * Method to take in location code and convert it to an integer
     * @return integer
     */
    public function locationToInteger($value = null)
    {
        $int = false;
        $value = $value == null ? $this->value : strtolower($value);
        LocationNumerals::errorReport($this->errorCodes[0], true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport($this->errorCodes[3]);
        if(LocationNumerals::validateString($value) === false) return LocationNumerals::errorReport($this->errorCodes[4]);
        for($i = 0; $i < strlen($value); $i++)
        {
            $c = substr($value,$i,1);
            $int+=$this->alphaVals[$c];
        }
        $this->result = $int;
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
        LocationNumerals::errorReport($this->errorCodes[0], true); //reset error codes if being invoked after instantiation
        if($value === false || $value == null) return LocationNumerals::errorReport($this->errorCodes[5]);
        if(LocationNumerals::validateString($value) === false) return LocationNumerals::errorReport($this->errorCodes[6]);
        $int = LocationNumerals::locationToInteger($value);
        $str = LocationNumerals::integerToAbbreviated($int);
        $this->result = $str;
        return $str;
    }
    
       
    /*
     * @param void
     * 
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
     * 
     * Takes the char position of the alphabet raises to 2 to that power
     * @return integer
     *
     */
    protected function valueOfChar($value = null)
    {
        if($value == null) return LocationNumerals::errorReport($this->errorCodes[7]);
        $pos = strpos($this->alphabet, $value);
        return pow(2, $pos);
    }
    
    /*
     * @param string
     * @param boolean
     * 
     * Just some error handling for bad input
     * Increases the class error array, or clears it if send argument is true
     * on increasing messagecode array sets the main class result to false
     * so we can hide the result on error
     * 
     * @return boolean.
     */
    protected function errorReport($messageCode, $clear = false)
    {
        $this->errors[] = $messageCode;
        if($clear === true) $this->errors = array();
        else $this->result = false;
        return false;
    }
    
    /*
     * @param integer
     * @param associativeArray
     * 
     * This function is find the best starting point that is less that search parameter
     * 
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
                if($value <= $search)
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
     * 
     * Parses a string an checks characters to make sure they are valid english
     * alphabet characters that are part of the class alphabet array
     * 
     * @return boolean
     */
    protected function validateString($value)
    {
        $value = strtolower($value);
        for($i=0; $i < strlen($value); $i++)
        {
            if(strpos($this->alphabet, substr($value,$i,1)) === false) return false;
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
 *
 */

?>