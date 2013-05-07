<?php
namespace Tests\Drahak\Api\Application\Responses;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Api\Application\Responses\NullResponse;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Api\Application\Responses\NullResponse.
 *
 * @testCase Tests\Drahak\Api\Application\Responses\NullResponseTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Api\Application\Responses
 */
class NullResponseTest extends TestCase
{
	/** @var NullResponse */
	private $response;
    
    protected function setUp()
    {
		parent::setUp();
		$this->response = new NullResponse;
    }
    
    public function testDoNotSendResponse()
    {
		$httpRequest = $this->mockista->create('Nette\Http\IRequest');
		$httpResponse = $this->mockista->create('Nette\Http\IResponse');

		ob_start();
		$result = $this->response->send($httpRequest, $httpResponse);
		$content = ob_get_contents();
		ob_end_clean();

		Assert::equal($content, '');
		Assert::null($result);
    }
}
\run(new NullResponseTest());