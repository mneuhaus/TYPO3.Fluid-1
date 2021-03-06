<?php
namespace NamelessCoder\Fluid\Tests\Unit\Core\Parser\SyntaxTree;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Core\Parser\SyntaxTree\Expression\CastingExpressionNode;
use NamelessCoder\Fluid\Core\Rendering\RenderingContext;
use NamelessCoder\Fluid\Core\Variables\StandardVariableProvider;
use NamelessCoder\Fluid\Tests\Unit\ViewHelpers\Fixtures\UserWithToArray;
use NamelessCoder\Fluid\Tests\UnitTestCase;

/**
 * Class CastingExpressionNodeTest
 */
class CastingExpressionNodeTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function testEvaluateInvalidExpressionThrowsException() {
		$renderingContext = new RenderingContext();
		$renderingContext->setVariableProvider(new StandardVariableProvider());
		$this->setExpectedException('NamelessCoder\\Fluid\\Core\\ViewHelper\\Exception');
		$result = CastingExpressionNode::evaluateExpression($renderingContext, 'suchaninvalidexpression as 1', array());
	}

	/**
	 * @dataProvider getEvaluateExpressionTestValues
	 * @param string $expression
	 * @param array $variables
	 * @param mixed $expected
	 */
	public function testEvaluateExpression($expression, array $variables, $expected) {
		$renderingContext = new RenderingContext();
		$renderingContext->setVariableProvider(new StandardVariableProvider($variables));
		$result = CastingExpressionNode::evaluateExpression($renderingContext, $expression, array());
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getEvaluateExpressionTestValues() {
		$arrayIterator = new \ArrayIterator(array('foo', 'bar'));
		$toArrayObject = new UserWithToArray('foobar');
		return array(
			array('123 as string', array(), '123'),
			array('1 as boolean', array(), TRUE),
			array('0 as boolean', array(), FALSE),
			array('0 as array', array(), array(0)),
			array('1 as array', array(), array(1)),
			array('mystring as float', array('mystring' => '1.23'), 1.23),
			array('myvariable as integer', array('myvariable' => 321), 321),
			array('myinteger as string', array('myinteger' => 111), '111'),
			array('mydate as DateTime', array('mydate' => 90000), \DateTime::createFromFormat('U', 90000)),
			array('mydate as DateTime', array('mydate' => 'January'), new \DateTime('January')),
			array('1 as namestoredinvariables', array('namestoredinvariables' => 'boolean'), TRUE),
			array('mystring as array', array('mystring' => 'foo,bar'), array('foo', 'bar')),
			array('mystring as array', array('mystring' => 'foo , bar'), array('foo', 'bar')),
			array('myiterator as array', array('myiterator' => $arrayIterator), array('foo', 'bar')),
			array('myarray as array', array('myarray' => array('foo', 'bar')), array('foo', 'bar')),
			array('myboolean as array', array('myboolean' => TRUE), array()),
			array('myboolean as array', array('myboolean' => FALSE), array()),
			array('myobject as array', array('myobject' => $toArrayObject), array('name' => 'foobar')),
		);
	}

}
