<?php
namespace NamelessCoder\Fluid\Tests\Unit\Core\Parser\SyntaxTree;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use NamelessCoder\Fluid\Tests\Unit\ViewHelpers\Fixtures\UserWithToString;
use NamelessCoder\Fluid\Tests\UnitTestCase;

/**
 * An AbstractNode Test
 */
class AbstractNodeTest extends UnitTestCase {

	protected $renderingContext;

	protected $abstractNode;

	protected $childNode;

	public function setUp() {
		$this->renderingContext = $this->getMock('NamelessCoder\Fluid\Core\Rendering\RenderingContext', array(), array(), '', FALSE);

		$this->abstractNode = $this->getMock('NamelessCoder\Fluid\Core\Parser\SyntaxTree\AbstractNode', array('evaluate'));

		$this->childNode = $this->getMock('NamelessCoder\Fluid\Core\Parser\SyntaxTree\AbstractNode');
		$this->abstractNode->addChildNode($this->childNode);
	}

	/**
	 * @test
	 */
	public function evaluateChildNodesPassesRenderingContextToChildNodes() {
		$this->childNode->expects($this->once())->method('evaluate')->with($this->renderingContext);
		$this->abstractNode->evaluateChildNodes($this->renderingContext);
	}

	/**
	 * @test
	 */
	public function evaluateChildNodeThrowsExceptionIfChildNodeCannotBeCastToString() {
		$this->childNode->expects($this->once())->method('evaluate')->with($this->renderingContext)->willReturn(new \DateTime('now'));
		$method = new \ReflectionMethod($this->abstractNode, 'evaluateChildNode');
		$method->setAccessible(TRUE);
		$this->setExpectedException('NamelessCoder\\Fluid\\Core\\Parser\\Exception');
		$method->invokeArgs($this->abstractNode, array($this->childNode, $this->renderingContext, TRUE));
	}

	/**
	 * @test
	 */
	public function evaluateChildNodeCanCastToString() {
		$withToString = new UserWithToString('foobar');
		$this->childNode->expects($this->once())->method('evaluate')->with($this->renderingContext)->willReturn($withToString);
		$method = new \ReflectionMethod($this->abstractNode, 'evaluateChildNode');
		$method->setAccessible(TRUE);
		$result = $method->invokeArgs($this->abstractNode, array($this->childNode, $this->renderingContext, TRUE));
		$this->assertEquals('foobar', $result);
	}

	/**
	 * @test
	 */
	public function childNodeCanBeReadOutAgain() {
		$this->assertSame($this->abstractNode->getChildNodes(), array($this->childNode));
	}
}
