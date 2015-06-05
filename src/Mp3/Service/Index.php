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
     * Directory Listing
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function index(array $params)
    {
        try {
            $directoryListing = $this->directoryListing($params);

            $count = count($directoryListing);

            /**
             * Remove Last 2 arrays
             */
            $output = array_slice(
                $directoryListing,
                0,
                $count - 2
            );

            /**
             * Paginate or Error if nothing found
             */
            if (is_array($directoryListing) && $count > '0') {
                $paginator = new Paginator(new ArrayAdapter($output));

                $paginator->setDefaultItemCountPerPage($count);
            } else {
                throw new \Exception(
                    $directoryListing . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }

            $path = (array_key_exists(
                'dir',
                $params
            ))
                ? $params['dir']
                : null;

            return [
                'paginator'     => $paginator,
                'path'          => $path,
                'totalLength'   => $directoryListing['totalLength'],
                'totalFileSize' => $directoryListing['totalFileSize'],
                'search'        => (is_file($this->getSearchFile()))
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Play All
     *
     * @param string $base64
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playAll($base64)
    {
        try {
            /**
             * Decode Path
             */
            $base64 = base64_decode($base64);

            /**
             * Path to File on Disk
             */
            $path = $this->getSearchPath() . '/' . $base64;

            /**
             * Path to Web URL
             */
            $file = ltrim($this->getBaseDir() . '/' . $base64, '/');

            /**
             * Server URL
             */
            $serverUrl = $this->serverUrl->get('serverurl')
                                         ->__invoke('/');

            clearstatcache();

            if (is_dir($path)) {
                $array = $this->directoryListing($base64);

                if (is_array($array) && count($array) > '0') {
                    switch ($this->getFormat()) {
                        /**
                         * Windows Media Player
                         */
                        case 'm3u':
                            $playlist = '#EXTM3U' . "\n";

                            foreach ($array as $value) {
                                $playlist .= '#EXTINF: ';
                                $playlist .= $value['name'];
                                $playlist .= "\n";
                                $playlist .= $serverUrl;
                                $playlist .= str_replace(' ', '%20', $file . '/' . $value['name']);
                                $playlist .= "\n\n";
                            }

                            header("Content-Type: audio/mpegurl");
                            header("Content-Disposition: attachment; filename=" . basename($path) . ".m3u");

                            echo $playlist;

                            exit;

                            break;

                        /**
                         * Winamp
                         */
                        case 'pls':
                            $playlist = '[Playlist]' . "\n";

                            foreach ($array as $key => $value) {
                                $id3 = $this->getId3($path . '/' . $value['name']);

                                $name = !empty($id3['comments_html']['title'])
                                    ? implode(
                                        '<br>',
                                        $id3['comments_html']['title']
                                    )
                                    : basename($value['name']);

                                $length = !empty($id3['playtime_string'])
                                    ? $id3['playtime_string']
                                    : '-1';

                                $keyNum = ($key + 1);

                                $playlist .= 'File';
                                $playlist .= $keyNum;
                                $playlist .= '=' . $serverUrl;
                                $playlist .= str_replace(' ', '%20', $file . '/' . $value['name']);
                                $playlist .= "\n";
                                $playlist .= 'Title' . $keyNum . '=' . $name . "\n";
                                $playlist .= 'Length' . $keyNum . '=' . $this->convertTime($length) . "\n";
                            }

                            $playlist .= 'Numberofentries=' . count($array) . "\n";
                            $playlist .= 'Version=2' . "\n";

                            header("Content-Type: audio/x-scpls");
                            header("Content-Disposition: attachment; filename=" . basename($path) . ".pls");

                            echo $playlist;

                            exit;

                            break;

                        /**
                         * Error
                         */
                        default:
                            throw new \Exception(
                                $this->translate->translate(
                                    'Format is not currently supported. Supported formats are: pls, m3u',
                                    'mp3'
                                )
                            );
                    }
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
     * Play Single Song
     *
     * @param string $base64
     *
     * @return null|string|void
     * @throws \Exception
     */
    public function playSingle($base64)
    {
        try {
            /**
             * Decode Path
             */
            $base64 = base64_decode($base64);

            /**
             * Path to File on Disk
             */
            $path = $this->getSearchPath() . '/' . $base64;

            /**
             * Path to Web URL
             */
            $file = ltrim($this->getBaseDir() . '/' . $base64, '/');

            /**
             * Server URL
             */
            $serverUrl = $this->serverUrl->get('serverurl')
                                         ->__invoke('/');

            clearstatcache();

            if (is_file($path)) {
                switch ($this->getFormat()) {
                    /**
                     * The most common playlist format
                     */
                    case 'm3u':
                        header("Content-Type: audio/mpegurl");
                        header("Content-Disposition: attachment; filename=" . basename($file) . ".m3u");

                        echo $serverUrl . str_replace(' ', '%20', $file);

                        exit;

                        break;

                    /**
                     * Shoutcast / Icecast / Winamp
                     */
                    case 'pls':
                        header("Content-Type: audio/x-scpls");
                        header("Content-Disposition: attachment; filename=" . basename($file) . ".pls");

                        $id3 = $this->getId3($path);

                        $name = !empty($id3['comments_html']['title'])
                            ? implode(
                                '<br>',
                                $id3['comments_html']['title']
                            )
                            : basename($path);

                        $playlist = '[Playlist]' . "\n";
                        $playlist .= 'File1=' . $serverUrl . str_replace(' ', '%20', $file) . "\n";
                        $playlist .= 'Title1=' . $name . "\n";
                        $playlist .= 'Length1=-1' . "\n";
                        $playlist .= 'Numberofentries=1' . "\n";
                        $playlist .= 'Version=2' . "\n";

                        echo $playlist;

                        exit;

                        break;

                    /**
                     * Error
                     */
                    default:
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
     * Download Folder
     *
     * @param array $params
     *
     * @return void
     * @throws \Exception
     */
    public function downloadFolder(array $params)
    {
        try {
            if (extension_loaded('Phar')) {
                /**
                 * Decode Path
                 */
                $base64 = base64_decode($params['dir']);

                /**
                 * Path to File on Disk
                 */
                $path = $this->getSearchPath() . '/' . $base64;

                clearstatcache();

                if (is_dir($path)) {
                    $array = $this->directoryListing($base64);

                    if (is_array($array) && count($array) > '0') {
                        unset($array['totalLength']);
                        unset($array['totalFileSize']);

                        $filename = $path . '.' . $params['format'];

                        $phar = new \PharData($filename);

                        foreach ($array as $value) {
                            $phar->addFile(
                                $value['fullPath'],
                                $value['name']
                            );
                        }

                        switch ($params['format']) {
                            case 'tar':
                                header('Content-Type: application/x-tar');
                                break;

                            case 'zip':
                                header('Content-Type: application/zip');
                                break;

                            case 'bz2':
                                header('Content-Type: application/x-bzip2');
                                break;

                            case 'rar':
                                header('Content-Type: x-rar-compressed');
                                break;
                        }

                        header('Content-disposition: attachment; filename=' . basename($filename));
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
                        $path . ' ' . $this->translate->translate(
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
     * Download Single
     *
     * @param string $base64
     *
     * @return void
     * @throws \Exception
     */
    public function downloadSingle($base64)
    {
        try {
            /**
             * Path to File on Disk
             */
            $path = $this->getSearchPath() . '/' . base64_decode($base64);

            clearstatcache();

            if (is_file($path)) {
                header("Content-Type: audio/mpeg");
                header("Content-Disposition: attachment; filename=" . basename($this->cleanPath($path)));
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
     * Directory Listing
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    private function directoryListing($params)
    {
        try {
            /**
             * Which path are we scanning?
             *
             * 1) Array
             * 2) String
             * 3) Default
             */
            if (is_array($params) && !empty($params['dir'])) {
                $path = scandir($this->getSearchPath() . '/' . base64_decode($params['dir']));
            } elseif (!is_array($params) && !empty($params)) {
                $path = scandir($this->getSearchPath() . '/' . $params);
            } else {
                $path = scandir($this->getSearchPath());
            }

            $array = [];

            $totalLength = null;

            $totalFileSize = null;

            if (is_array($path) && count($path) > '0') {
                foreach ($path as $name) {
                    if ($name != '.' && $name != '..') {
                        $fileExtension = substr(
                            strrchr(
                                $name,
                                '.'
                            ),
                            1
                        );

                        /**
                         * Set Paths
                         *
                         * 1) Array
                         * 2) String
                         * 3) Default
                         */
                        if (is_array($params) && !empty($params['dir'])) {
                            $basePath = base64_decode($params['dir']) . '/' . $name;

                            $fullPath = $this->getSearchPath() . '/' . base64_decode($params['dir']) . '/' . $name;
                        } elseif (is_string($params) && !empty($params)) {
                            $basePath = $params . '/' . $name;

                            $fullPath = $this->getSearchPath() . '/' . $params . '/' . $name;
                        } else {
                            $basePath = $name;

                            $fullPath = $this->getSearchPath() . '/' . $name;
                        }

                        clearstatcache();

                        /**
                         * Directory
                         */
                        if (is_dir($fullPath)) {
                            $array[] = [
                                'name'     => $name,
                                'basePath' => $basePath,
                                'base64'   => base64_encode($basePath),
                                'fullPath' => $fullPath,
                                'type'     => 'dir'
                            ];
                        }

                        /**
                         * File
                         */
                        if (is_file($fullPath) && in_array(
                                '.' . $fileExtension,
                                $this->getExtensions()
                            )
                        ) {
                            $id3 = $this->getId3($fullPath);

                            $title = !empty($id3['comments_html']['title'])
                                ? implode(
                                    '<br>',
                                    $id3['comments_html']['title']
                                )
                                : $name;

                            $bitRate = !empty($id3['audio']['bitrate'])
                                ? round($id3['audio']['bitrate'] / '1000')
                                : '-';

                            $length = !empty($id3['playtime_string'])
                                ? $id3['playtime_string']
                                : '-';

                            $fileSize = !empty($id3['filesize'])
                                ? $id3['filesize']
                                : '-';

                            $totalLength += $this->convertTime($length);

                            $totalFileSize += $fileSize;

                            $array[] = [
                                'name'     => $name,
                                'basePath' => $basePath,
                                'base64'   => base64_encode($basePath),
                                'fullPath' => $fullPath,
                                'type'     => 'file',
                                'id3'      => [
                                    'title'    => $title,
                                    'bitRate'  => $bitRate,
                                    'length'   => $length,
                                    'fileSize' => $fileSize
                                ]
                            ];
                        }
                    }
                }

                sort($array);

                if ($totalLength > '0') {
                    $totalLength = sprintf(
                        "%d:%02d",
                        ($totalLength / '60'),
                        $totalLength % '60'
                    );
                }

                $array['totalLength'] = $totalLength;

                $array['totalFileSize'] = $totalFileSize;
            }

            return $array;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
