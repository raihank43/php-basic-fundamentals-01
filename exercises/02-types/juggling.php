<?php

echo "Comparison 1", PHP_EOL;
var_dump(0 === false);
var_dump(0 == false);
/**
 * first line will print false while the second is true, 
 * i think because === compare type and value, and by value it probably mean the zero and type is "number/int", 
 * so "===" value is true because 0 is falsy in boolean, but the type is not because 0 is number/int while "false" is boolean.
 * meanwhile "==" do type coercion that enforce the type for 0 to be boolean behind the scene and since 0 is falsy value in boolean it returns true.
 */
echo "=========", PHP_EOL;

echo "Comparison 2", PHP_EOL;
var_dump(0 == "a");
var_dump(0 === "a");
// they're both return false because neither type or their value are the same

echo "=========", PHP_EOL;

echo "Comparison 3", PHP_EOL;
var_dump("" == false);
var_dump("" === false);
/**
 * the first is true, while the second line return false
 * "" is empty string and is falsy, == do type coercion and after the conversion the empty string value is considered falsy in boolean, hence why it returns true.
 * === return false because it compares both value and type. "" type is string and "false" is boolean, 
 * meanwhile the value is true because "" is falsy in boolean, so a true + false = false
 */


echo "=========", PHP_EOL;

echo "Comparison 4", PHP_EOL;
$comparison_4 = "5" + 3;

var_dump($comparison_4 == true);
var_dump($comparison_4 === true);
/**
 * the first line is true while the second one is false,
 * because "5" + 3 becomes int(8)
 * == do type coercion to boolean, and int(8) is considered truthy value in boolean. (I think any int value is truthy except 0), so a truthy value is being compared with a true = "true"
 * the second one is because the type is different, while 8 is a truthy value, so its different in type but the same in value, hence why it return false.
 */

echo "=========", PHP_EOL;

echo "Comparison 5", PHP_EOL;
var_dump(null == false);
var_dump(null === false);
/**
 * the first one return true while the second is false
 * null is special type, however "==" do type coercion to boolean and null is considered falsy value in boolean, hence why it true
 * the second one return false because their type is different but their value is the same, like it stated above, "null" is considered falsy value in boolean, hence why it return false.
 */