<?php
/**
 * OTWebsoft Framework
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, OTWebsoft Corporation
 * @license   http://otwebsoft.com/license
 * @link      http://otwebsoft.com OTWebsoft
 */

namespace Mp3\Service;

use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

/**
 * Class Search
 *
 * @package Mp3\Service
 */
class Search extends ServiceProvider implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function Search(array $params)
    {
        try {
            if (array_key_exists('dir', $params)) {
                $base_dir = preg_replace('/(\/+)/', '/', $this->getBasePath() . rawurldecode($params['dir']));

                $dir = preg_replace('/(\/+)/', '/', rawurldecode($params['dir']));
            } else {
                $base_dir = preg_replace('/(\/+)/', '/', $this->getBasePath());

                $dir = null;
            }

            $array = array();

            $total_length = '0';
            $total_size = '0';

            foreach ($this->DirectoryArray($base_dir) as $location) {
                clearstatcache();

                /**
                 * Directory
                 */
                if (is_dir($base_dir . $location)) {
                    $array[] = array(
                        'name'     => ltrim($location, '/'),
                        'location' => ($dir != null) ? $dir . $location : $location,
                        'type'     => 'dir'
                    );
                }

                /**
                 * File
                 */
                if (is_file($base_dir . '/' . $location)) {
                    $path = ($dir != null) ? $base_dir . $location : $location;

                    $calculate = new Calculate($path);
                    $meta = $calculate->get_metadata();

                    $array[] = array(
                        'name'     => ltrim($location, '/'),
                        'location' => ($dir != null) ? $dir . $location : $location,
                        'type'     => 'file',
                        'bit_rate' => (isset($meta['Bitrate'])) ? $meta['Bitrate'] : '-',
                        'length'   => (isset($meta['Length mm:ss'])) ? $meta['Length mm:ss'] : '-',
                        'size'     => (isset($meta['Filesize'])) ? $meta['Filesize'] : '-'
                    );

                    $total_length += (isset($meta['Length'])) ? $meta['Length'] : '0';
                    $total_size += (isset($meta['Filesize'])) ? $meta['Filesize'] : '0';
                }
            }

            $paginator = new Paginator(new ArrayAdapter($array));
            $paginator->setDefaultItemCountPerPage((count($array) > '0') ? count($array) : '1');

            return array(
                'paginator'    => $paginator,
                'path'         => ($dir != null) ? $dir : null,
                'total_length' => sprintf("%d:%02d", ($total_length / 60), $total_length % 60),
                'total_size'   => $total_size
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function PlayAll($dir)
    {
        try {
            $path = $this->getConfig()['base_dir'] . rawurldecode($dir);

            $array = $this->DirectoryArray($this->getBasePath() . rawurldecode($dir));

            if ($this->getConfig()['format'] == 'm3u') {
                /**
                 * Windows Media Player
                 */
                if (is_array($array) && count($array) > '0') {
                    $playlist = '#EXTM3U' . "\n";

                    foreach ($array as $value) {
                        $playlist .= '#EXTINF: ' . ltrim($value, '/') . "\n";
                        $playlist .= 'http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($path . $value) . "\n";
                        $playlist .= "\n";
                    }

                    header("Content-Type: audio/mpegurl");
                    header("Content-Disposition: attachment; filename=playlist.m3u");

                    return $playlist;
                }
            } elseif ($this->getConfig()['format'] == 'pls') {
                /**
                 * Winamp
                 */
                if (is_array($array) && count($array) > '0') {
                    $playlist = '[Playlist]' . "\n";

                    foreach ($array as $key => $value) {
                        $calculate = new Calculate($this->getBasePath() . rawurldecode($dir) . $value);
                        $meta = $calculate->get_metadata();

                        if (array_key_exists('Length', $meta)) {
                            $length = $meta['Length'];
                        } else {
                            $length = '-1';
                        }

                        $playlist .= 'File' . ($key + '1') . '=http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($path . $value) . "\n";
                        $playlist .= 'Title' . ($key + '1') . '=' . ltrim($value, '/') . "\n";
                        $playlist .= 'Length' . ($key + '1') . '=' . $length . "\n";
                    }

                    $playlist .= 'Numberofentries=' . count($array) . "\n";
                    $playlist .= 'Version=2' . "\n";

                    header("Content-Type: audio/x-scpls");
                    header("Content-Disposition: attachment; filename=playlist.pls");

                    return $playlist;
                }
            } else {
                throw new \Exception('Format is not currently supported' . "\n" . 'Supported formats are: pls, m3u');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function PlaySingle($dir)
    {
        try {
            $path = $this->getConfig()['base_dir'] . rawurldecode($dir);

            if ($this->getConfig()['format'] == 'm3u') {
                /**
                 * Windows Media Player
                 */
                header("Content-Type: audio/mpegurl");
                header("Content-Disposition: attachment; filename=playlist.m3u");

                return 'http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($path);
            } elseif ($this->getConfig()['format'] == 'pls') {
                /**
                 * Winamp
                 */
                header("Content-Type: audio/x-scpls");
                header("Content-Disposition: attachment; filename=playlist.pls");

                $playlist = '[Playlist]' . "\n";
                $playlist .= 'File1=http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($path) . "\n";
                $playlist .= 'Title1=' . basename($path) . "\n";
                $playlist .= 'Length1=-1' . "\n";
                $playlist .= 'Numberofentries=1' . "\n";
                $playlist .= 'Version=2' . "\n";

                return $playlist;
            } else {
                throw new \Exception('Format is not currently supported' . "\n" . 'Supported formats are: pls, m3u');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function DownloadSingle($dir)
    {
        try {
            $path = $this->getBasePath() . rawurldecode($dir);

            header("Content-Type: audio/mpeg");
            header("Content-Disposition: attachment; filename=" . basename($path));
            header("Content-Length: " . filesize($path));

            $handle = fopen($path, "rb");

            $contents = fread($handle, filesize($path));

            while ($contents) {
                echo $contents;
            }

            fclose($handle);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Directory Array
     *
     * @param string $dir
     *
     * @return array
     * @throws \Exception
     */
    private function DirectoryArray($dir)
    {
        try {
            $result_array = array();

            $handle = opendir($dir);

            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        clearstatcache();

                        if (is_dir($dir . '/' . $file) && false) {
                            $list = $this->DirectoryArray($dir . '/' . $file, '/' . $file);

                            $i = 0;

                            while ($list[$i]) {
                                $result_array[] = $list[$i];
                                $i++;
                            }
                        } else {
                            clearstatcache();

                            if (is_dir($dir . '/' . $file)) {
                                $result_array[] = '/' . $file;
                            }

                            /**
                             * Currently only supporting .mp3 format
                             */
                            if (is_file($dir . '/' . $file) && substr($file, -4) == '.mp3') {
                                $result_array[] = '/' . $file;
                            }
                        }
                    }
                }

                closedir($handle);

                sort($result_array);

                return $result_array;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Base Path
     *
     * @return string
     */
    private function getBasePath()
    {
        $config = $this->getServiceManager()
            ->get('config');

        return $_SERVER['DOCUMENT_ROOT'] . $config['mp3']['base_dir'];
    }

    /**
     * Get Config
     *
     * @return array
     */
    private function getConfig()
    {
        $config = $this->getServiceManager()
            ->get('config');

        return array(
            'base_dir' => $config['mp3']['base_dir'],
            'format'   => $config['mp3']['format']
        );
    }
}
