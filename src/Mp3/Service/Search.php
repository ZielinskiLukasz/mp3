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
 * Class Search
 *
 * @package Mp3\Service
 */
class Search extends ServiceProvider implements SearchInterface
{
    /**
     * Search
     *
     * @param string $string
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function find($string)
    {
        try {
            $array = [];

            $totalLength = null;

            $totalFileSize = null;

            if ($string != null) {
                $searchFile = $this->getSearchFile();

                clearstatcache();

                if (is_file($searchFile)) {
                    /**
                     * Error: Search File is Empty
                     */
                    if (filesize($searchFile) <= '0') {
                        $errorString = 'The search file is currently empty. ';
                        $errorString .= 'Use the Import Tool to populate the Search Results';

                        $translateError = $this->translate->translate(
                            $errorString,
                            'mp3'
                        );

                        $location = $this->serverUrl
                            ->get('url')
                            ->__invoke(
                                'mp3-search',
                                [
                                    'flash' => $translateError
                                ]
                            );

                        header('Location: ' . $location);

                        exit;
                    }

                    $handle = fopen(
                        $searchFile,
                        'r'
                    );

                    $contents = fread(
                        $handle,
                        filesize($searchFile)
                    );

                    $unserialize = preg_grep(
                        '/' . $string . '/i',
                        unserialize($contents)
                    );

                    fclose($handle);

                    if (count($unserialize) > '0') {
                        foreach ($unserialize as $path) {
                            $this->memoryUsage();

                            /**
                             * Set Paths
                             */
                            $basePath = $path;

                            $fullPath = $this->getSearchPath() . '/' . $path;

                            clearstatcache();

                            if (is_dir($fullPath)) {
                                $array[] = [
                                    'name'     => $path,
                                    'basePath' => $basePath,
                                    'base64'   => base64_encode(
                                        ltrim(
                                            $basePath,
                                            '/'
                                        )
                                    ),
                                    'fullPath' => $fullPath,
                                    'type'     => 'dir'
                                ];
                            }

                            if (is_file($fullPath)) {
                                $id3 = $this->getId3($fullPath);

                                $title = !empty($id3['comments_html']['title'])
                                    ? implode(
                                        '<br>',
                                        $id3['comments_html']['title']
                                    )
                                    : basename($fullPath);

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
                                    'name'     => $path,
                                    'basePath' => $basePath,
                                    'base64'   => base64_encode(
                                        ltrim(
                                            $basePath,
                                            '/'
                                        )
                                    ),
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
                } else {
                    throw new \Exception(
                        $searchFile . ' ' . $this->translate->translate(
                            'was not found',
                            'mp3'
                        )
                    );
                }
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
                    ($totalLength / '60'),
                    $totalLength % '60'
                );
            }

            return [
                'paginator'     => $paginator,
                'totalLength'   => $totalLength,
                'totalFileSize' => $totalFileSize,
                'search'        => (is_file($this->getSearchFile()))
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Import Search Results
     *
     * @throws \Exception
     */
    public function import()
    {
        try {
            ini_set(
                'max_execution_time',
                '0'
            );

            clearstatcache();

            if (is_dir($this->getSearchPath())) {
                $searchPath = new \RecursiveDirectoryIterator(
                    $this->getSearchPath(),
                    \FilesystemIterator::FOLLOW_SYMLINKS
                );

                $searchFile = $this->getSearchFile();

                clearstatcache();

                if (!touch($searchFile)) {
                    throw new \Exception(
                        $searchFile . ' ' . $this->translate->translate(
                            'could not be created',
                            'mp3'
                        )
                    );
                }

                if (is_file($searchFile)) {
                    $handle = fopen(
                        $searchFile,
                        'w'
                    );

                    if (is_writable($searchFile)) {
                        if (!$handle) {
                            throw new \Exception(
                                $this->translate->translate('Cannot Open File') . ': ' . $searchFile
                            );
                        }
                    } else {
                        throw new \Exception(
                            $this->translate->translate('File Is Not Writable') . ': ' . $searchFile
                        );
                    }

                    $array = [];

                    /**
                     * @var \RecursiveDirectoryIterator $current
                     */
                    foreach (new \RecursiveIteratorIterator($searchPath) as $current) {
                        $basePathName = basename($current->getPathname());

                        if ($basePathName != '..') {
                            $fileExtension = substr(
                                strrchr(
                                    $basePathName,
                                    '.'
                                ),
                                1
                            );

                            clearstatcache();

                            /**
                             * Directory
                             */
                            if (is_dir($current->getPathname())) {
                                $directoryName = substr(
                                    $current->getPathName(),
                                    0,
                                    -2
                                );

                                $replaceDirectory = str_replace(
                                    $this->getSearchPath(),
                                    '',
                                    $directoryName
                                );

                                $directoryTrim = ltrim(
                                    $replaceDirectory,
                                    '/'
                                );

                                $array[] = $directoryTrim;
                            }

                            /**
                             * File
                             */
                            if (is_file($current->getPathname()) && in_array(
                                    '.' . $fileExtension,
                                    $this->getExtensions()
                                )
                            ) {
                                $fileName = $current->getPathname();

                                $replaceFileName = str_replace(
                                    $this->getSearchPath(),
                                    '',
                                    $fileName
                                );

                                $fileNameTrim = ltrim(
                                    $replaceFileName,
                                    '/'
                                );

                                $array[] = $fileNameTrim;
                            }
                        }
                    }

                    $result = array_unique($array);

                    sort($result);

                    fwrite(
                        $handle,
                        serialize($result)
                    );

                    fclose($handle);
                } else {
                    throw new \Exception(
                        $searchFile . ' ' . $this->translate->translate(
                            'was not found',
                            'mp3'
                        )
                    );
                }
            } else {
                throw new \Exception(
                    $this->getBaseDir() . ' ' . $this->translate->translate(
                        'was not found',
                        'mp3'
                    )
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * Parse Help
     *
     * @param string $help
     *
     * @return string
     */
    public function help($help)
    {
        $green = "\033[49;32m";
        $end = "\033[0m";

        $array['import'] = [
            'Import Search Results',
            $green . 'mp3 import' . $end,
            '',
            'Option          Description             Required    Default    Available Options',
            '--confirm=      Display Confirmation    No          Yes        Yes, No'
        ];

        $implode = implode(
            "\n",
            $array[$help]
        );

        return $implode . "\n";
    }

    /**
     * Determines PHP's Memory Usage Overflow
     *
     * Usage: Set memoryLimit in mp3.global.php to true in order to use this feature
     *
     * @return void
     */
    public function memoryUsage()
    {
        if ($this->getMemoryLimit()) {
            $remaining = (memory_get_peak_usage() - memory_get_usage());

            $left = (memory_get_peak_usage() + $remaining);

            if ($left < memory_get_peak_usage(true)) {
                $translateError = $this->translate->translate(
                    'PHP Ran Out of Memory. Please Try Again',
                    'mp3'
                );

                $location = $this->serverUrl
                    ->get('url')
                    ->__invoke(
                        'mp3-search',
                        [
                            'flash' => $translateError
                        ]
                    );

                header('Location: ' . $location);

                exit;
            }
        }
    }
}
