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
     * Index
     *
     * @param array $params
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function index(array $params)
    {
        try {
            if (array_key_exists(
                'dir',
                $params
            )) {
                $baseDir = preg_replace(
                    '/(\/+)/',
                    '/',
                    $this->getBasePath() . rawurldecode($params['dir'])
                );

                $dir = preg_replace(
                    '/(\/+)/',
                    '/',
                    rawurldecode($params['dir'])
                );
            } else {
                $baseDir = preg_replace(
                    '/(\/+)/',
                    '/',
                    $this->getBasePath()
                );

                $dir = null;
            }

            $array = [];

            $totalLength = null;

            $totalSize = null;

            clearstatcache();

            if (is_dir($baseDir)) {
                foreach ($this->directoryArray($baseDir) as $location) {
                    clearstatcache();

                    /**
                     * Directory
                     */
                    if (is_dir($baseDir . $location)) {
                        $array[] = [
                            'name'     => ltrim(
                                $location,
                                '/'
                            ),
                            'location' => ($dir != null)
                                ? $dir . $location
                                : $location,
                            'type'     => 'dir'
                        ];
                    }

                    /**
                     * File
                     */
                    if (is_file($baseDir . '/' . $location)) {
                        $path = ($dir != null)
                            ? $baseDir . $location
                            : $location;

                        $calculate = new Calculate($path);
                        $meta = $calculate->get_metadata();

                        $array[] = [
                            'name'     => ltrim(
                                $location,
                                '/'
                            ),
                            'location' => ($dir != null)
                                ? $dir . $location
                                : $location,
                            'type'     => 'file',
                            'bit_rate' => (isset($meta['Bitrate']))
                                ? $meta['Bitrate']
                                : '-',
                            'length'   => (isset($meta['Length mm:ss']))
                                ? $meta['Length mm:ss']
                                : '-',
                            'size'     => (isset($meta['Filesize']))
                                ? $meta['Filesize']
                                : '-'
                        ];

                        $totalLength += (isset($meta['Length']))
                            ? $meta['Length']
                            : '0';

                        $totalSize += (isset($meta['Filesize']))
                            ? $meta['Filesize']
                            : '0';
                    }
                }
            } else {
                throw new \Exception(
                    $baseDir . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }

            $paginator = new Paginator(new ArrayAdapter($array));

            $paginator->setDefaultItemCountPerPage(
                (count($array) > '0')
                    ? count($array)
                    : '1'
            );

            if ($totalLength > '0') {
                $totalLength = sprintf(
                    "%d:%02d",
                    ($totalLength / 60),
                    $totalLength % 60
                );
            }

            return [
                'paginator'    => $paginator,
                'path'         => ($dir != null)
                    ? $dir
                    : null,
                'total_length' => $totalLength,
                'total_size'   => $totalSize,
                'search'       => (is_file($this->getConfig()['searchFile']))
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Play All
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playAll($dir)
    {
        try {
            $path = $this->getConfig()['baseDir'] . rawurldecode($dir);

            $serverUrl = $this->serverUrl->get('serverurl')
                                         ->__invoke(
                                             '/'
                                         );

            clearstatcache();

            if (is_dir($this->getBasePath() . rawurldecode($dir))) {
                $array = $this->directoryArray($this->getBasePath() . rawurldecode($dir));

                if ($this->getConfig()['format'] == 'm3u') {
                    /**
                     * Windows Media Player
                     */
                    if (is_array($array) && count($array) > '0') {
                        $playlist = '#EXTM3U' . "\n";

                        foreach ($array as $value) {
                            $playlist .= '#EXTINF: ';
                            $playlist .= ltrim(
                                $value,
                                '/'
                            );
                            $playlist .= "\n";
                            $playlist .= $serverUrl;
                            $playlist .= rawurlencode(
                                $path . $value
                            );
                            $playlist .= "\n\n";
                        }

                        header("Content-Type: audio/mpegurl");
                        header("Content-Disposition: attachment; filename=playlist.m3u");

                        echo $playlist;

                        exit;
                    } else {
                        throw new \Exception(
                            $this->translate->translate(
                                'Format is not currently supported. Supported formats are: pls, m3u',
                                'mp3'
                            )
                        );
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

                            if (array_key_exists(
                                'Length',
                                $meta
                            )) {
                                $length = $meta['Length'];
                            } else {
                                $length = '-1';
                            }

                            $keyNum = ($key + 1);

                            $playlist .= 'File';
                            $playlist .= $keyNum;
                            $playlist .= '=' . $serverUrl;
                            $playlist .= rawurlencode(
                                $path . $value
                            );
                            $playlist .= "\n";
                            $playlist .= 'Title' . $keyNum . '=' . basename($value) . "\n";
                            $playlist .= 'Length' . $keyNum . '=' . $length . "\n";
                        }

                        $playlist .= 'Numberofentries=' . count($array) . "\n";
                        $playlist .= 'Version=2' . "\n";

                        header("Content-Type: audio/x-scpls");
                        header("Content-Disposition: attachment; filename=playlist.pls");

                        echo $playlist;

                        exit;
                    } else {
                        throw new \Exception(
                            $this->translate->translate(
                                'Something went wrong and we cannot play this folder',
                                'mp3'
                            )
                        );
                    }
                } else {
                    throw new \Exception(
                        $this->translate->translate(
                            'Format is not currently supported. Supported formats are: pls, m3u',
                            'mp3'
                        )
                    );
                }
            } else {
                throw new \Exception(
                    $this->getBasePath() . rawurldecode($dir) . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Play Single Song
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playSingle($dir)
    {
        try {
            $path = $this->getBasePath() . rawurldecode($dir);

            $file = $this->getConfig()['baseDir'] . rawurldecode($dir);

            $serverUrl = $this->serverUrl->get('serverurl')
                                         ->__invoke(
                                             '/'
                                         );

            clearstatcache();

            if (is_file($path)) {
                if ($this->getConfig()['format'] == 'm3u') {
                    /**
                     * Windows Media Player
                     */
                    header("Content-Type: audio/mpegurl");
                    header("Content-Disposition: attachment; filename=playlist.m3u");

                    echo $serverUrl . rawurlencode($file);

                    exit;
                } elseif ($this->getConfig()['format'] == 'pls') {
                    /**
                     * Winamp
                     */
                    header("Content-Type: audio/x-scpls");
                    header("Content-Disposition: attachment; filename=playlist.pls");

                    $playlist = '[Playlist]' . "\n";
                    $playlist .= 'File1=' . $serverUrl . rawurlencode($file) . "\n";
                    $playlist .= 'Title1=' . basename($path) . "\n";
                    $playlist .= 'Length1=-1' . "\n";
                    $playlist .= 'Numberofentries=1' . "\n";
                    $playlist .= 'Version=2' . "\n";

                    echo $playlist;

                    exit;
                } else {
                    throw new \Exception(
                        $this->translate->translate(
                            'Format is not currently supported. Supported formats are: pls, m3u',
                            'mp3'
                        )
                    );
                }
            } else {
                throw new \Exception(
                    $path . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Download Single
     *
     * @param string $dir
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function downloadSingle($dir)
    {
        try {
            $path = $this->getBasePath() . rawurldecode($dir);

            clearstatcache();

            if (is_file($path)) {
                header("Content-Type: audio/mpeg");
                header("Content-Disposition: attachment; filename=" . basename($path));
                header("Content-Length: " . filesize($path));

                $handle = fopen(
                    $path,
                    'rb'
                );

                $contents = fread(
                    $handle,
                    filesize($path)
                );

                while ($contents) {
                    echo $contents;
                }

                fclose($handle);
            } else {
                throw new \Exception(
                    $path . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Download Folder
     *
     * @param array $params
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function downloadFolder(array $params)
    {
        try {
            if (extension_loaded('Phar')) {
                $dir = rawurldecode($params['dir']);

                clearstatcache();

                if (is_dir($this->getBasePath() . $dir)) {
                    $array = $this->directoryArray($this->getBasePath() . $dir);

                    if (is_array($array) && count($array) > '0') {
                        $filename = basename($dir) . '.' . $params['format'];

                        $tar = new \PharData($filename);

                        foreach ($array as $value) {
                            $tar->addFile(
                                $this->getBasePath() . $dir . $value,
                                basename($value)
                            );
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
                        throw new \Exception(
                            $this->translate->translate(
                                'Something went wrong and we cannot download this folder',
                                'mp3'
                            )
                        );
                    }
                } else {
                    throw new \Exception(
                        $this->getBasePath() . $dir . ' ' . $this->translate->translate(
                            'was not found',
                            'mp3'
                        )
                    );
                }
            } else {
                throw new \Exception(
                    $this->translate->translate('Phar Extension is not loaded')
                );
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
    private function directoryArray($dir)
    {
        try {
            $result_array = [];

            $handle = opendir($dir);

            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        clearstatcache();

                        if (is_dir($dir . '/' . $file) && false) {
                            $list = $this->directoryArray(
                                $dir . '/' . $file
                            );

                            $count = 0;

                            while ($list[$count]) {
                                $result_array[] = $list[$count];

                                $count++;
                            }
                        } else {
                            clearstatcache();

                            if (is_dir($dir . '/' . $file)) {
                                $result_array[] = '/' . $file;
                            }

                            $fileExt = substr(
                                $file,
                                -4
                            );

                            /**
                             * Currently Supported Formats
                             *
                             * .mp3
                             * .m4a
                             */
                            if (is_file($dir . '/' . $file) && $fileExt == '.mp3' || $fileExt == '.m4a') {
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
