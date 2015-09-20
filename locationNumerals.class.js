/*
 * LocationNumerals Class
 * Author: Jared J Kramer
 * jared@jaredjkramer.com
 * 9.20.2015
 *
 * A js class to use location arithmetic to evaluate
 * integers to their proper location numeral assignment,
 * parse location numeral codes to integer values, and
 * parse location numeral codes to the most abbreviated
 * form
 *
 */


var Class = function(methods){
    var klass = function(){
        this.initialize.apply(this, arguments);
    };
    for(var property in methods)
    {
        klass.prototype[property] = methods[property];
    }
    if (!klass.prototype.initialize) {
        klass.prototype.initialize = function(){};
    }
    return klass;
};

var LocationNumeral = Class({
    initialize: function(value, method) {
        this.value = value;
        this.alphaVals = this.processAlphabet();
        if (value &&  typeof this[method] == 'function') {
            this.result = this[method](value);
        }
    },
    alphabet: 'abcdefghijklmnopqrstuvwxwz',
    alphaVals: {},
    errors : [],
    errorCodes: [
        "No Error: Init Code",
        "Error 1: Missing argument",                       
        "Error 1A: Invalid Argument. Integers only",
        "Error 2: Missing argument",
        "Error 2A: Invalid argument. Characters of the english alphabet only.",
        "Error 3: Missing argument",
        "Error 3A: Invalid argument. Characters of the english alphabet only.",
        "Error 4: Missing Argument"
    ],
    result: false,
    integerToAbbreviated: function(value)
    {
        this.errorReport(this.errorCodes[0], true);
        var str = '',
        remainder = value,
        key = null;
        if (parseInt(value) == NaN) {
            return this.errorReport(this.errorCodes[2]);
        }
        while(remainder > 0){
            key = this.closestKey(remainder);
            remainder= remainder - this.alphaVals[key];
            str+=key;
        }
        str = this.reverseString(str);
        this.result = str;
        return str;
    },
    locationToInteger: function(value)
    {
      this.errorReport(this.errorCodes[0], true);
      var intVal = false,
      valid = this.validateString(value),
      len = value.length,
      i = 0,
      c = null;
      if (!valid) {
        return this.errorReport(this.errorCodes[4]);
      }
      for(i; i<len;i++)
      {
        c = this.alphabet.substr(i,1);
        intVal+=this.alphaVals[c];
      }
      this.result = intVal;
      return intVal;
    },
    locationToAbbreviated: function(value){
      this.errorReport(this.errorCodes[0], true);
      var str = false,
      valid = this.validateString(value),
      intVal = false;
      if (!valid) {
        return this.errorReport(this.errorCodes[6]);
      }
      intVal = this.locationToInteger(value);
      str = this.integerToAbbreviated(inVal);
      this.result = str;
      return str;
    },
    processAlphabet: function()
    {
        var i = 0,
        str = null;
        for(i; i< 26; i++){
            str = this.alphabet.substr(i,1);
            this.alphaVals[str] = this.getValueOfChar(str);
        }
        return this.alphaVals;
    },
    getValueOfChar: function(string)
    {
        var valid = this.validateString(string),
        len = string.length,
        pos = null;
        if (!valid || len > 1 || len < 1) {
            return this.errorReport(this.errorCodes[7],false);
        }
        pos = this.alphabet.indexOf(string);
        return Math.pow(2, pos);
    },
    closestKey: function(search){
        var closestKey = null,
        closest = null,
        key = null,
        i = 0,
        c = null,
        value = null;
        for(i; i<26; i++){
            c = this.alphabet.charAt(i);
            value = this.alphaVals[c];
            if (closest == null || Math.abs((search - closest)) > Math.abs((value - search))) {
                if (value <= search) {
                    closest = value;
                    closestKey = c;
                }
            }
        }
        return closestKey;
    },
    validateString: function(string)
    {
        var len = string.length,
        str = string.toLowerCase(),
        i = 0,
        n = null;
        for(i;i<len;i++)
        {
            n = this.alphabet.indexOf(str.substr(i,1));
            if(n == -1) return false;
        }
        return true;
        
    },
    reverseString: function(string)
    {
        string = string.split("");
        var len = string.length,
        hindex = Math.floor(len/2) - 1,
        tmp = null,
        i = 0;
        for(i; i<= hindex; i++)
        {
            tmp = string[len - i - 1];
            string[len - i - 1] = string[i];
            string[i] = tmp;
        }
        return string.join("");
    },
    errorReport: function(errorCode, clear){
        this.errors.push(errorCode);
        if (clear === true) {
            this.errors = [];
            this.result = false;
        }
        return false;
    }
    
});