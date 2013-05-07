<?php
namespace Tests\Drahak\Api;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Api\IResource;
use Drahak\Api\ResponseFactory;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Api\ResponseFactory.
 *
 * @testCase Tests\Drahak\Api\ResponseFactoryTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Api
 */
class ResponseFactoryTest extends TestCase
{
	/** @var ResponseFactory */
	private $factory;

	/** @var MockInterface */
	private $resource;

    protected function setUp()
    {
		parent::setUp();
		$this->factory = new ResponseFactory();
		$this->resource = $this->mockista->create('Drahak\Api\Resource');
	}

	public function testCreateResponse()
	{
		$this->resource->expects('getMimeType')
			->once()
			->andReturn(IResource::JSON);
		$this->resource->expects('getData')
			->once()
			->andReturn(array());

		$response = $this->factory->create($this->resource);
		Assert::true($response instanceof Nette\Application\Responses\JsonResponse);
	}

	public function testCreateCustomResponse()
	{
		$this->resource->expects('getMimeType')
			->once()
			->andReturn('text');
		$this->resource->expects('getData')
			->once()
			->andReturn('test');

		$this->factory->registerResponse('text', 'Nette\Application\Responses\TextResponse');
		$response = $this->factory->create($this->resource);

		Assert::true($response instanceof Nette\Application\Responses\TextResponse);
	}

	public function testThrowsExceptionWhenResponseTypeIsNotFound()
	{
		$this->resource->expects('getMimeType')
			->once()
			->andReturn('drahak/test');

		Assert::throws(function() {
			$this->factory->create($this->resource);
		}, 'Drahak\Api\InvalidStateException');
	}

    public function testThrowsExceptionWhenResponseClassNotExists()
    {
		$factory = $this->factory;
		Assert::throws(function() use($factory) {
			$factory->registerResponse('test/plain', 'Drahak\TestResponse');
		}, 'Drahak\Api\InvalidArgumentException');
    }
}
\run(new ResponseFactoryTest());