<?php
namespace NamelessCoder\Fluid\Tests\Unit\Core\Parser\SyntaxTree;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode;
use NamelessCoder\Fluid\Core\Rendering\RenderingContext;
use NamelessCoder\Fluid\Core\Variables\StandardVariableProvider;
use NamelessCoder\Fluid\Tests\UnitTestCase;

/**
 * Class TernaryExpressionNodeTest
 */
class TernaryExpressionNodeTest extends UnitTestCase {

	/**
	 * @dataProvider getEvaluateExpressionTestValues
	 * @param string $expression
	 * @param array $variables
	 * @param mixed $expected
	 */
	public function testEvaluateExpression($expression, array $variables, $expected) {
		$renderingContext = new RenderingContext();
		$renderingContext->setVariableProvider(new StandardVariableProvider($variables));
		$result = TernaryExpressionNode::evaluateExpression($renderingContext, $expression, array());
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getEvaluateExpressionTestValues() {
		return array(
			array('1 ? 2 : 3', array(), 2),
			array('0 ? 2 : 3', array(), 3),
		);
	}

}
