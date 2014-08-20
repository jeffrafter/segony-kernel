<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Segment;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Segony\Test\FileSystemTestCase;
use Segony\Segment\SegmentWorker;
use Segony\Storage\Storage;
use Segony\Debug\Debug;
use Segony\Debug\MemorizeBridge\BlackHoleMemorizer;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 *
 * @runTestsInSeparateProcesses
 *
 * @covers \Segony\Segment\SegmentWorker
 */
class SegmentWorkerTest extends FileSystemTestCase
{

    private $resourceDir;
    private $segmentDir;
    private $container;
    private $worker;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this
            ->skeleton('backend_controller', 'TestSegmentController.php', ['Test', 'Test'])
            ->skeleton('client_controller',  'test.js')
            ->skeleton('config',             'test.yml')
            ->skeleton('view',               'view/test.html.twig');

        $this->worker = new SegmentWorker();
        $this->worker->setContainer($this->getContainer());
    }

    private function skeleton($name, $destination, array $args = [])
    {
        if (null === $this->resourceDir) {
            $this->resourceDir = realpath(__DIR__ . '/../../Resource/skeleton/segment');
        }

        if (null === $this->segmentDir) {
            $this->segmentDir = $this->workspace . '/app/segment/test';
            mkdir($this->segmentDir, 0777, true);
            mkdir($this->segmentDir . '/view');
        }

        $template = file_get_contents($this->resourceDir . '/' . $name . '.skeleton');
        $content  = vsprintf($template, $args);

        file_put_contents($this->segmentDir . '/' . $destination, $content);

        return $this;
    }

    /**
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $this->container = new ContainerBuilder(new ParameterBag([
                'kernel.root_dir'    => $this->workspace,
                'kernel.cache_dir'   => $this->workspace . '/app/cache',
                'kernel.environment' => 'test',
                'kernel.debug'       => false
            ]));

            $this->container
                ->register('config_loader', 'Segony\Config\ConfigLoader')
                ->addArgument($this->container);

            $this->container
                ->register('string_helper', 'Segony\Service\StringHelper');

            $this->container
                ->register('event_dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');

            $this->container
                ->register('twig_loader_filesystem', 'Twig_Loader_Filesystem')
                ->addArgument($this->container->getParameter('kernel.root_dir'));

            $this->container
                ->register('twig', 'Twig_Environment')
                ->addArgument(new Reference('twig_loader_filesystem'))
                ->addArgument([]);

            $debug = new Debug();
            $debug->setMemorizeBridge(new BlackHoleMemorizer());

            $this->container->set('debug', $debug);

            $this->container->compile();
        }

        return $this->container;
    }

    public function testToRegisterANewSegment()
    {
        $defintion = new Storage([
            'segment' => 'test',
            'config'  => []
        ]);

        $response = $this->worker->register('my_id', $defintion);
        $this->assertInstanceOf('Segony\Segment\SegmentWorker', $response);
    }

    public function testToRegisterASegmentMoreThanOneTimes()
    {
        $this->setExpectedException('Segony\Exception');

        $defintion = new Storage([
            'segment' => 'test',
            'config'  => []
        ]);

        $this->worker->register('my_id', $defintion);
        $this->worker->register('my_id', $defintion);
    }

    public function testProcess()
    {
        $defintion = new Storage([
            'segment' => 'test',
            'config'  => []
        ]);

        $this->worker->register('my_id', $defintion);
        $result = $this->worker->process();

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('my_id', $result);

        $information = $this->worker->getInformation();

        $this->assertInternalType('array', $information);
        $this->assertCount(1, $information);
        $this->assertArrayHasKey('my_id', $information);

        $segment = $information['my_id'];

        $this->assertArrayHasKey('embeddingKey', $segment);
        $this->assertArrayHasKey('controller', $segment);
        $this->assertArrayHasKey('config', $segment);
    }

}