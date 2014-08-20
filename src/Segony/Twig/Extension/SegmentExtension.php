<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension as Extension;
use Twig_Function_Method as Method;
use Segony\Debug\Debuggable;
use Segony\Storage\Storage;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class SegmentExtension extends Extension implements Debuggable
{

    private $container;
    private $requiredSegments = [];
    private $segmentResultSet;

    /**
     * @param  Storage $resultSet
     * @return SegmentExtension
     */
    public function setSegmentResultSet(array $resultSet)
    {
        $this->segmentResultSet = new Storage($resultSet);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getName()
    {
        return 'segment';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getFunctions()
    {
        return [
            'segment' => new Method($this, 'segment')
        ];
    }

    /**
     * @param  string $id
     * @return string
     * @throws Exception If the segment was not found
     *
     * @api
     */
    public function segment($id)
    {
        if (false === $this->segmentResultSet->has($id)) {
            throw new \Exception(sprintf('Cannot find segment with the identifier "%s"', $id));
        }

        array_push($this->requiredSegments, $id);

        return $this->segmentResultSet->get($id);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugId()
    {
        return 'twig_segment_extension';
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     */
    public function getDebugInfo()
    {
        return [
            'requiredSegments' => $this->requiredSegments
        ];
    }

}