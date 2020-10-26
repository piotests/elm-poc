<?php

namespace Tests\Helpers;

use App\Helpers\Helper;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest
 * @package Tests\Helpers
 */
class HelperTest extends TestCase
{
    protected ?Helper $helper;

    /**
     * @var array
     */
    private array $user = [
        'getBrowserName' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
    ];

    public function setUp() : void
    {
        $this->helper = new Helper;
    }

    public function tearDown() : void
    {
        $this->helper = null;
    }

    public function testGetBrowserName() :void
    {
        $response = $this->helper->getBrowserName( $this->user["getBrowserName"] );
        $this->assertEquals('Chrome', $response, "testGetBrowserName");
    }
}
