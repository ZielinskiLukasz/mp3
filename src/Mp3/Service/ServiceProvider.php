<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright 2014 Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE License
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Service;

use Zend\Mvc\I18n\Translator;
use Zend\View\HelperPluginManager;

/**
 * Class ServiceProvider
 *
 * @package Mp3\Service
 */
abstract class ServiceProvider
{
    use FunctionTrait;

    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var HelperPluginManager $serverUrl
     */
    protected $serverUrl;

    /**
     * @var Translator $translate
     */
    protected $translate;

    /**
     * Construct
     *
     * @param array               $config
     * @param HelperPluginManager $serverUrl
     * @param Translator          $translate
     */
    public function __construct(
        array $config,
        $serverUrl,
        Translator $translate
    ) {
        $this->config = $config;

        $this->serverUrl = $serverUrl;

        $this->translate = $translate;
    }

    /**
     * Get Search Path
     *
     * @return string
     * @throws \Exception
     */
    protected function getSearchPath()
    {
        if (!isset($this->config['mp3']['searchPath'])) {
            throw new \Exception(
                $this->translate->translate(
                    'searchPath is not currently set',
                    'mp3'
                )
            );
        }

        return $this->config['mp3']['searchPath'];
    }

    /**
     * Get Base Directory
     *
     * @return string
     * @throws \Exception
     */
    protected function getBaseDir()
    {
        if (!isset($this->config['mp3']['baseDir'])) {
            throw new \Exception(
                $this->translate->translate(
                    'baseDir is not currently set',
                    'mp3'
                )
            );
        }

        return $this->config['mp3']['baseDir'];
    }

    /**
     * Get Format
     *
     * @return string
     * @throws \Exception
     */
    protected function getFormat()
    {
        if (!isset($this->config['mp3']['format'])) {
            throw new \Exception(
                $this->translate->translate(
                    'format is not currently set',
                    'mp3'
                )
            );
        }

        return $this->config['mp3']['format'];
    }

    /**
     * Get Search File
     *
     * @return string
     * @throws \Exception
     */
    protected function getSearchFile()
    {
        if (!isset($this->config['mp3']['searchFile'])) {
            throw new \Exception(
                $this->translate->translate(
                    'searchFile is not currently set',
                    'mp3'
                )
            );
        }

        return $this->config['mp3']['searchFile'];
    }

    /**
     * Get Memory Limit
     *
     * @return string
     * @throws \Exception
     */
    protected function getMemoryLimit()
    {
        if (!isset($this->config['mp3']['memoryLimit'])) {
            throw new \Exception(
                $this->translate->translate(
                    'memoryLimit is not currently set',
                    'mp3'
                )
            );
        }

        return $this->config['mp3']['memoryLimit'];
    }
}
