<?php

namespace Api;


use Phinx\Console\PhinxApplication;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class AppTest
 * @package Api
 */
class AppTest extends TestCase
{
    /** @var App */
    private $subject;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();

        $phinx = new PhinxApplication();
        $phinx->setAutoExit(false);
        $phinx->run(new StringInput('seed:run -e testing -s TaskSeed'), new NullOutput());

        $this->subject = new App();
    }

    /**
     * @return array
     */
    public function providePostData(): array
    {
        $taskId = uniqid(null, true);
        return [
            'good' => [
                [
                    'status' => 201,
                    'body' => [
                        'task' => $taskId,
                        'is_done' => '0',
                    ]
                ],
                ['task' => $taskId]
            ],
            'bad 1' => [
                [
                    'status' => 400,
                    'body' => []
                ],
                []
            ],
            'bad 2' => [
                [
                    'status' => 400,
                    'body' => []
                ],
                ['id' => $taskId]
            ],
        ];
    }

    /**
     * @dataProvider providePostData
     *
     * @param $expected
     * @param $given
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPost($expected, $given): void
    {
        $resposne = $this->request('/task', 'post', $given);
        $this->assertEquals($expected['status'], $resposne->getStatusCode());
        $body = $resposne->getBody();

        $this->assertJson($body);
        $data = json_decode($body, true);
        foreach ($expected['body'] as $key => $val) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($val, $data[$key]);
        }
    }

    /**
     * @param $uri
     * @param $method
     * @param null $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    protected function request($uri, $method, $data = null): \Psr\Http\Message\ResponseInterface
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => strtoupper($method),
            'REQUEST_URI' => $uri,
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
        ]);

        $req = Request::createFromEnvironment($env);

        if ($data) {
            $req->getBody()->write(json_encode($data));
            $req->getBody()->rewind();
        }

        $this->subject->getContainer()['request'] = $req;

        return $this->subject->run(true);
    }

    /**
     * @return array
     */
    public function provideGetListData(): array
    {
        return [
            'good 1' => [
                ['status' => 200],
                '/task'
            ],
            'bad route' => [
                ['status' => 404],
                '/task/'
            ],
        ];
    }

    /**
     * @dataProvider provideGetListData
     * @param $expected
     * @param $given
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testGetList($expected, $given): void
    {
       $response = $this->request($given, 'get');
       $this->assertEquals($expected['status'], $response->getStatusCode());

       if ($response->getStatusCode() === 200) {
           $this->assertJson($response->getBody());
           $data = json_decode($response->getBody(), true);
           $this->assertIsArray($data);
           $this->assertNotEmpty($data);
       }
    }

    /**
     * @return array
     */
    public function provideGetData(): array
    {
        return [
            'good 1' => [
                ['status' => 200],
                '/task/1'
            ],
            'missing 1' => [
                ['status' => 404],
                '/task/999999'
            ],
            'bad route' => [
                ['status' => 404],
                '/task/nope'
            ],
        ];
    }

    /**
     * @dataProvider provideGetData
     * @param $expected
     * @param $given
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testGet($expected, $given): void
    {
        $response = $this->request($given, 'get');
        $this->assertEquals($expected['status'], $response->getStatusCode());

        if ($response->getStatusCode() === 200) {
            $this->assertJson($response->getBody());
            $data = json_decode($response->getBody(), true);
            $this->assertArrayHasKey('id', $data);
            $this->assertArrayHasKey('task', $data);
            $this->assertArrayHasKey('is_done', $data);
        }
    }

    /**
     * @return array
     */
    public function providePutData(): array
    {
        return [
            'good' => [
                ['status' => 200],
                [
                    'id' => 1,
                    'data' => [
                        'id' => '1',
                        'task' => uniqid(null, true)
                    ]
                ],
            ],
            'id mismatch' => [
                ['status' => 400],
                [
                    'id' => 1,
                    'data' => [
                        'id' => '2',
                        'task' => uniqid(null, true)
                    ]
                ],
            ],
            'missing id' => [
                ['status' => 400],
                [
                    'id' => 1,
                    'data' => [
                        'id' => '       ',
                        'task' => uniqid(null, true)
                    ]
                ],
            ],
            'bad id' => [
                ['status' => 400],
                [
                    'id' => 1,
                    'data' => [
                        'id' => 'foobar',
                        'task' => uniqid(null, true)
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider providePutData
     * @param $expected
     * @param $given
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testPut($expected, $given): void
    {
        $response = $this->request('/task/' . $given['id'], 'put', $given['data']);
        $this->assertEquals($expected['status'], $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function provideDeleteData(): array
    {
        return [
            'good' => [
                ['status' => 200],
                '1'
            ],
            'bad route' => [
                ['status' => 404],
                'foobar'
            ],
        ];
    }

    //////////////

    /**
     * @dataProvider provideDeleteData
     * @param $expected
     * @param $given
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function testDelete($expected, $given): void
    {
        $response = $this->request('/task/' . $given, 'delete');
        $this->assertEquals($expected['status'], $response->getStatusCode());
    }

}
