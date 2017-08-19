<?php
declare(strict_types=1);

namespace Roave\BetterReflectionTest\Reflection\Adapter;

use ReflectionClass as CoreReflectionClass;
use ReflectionClassConstant as CoreReflectionClassConstant;
use Roave\BetterReflection\Reflection\Adapter\ReflectionClassConstant as ReflectionClassConstantAdapter;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionClassConstant as BetterReflectionClassConstant;

/**
 * @covers \Roave\BetterReflection\Reflection\Adapter\ReflectionClassConstant
 */
class ReflectionClassConstantTest extends \PHPUnit_Framework_TestCase
{
    public function coreReflectionMethodNamesProvider() : array
    {
        $methods = \get_class_methods(CoreReflectionClassConstant::class);
        return \array_combine($methods, \array_map(function (string $i) : array { return [$i]; }, $methods));
    }

    /**
     * @param string $methodName
     * @dataProvider coreReflectionMethodNamesProvider
     */
    public function testCoreReflectionMethods(string $methodName) : void
    {
        $reflectionClassConstantAdapterReflection = new CoreReflectionClass(ReflectionClassConstantAdapter::class);
        self::assertTrue($reflectionClassConstantAdapterReflection->hasMethod($methodName));
    }

    public function methodExpectationProvider() : array
    {
        return [
            ['__toString', '', []],
            ['getName', '', []],
            ['getValue', null, []],
            ['isPublic', true, []],
            ['isPrivate', true, []],
            ['isProtected', true, []],
            ['getModifiers', 123, []],
            ['getDeclaringClass', $this->createMock(BetterReflectionClass::class), []],
            ['getDocComment', '', []],
        ];
    }

    /**
     * @param string $methodName
     * @param mixed $returnValue
     * @param array $args
     * @dataProvider methodExpectationProvider
     */
    public function testAdapterMethods(string $methodName, $returnValue, array $args) : void
    {
        /* @var BetterReflectionClassConstant|\PHPUnit_Framework_MockObject_MockObject $reflectionStub */
        $reflectionStub = $this->createMock(BetterReflectionClassConstant::class);

        $reflectionStub->expects($this->once())
            ->method($methodName)
            ->with(...$args)
            ->will($this->returnValue($returnValue));

        $adapter = new ReflectionClassConstantAdapter($reflectionStub);
        $adapter->{$methodName}(...$args);
    }

    public function testGetDocCommentReturnsFalseWhenNoDocComment() : void
    {
        $betterReflectionClassConstant = $this->createMock(BetterReflectionClassConstant::class);
        $betterReflectionClassConstant
            ->method('getDocComment')
            ->willReturn('');

        $reflectionClassConstantAdapter = new ReflectionClassConstantAdapter($betterReflectionClassConstant);

        self::assertFalse($reflectionClassConstantAdapter->getDocComment());
    }
}
