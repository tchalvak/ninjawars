<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use NinjaWars\core\environment\RequestWrapper;
use NinjaWars\core\extensions\SessionFactory;
use NinjaWars\core\extensions\StreamedViewResponse;
use NinjaWars\core\control\ChatController;

class ChatControllerTest extends NWTest {
    private $controller;

    public function __construct() {
        $this->controller = new ChatController();
    }

	public function setUp() {
		SessionFactory::init(new MockArraySessionStorage());
        $char_id = TestAccountCreateAndDestroy::create_testing_account();
		SessionFactory::getSession()->set('player_id', $char_id);
    }

	public function tearDown() {
        RequestWrapper::destroy();
        $session = SessionFactory::getSession();
        $session->invalidate();
    }

    public function testIndex() {
        RequestWrapper::inject(new Request());
        $response = $this->controller->index();

        $this->assertInstanceOf(StreamedViewResponse::class, $response);
    }

    public function testIndexRendersEvenLoggedOut() {
        $response = $this->controller->index($this->mockLogout());

        $this->assertInstanceOf(StreamedViewResponse::class, $response);
    }
}
