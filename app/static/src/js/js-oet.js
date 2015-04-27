/**
* Join a list of words together by a given string (string delimiter).
* @param string join_by The string to join a list of text by.
* @class String
* @function join
* @return A new concated string with the string seperate.
*/
String.prototype.join = function(join_by){
    var arglen = arguments.length;
    var temp = this;
    if(arglen < 2)
        return this;
    for(i = 1; i < arglen; i++) {
        temp = temp.concat(arguments[0], arguments[i]);
    }
    return temp;
}

Math.random_between = function(x, y){
  return Math.floor((Math.random()*x))+y;
}

Array.compare = function(array1, array2){
  return JSON.stringify(array1) === JSON.stringify(array2)
}

Array.is_empty = function(array){
  return array[0] != undefined
}

/**
* Script injection.
*/
var infer = {
    script: function(script){
      $script = document.createElement('script');
      $script.type = 'text/javascript';
      $script.src = script;
      document.body.appendChild($script);
    }
}

/**
* Check if a varibale is empty.
* @param {mixed} $var.
* @return boolean True if the variable is empty, else false.
*/
function isEmpty($var){
    try{
        return myVar==null || myVar==undefined || myVar=='';
    }catch($e){
        return true;
    }
}