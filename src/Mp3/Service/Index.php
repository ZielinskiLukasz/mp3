<?php
/**
 * MP3 Player
 *
 * @author    Sammie S. Taunton <diemuzi@gmail.com>
 * @copyright Copyright (c) 2014, Sammie S. Taunton
 * @license   https://github.com/diemuzi/mp3/blob/master/LICENSE
 * @link      https://github.com/diemuzi/mp3 MP3 Player
 */

namespace Mp3\Service;

use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;

/**
 * Class Index
 *
 * @package Mp3\Service
 */
class Index extends ServiceProvider implements IndexInterface
{
    /**
     * {@inheritdoc}
     */
    public function Index(array $params)
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

            $total_length = null;
            $total_size = null;

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

            if ($total_length > '0') {
                $total_length = sprintf("%d:%02d", ($total_length / 60), $total_length % 60);
            }

            return array(
                'paginator'    => $paginator,
                'path'         => ($dir != null) ? $dir : null,
                'total_length' => $total_length,
                'total_size'   => $total_size,
                'search'       => (is_file($this->getConfig()['search_file']))
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

                    echo $playlist;
                    exit;
                } else {
                    throw new \Exception('Format is not currently supported' . "\n" . 'Supported formats are: pls, m3u');
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
                        $playlist .= 'Title' . ($key + '1') . '=' . basename($value) . "\n";
                        $playlist .= 'Length' . ($key + '1') . '=' . $length . "\n";
                    }

                    $playlist .= 'Numberofentries=' . count($array) . "\n";
                    $playlist .= 'Version=2' . "\n";

                    header("Content-Type: audio/x-scpls");
                    header("Content-Disposition: attachment; filename=playlist.pls");

                    echo $playlist;
                    exit;
                } else {
                    throw new \Exception('Something went wrong and we cannot play this folder.');
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

                echo 'http://' . $_SERVER["SERVER_NAME"] . '/' . rawurlencode($path);
                exit;
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

                echo $playlist;
                exit;
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

            $handle = fopen($path, 'rb');

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
     * {@inheritdoc}
     */
    public function DownloadFolder(array $params)
    {
        try {
            if (extension_loaded('Phartt')) {
                $dir = rawurldecode($params['dir']);

                $array = $this->DirectoryArray($this->getBasePath() . $dir);

                if (is_array($array) && count($array) > '0') {
                    $filename = basename($dir) . '.' . $params['format'];

                    $tar = new \PharData($filename);

                    foreach ($array as $value) {
                        $tar->addFile($this->getBasePath() . $dir . $value, basename($value));
                    }

                    if ($params['format'] == 'tar') {
                        header('Content-Type: application/x-tar');
                    } elseif ($params['format'] == 'zip') {
                        header('Content-Type: application/zip');
                    } elseif ($params['format'] == 'bz2') {
                        header('Content-Type: application/x-bzip2');
                    } elseif ($params['format'] == 'rar') {
                        header('Content-Type: x-rar-compressed');
                    }

                    header('Content-disposition: attachment; filename=' . $filename);
                    header('Content-Length: ' . filesize($filename));

                    readfile($filename);

                    unlink($filename);

                    exit;
                } else {
                    throw new \Exception('Something went wrong and we cannot download this folder.');
                }
            } else {
                throw new \Exception('failed');
            }
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
}
