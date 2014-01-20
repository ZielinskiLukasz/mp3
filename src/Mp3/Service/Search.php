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
            $config = $this->getServiceManager()
                ->get('config');

            $base_dir = (isset($params['dir'])) ? $_SERVER['DOCUMENT_ROOT'] . $config['mp3']['base_dir'] . '/' . rawurldecode($params['dir']) : $_SERVER['DOCUMENT_ROOT'] . $config['mp3']['base_dir'];

            $array = array();

            $total_length = '0';
            $total_size = '0';

            foreach ($this->DirectoryArray($base_dir) as $location) {
                if ($location['type'] == 'dir') {
                    $array[] = array(
                        'name'     => ltrim($location['path'], '/'),
                        'location' => (isset($params['dir'])) ? $params['dir'] . $location['path'] : $location['path'],
                        'type'     => $location['type']
                    );
                } else {
                    $path = (isset($params['dir'])) ? $base_dir . $location['path'] : $location['path'];

                    $mp3_file = new Calculate($path);
                    $convert = $mp3_file->get_metadata();

                    $array[] = array(
                        'name'     => ltrim($location['path'], '/'),
                        'location' => $params['dir'] . $location['path'],
                        'type'     => $location['type'],
                        'bit_rate' => (isset($convert['Bitrate'])) ? $convert['Bitrate'] : '-',
                        'length'   => (isset($convert['Length mm:ss'])) ? $convert['Length mm:ss'] : '-',
                        'size'     => (isset($convert['Filesize'])) ? $convert['Filesize'] : '-'
                    );

                    $total_length += (isset($convert['Length'])) ? $convert['Length'] : '0';
                    $total_size += (isset($convert['Filesize'])) ? $convert['Filesize'] : '0';
                }
            }

            $paginator = new Paginator(new ArrayAdapter($array));
            $paginator->setDefaultItemCountPerPage((count($array) > '0') ? count($array) : '1');

            return array(
                'paginator'    => $paginator,
                'path'         => (isset($params['dir'])) ? rawurldecode($params['dir']) : null,
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
    public function PlayAll($dir, $playlist = null)
    {
        try {
            $config = $this->getServiceManager()
                ->get('config');

            $dir = $config['mp3']['base_dir'] . $dir;

            $array = $this->DirectoryArray($_SERVER['DOCUMENT_ROOT'] . $dir);

            if ($config['mp3']['format'] == 'm3u') {
                /**
                 * Windows Media Player
                 */
                if (is_array($array) && count($array) > '0') {
                    $playlist = '#EXTM3U' . "\n";

                    foreach ($array as $value) {
                        $playlist .= '#EXTINF: ' . ltrim($value['path'], '/') . "\n";
                        $playlist .= 'http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($dir . $value['path']) . "\n";
                        $playlist .= "\n";
                    }

                    header("Content-Type: audio/mpegurl");
                    header("Content-Disposition: attachment; filename=playlist.m3u");

                    return $playlist;
                }
            } elseif ($config['mp3']['format'] == 'pls') {
                /**
                 * Winamp
                 */
                if (is_array($array) && count($array) > '0') {
                    $playlist = '[Playlist]' . "\n";

                    foreach ($array as $key => $value) {
                        $mp3_file = new Calculate($_SERVER['DOCUMENT_ROOT'] . $dir . $value['path']);
                        $convert = $mp3_file->get_metadata();

                        if (array_key_exists('Length', $convert)) {
                            $length = $convert['Length'];
                        } else {
                            $length = '-1';
                        }

                        $playlist .= 'File' . ($key + '1') . '=http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($dir . $value['path']) . "\n";
                        $playlist .= 'Title' . ($key + '1') . '=' . ltrim($value['path'], '/') . "\n";
                        $playlist .= 'Length' . ($key + '1') . '=' . $length . "\n";
                    }

                    $playlist .= 'Numberofentries=' . count($array) . "\n";
                    $playlist .= 'Version=2' . "\n";
                }

                header("Content-Type: audio/x-scpls");
                header("Content-Disposition: attachment; filename=playlist.pls");

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
    public function PlaySingle($dir, $playlist = null)
    {
        try {
            $config = $this->getServiceManager()
                ->get('config');

            $dir = $config['mp3']['base_dir'] . $dir;

            if ($config['mp3']['format'] == 'm3u') {
                /**
                 * Windows Media Player
                 */
                header("Content-Type: audio/mpegurl");
                header("Content-Disposition: attachment; filename=playlist.m3u");

                return 'http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($dir);
            } elseif ($config['mp3']['format'] == 'pls') {
                /**
                 * Winamp
                 */
                header("Content-Type: audio/x-scpls");
                header("Content-Disposition: attachment; filename=playlist.pls");

                $playlist = '[Playlist]' . "\n";
                $playlist .= 'File1=http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($dir) . "\n";
                $playlist .= 'Title1=' . rawurlencode($dir) . "\n";
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
            $config = $this->getServiceManager()
                ->get('config');

            $dir = $_SERVER['DOCUMENT_ROOT'] . $config['mp3']['base_dir'] . rawurldecode($dir);

            header("Content-Type: audio/mpeg");
            header("Content-Disposition: attachment; filename=" . basename($dir));
            header("Content-Length: " . filesize($dir));

            $handle = fopen($dir, "rb");

            $contents = fread($handle, filesize($dir));

            while ($contents) {
                echo $contents;
            }

            fclose($handle);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function DirectoryArray($dir)
    {
        try {
            $result_array = array();

            $handle = opendir($dir);

            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir($dir . '/' . $file) && false) {
                            $list = $this->DirectoryArray($dir . '/' . $file, '/' . $file);

                            $i = 0;

                            while ($list[$i]) {
                                $result_array[] = $list[$i];
                                $i++;
                            }
                        } else {
                            $path = '/' . $file;

                            if (is_file($dir . $path) && substr($dir . $path, -3) == 'mp3') {
                                $result_array[] = array(
                                    'path' => $path,
                                    'type' => 'file'
                                );
                            } elseif (is_dir($dir . $path)) {
                                $result_array[] = array(
                                    'path' => $path,
                                    'type' => 'dir'
                                );
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
}
