--TEST--
ReflectionMethod::invokeArgs() further errors
--FILE--
<?php require 'vendor/autoload.php';

class TestClass {

    public function foo() {
        echo "Called foo()\n";
        var_dump($this);
        return "Return Val";
    }
}

$foo = \BetterReflection\Reflection\ReflectionMethod::createFromName('TestClass', 'foo');

$testClassInstance = new TestClass();

try {
    var_dump($foo->invokeArgs($testClassInstance, true));
} catch (Error $e) {
    var_dump($e->getMessage());
}

?>
--EXPECT--
string(92) "Argument 2 passed to ReflectionMethod::invokeArgs() must be of the type array, boolean given"