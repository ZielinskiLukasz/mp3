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
     * @param string $name
     *
     * @return array|Paginator
     * @throws \Exception
     */
    public function find($name)
    {
        try {
            $array = [];

            $totalLength = null;
            $totalSize = null;

            if ($name != null) {
                $filename = $this->getConfig()['searchFile'];

                clearstatcache();

                if (is_file($filename)) {
                    if (filesize($filename) <= '0') {
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
                        $filename,
                        'r'
                    );

                    $contents = fread(
                        $handle,
                        filesize($filename)
                    );

                    $unserialize = preg_grep(
                        '/' . $name . '/i',
                        unserialize($contents)
                    );

                    fclose($handle);

                    if (count($unserialize) > '0') {
                        foreach ($unserialize as $search) {
                            $this->memoryUsage();

                            clearstatcache();

                            $dir = preg_replace(
                                '/(\/+)/',
                                '/',
                                $search
                            );

                            if (is_dir($this->getBasePath() . $search)) {
                                $array[] = [
                                    'name'     => ltrim(
                                        $dir,
                                        '/'
                                    ),
                                    'location' => $dir,
                                    'type'     => 'dir'
                                ];
                            }

                            if (is_file($this->getBasePath() . $search)) {
                                $ThisFileInfo = $this->getId3($this->getBasePath() . $dir);

                                $name = htmlentities(
                                    !empty($ThisFileInfo['comments_html']['title'])
                                        ? implode(
                                        '<br>',
                                        $ThisFileInfo['comments_html']['title']
                                    )
                                        : ltrim(
                                        $dir,
                                        '/'
                                    )
                                );

                                $bitRate = htmlentities(
                                    !empty($ThisFileInfo['audio']['bitrate'])
                                        ? round($ThisFileInfo['audio']['bitrate'] / 1000)
                                        : '-'
                                );

                                $length = htmlentities(
                                    !empty($ThisFileInfo['playtime_string'])
                                        ? $ThisFileInfo['playtime_string']
                                        : '-'
                                );

                                $filesize = !empty($ThisFileInfo['filesize'])
                                    ? $ThisFileInfo['filesize']
                                    : '-';

                                $array[] = [
                                    'name'     => $name,
                                    'bit_rate' => $bitRate,
                                    'length'   => $length,
                                    'size'     => $filesize,
                                    'location' => $dir,
                                    'type'     => 'file',
                                ];

                                $totalLength += $this->convertTime($length);

                                $totalSize += $filesize;
                            }
                        }
                    }
                } else {
                    throw new \Exception(
                        $filename . ' ' . $this->translate->translate(
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
                    ($totalLength / 60),
                    $totalLength % 60
                );
            }

            return [
                'paginator'    => $paginator,
                'total_length' => $totalLength,
                'total_size'   => $totalSize,
                'search'       => (is_file($this->getConfig()['searchFile']))
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

            if (is_dir($this->getBasePath())) {
                $directory = new \RecursiveDirectoryIterator(
                    $this->getBasePath(),
                    \FilesystemIterator::FOLLOW_SYMLINKS
                );

                $filename = $this->getConfig()['searchFile'];

                clearstatcache();

                if (!touch($filename)) {
                    throw new \Exception(
                        $filename . ' ' . $this->translate->translate(
                            'could not be created',
                            'mp3'
                        )
                    );
                }

                if (is_file($filename)) {
                    $handle = fopen(
                        $filename,
                        'w'
                    );

                    if (is_writable($filename)) {
                        if (!$handle) {
                            throw new \Exception(
                                $this->translate->translate('Cannot Open File') . ': ' . $filename
                            );
                        }
                    } else {
                        throw new \Exception(
                            $this->translate->translate('File Is Not Writable') . ': ' . $filename
                        );
                    }

                    $array = [];

                    /**
                     * @var \RecursiveDirectoryIterator $current
                     */
                    foreach (new \RecursiveIteratorIterator($directory) as $current) {
                        $mainFolder = substr(
                            $current->getPathName(),
                            0,
                            -2
                        );

                        $mainFile = substr(
                            $current->getPathName(),
                            -4
                        );

                        /**
                         * Do not index the main folder
                         */
                        if ($mainFolder != $this->getBasePath()
                        ) {
                            /**
                             * Remove . and .. but translate the path into the base folder name
                             */
                            if (basename($current->getPathName()) == '.') {
                                $array[] = str_replace(
                                    $this->getBasePath(),
                                    '',
                                    substr(
                                        $current->getPathName(),
                                        0,
                                        -2
                                    )
                                );
                            } elseif (
                                basename($current->getPathName()) != '..' && $mainFile == '.mp3'
                            ) {
                                $array[] = str_replace(
                                    $this->getBasePath(),
                                    '',
                                    $current->getPathName()
                                );
                            }
                        }
                    }

                    sort($array);

                    fwrite(
                        $handle,
                        serialize($array)
                    );

                    fclose($handle);
                } else {
                    throw new \Exception(
                        $filename . ' ' . $this->translate->translate(
                            'was not found',
                            'mp3'
                        )
                    );
                }
            } else {
                throw new \Exception(
                    $this->getBasePath() . ' ' . $this->translate->translate(
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
     * @return void
     */
    public function memoryUsage()
    {
        $remaining = (memory_get_peak_usage() - memory_get_usage());

        $left = (memory_get_peak_usage() + $remaining);

        if ($left < memory_get_peak_usage(true)) {
            $errorString = 'PHP Ran Out of Memory. Please Try Again';

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
    }
}
