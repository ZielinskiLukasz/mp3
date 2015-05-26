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
     * Get Base Path
     *
     * @return string
     */
    protected function getBasePath()
    {
        if (php_sapi_name() == 'cli') {
            return $this->getConfig()['searchPath'];
        } else {
            return $_SERVER['DOCUMENT_ROOT'] . $this->getConfig()['baseDir'];
        }
    }

    /**
     * Get Config
     *
     * @return array
     * @throws \Exception
     */
    protected function getConfig()
    {
        $config = $this->config;

        if (!isset($config['mp3']['baseDir'])) {
            throw new \Exception(
                $this->translate->translate(
                    'baseDir is not currently set',
                    'mp3'
                )
            );
        }

        if (!isset($config['mp3']['format'])) {
            throw new \Exception(
                $this->translate->translate(
                    'format is not currently set',
                    'mp3'
                )
            );
        }

        if (!isset($config['mp3']['searchFile'])) {
            throw new \Exception(
                $this->translate->translate(
                    'searchFile is not currently set',
                    'mp3'
                )
            );
        }

        if (!isset($config['mp3']['searchPath'])) {
            throw new \Exception(
                $this->translate->translate(
                    'searchPath is not currently set',
                    'mp3'
                )
            );
        }

        return [
            'baseDir'    => $config['mp3']['baseDir'],
            'format'     => $config['mp3']['format'],
            'searchFile' => $config['mp3']['searchFile'],
            'searchPath' => $config['mp3']['searchPath']
        ];
    }

    /**
     * Converts Timestamp from HH:MM:SS to Seconds
     *
     * @param string $length
     * @param string $hours
     * @param string $minutes
     * @param string $seconds
     *
     * @return string
     */
    protected function convertTime($length, $hours = '0', $minutes = '00', $seconds = '00')
    {
        $length = preg_replace(
            "/^([\d]{1,2})\:([\d]{2})$/",
            "00:$1:$2",
            $length
        );

        sscanf(
            $length,
            "%d:%d:%d",
            $hours,
            $minutes,
            $seconds
        );

        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    /**
     * Get ID3 Details
     *
     * @param string $path
     *
     * @return array
     */
    protected function getId3($path)
    {
        $getID3 = new \getID3();

        $analyze = $getID3->analyze($path);

        \getid3_lib::CopyTagsToComments($analyze);

        return $analyze;
    }

    /**
     * Extensions
     *
     * @return array
     */
    protected function getExtensions()
    {
        return [
            '.3gp',
            '.act',
            '.aiff',
            '.aac',
            '.aac',
            '.au',
            '.awb',
            '.dct',
            '.dss',
            '.dvf',
            '.flac',
            '.gsm',
            '.iklax',
            '.ivs',
            '.m4a',
            '.m4p',
            '.mmf',
            '.mp3',
            '.mpc',
            '.msv',
            '.ogg',
            '.oga',
            '.opus',
            '.ra',
            '.rm',
            '.raw',
            '.sln',
            '.tta',
            '.vox',
            '.wav',
            '.wma',
            '.wv',
            '.webm'
        ];
    }
}
