<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Debug;

use Segony\Test\ContainerTestCase;
use Segony\Config\MainConfigDefinition;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class ConfigLoaderTest extends ContainerTestCase
{

    private $configLoader;

    protected function prepareContainer($container)
    {
        $container
            ->register('string_helper', 'Segony\Service\StringHelper');

        $container
            ->register('config_loader', 'Segony\Config\ConfigLoader')
            ->addArgument($container);

        $container->setParameter('firstname', 'Jon');
        $container->setParameter('lastname', 'Doe');

        $container->setParameter('child_firstname', 'Anna');
        $container->setParameter('child_lastname', 'Doe');

        // apply parameters to the current container
        $container->get('config_loader')->load('parameter.yml');
    }

    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../Resource/environment');
    }

    public function setUp()
    {
        $this->configLoader = $this->getContainer()->get('config_loader');
    }

    public function tearDown()
    {
        $this->configLoader = null;
    }

    public function testExceptionIfTheFileDoesNotExist()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->configLoader->load('this/should/not/exist.yml');
    }

    public function testToReplaceParameters()
    {
        $config = $this->configLoader->load('replace_parameter_test.yml');

        $this->assertSame('Jon', $config->get('firstname'));
        $this->assertSame('Doe', $config->get('lastname'));

        $this->assertSame('Anna', $config->get('child')->get('firstname'));
        $this->assertSame('Doe', $config->get('child')->get('lastname'));
    }

    public function testToApplyAConfigurationInterface()
    {
        $config = $this->configLoader->load('config.yml', new MainConfigDefinition());
        $this->assertInstanceOf('Segony\Storage\Storage', $config);
    }

    public function testToLoadEmptyFile()
    {
        $config = $this->configLoader->load('empty_file.yml');
        $this->assertInstanceOf('Segony\Storage\Storage', $config);
    }

    public function testToApplyParametersOnFrozenParameterBag()
    {
        $this->setExpectedException('Symfony\Component\DependencyInjection\Exception\LogicException');
        $this->configLoader->load('parameter.yml');
    }

    public function testToLoadAFileWhichImportsAnother()
    {
        $config = $this->configLoader->load('config_dev.yml');
        $this->assertTrue($config->has('twig'));
    }

    public function testToLoadExternalStuff()
    {
        $this->setExpectedException('Symfony\Component\Config\Exception\FileLoaderLoadException');
        $this->configLoader->load('http://www.google.com');
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithYamlParseFunction()
    {
        require_once realpath(__DIR__ . '/../../Resource/YamlParseFunction.php');

        $config = $this->configLoader->load('service.yml');
        $this->assertTrue($config->has('yaml_parse_fake'));
    }

}