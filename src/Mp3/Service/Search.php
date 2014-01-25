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
 * Class Search
 *
 * @package Mp3\Service
 */
class Search extends ServiceProvider implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function Search($name)
    {
        try {
            $array = array();

            $total_length = null;
            $total_size = null;

            if ($name != null) {
                $filename = $this->getConfig()['search_file'];

                clearstatcache();

                if (is_file($filename)) {
                    if (filesize($filename) <= '0') {
                        header('Location: /mp3/search/flash/' . $this->getTranslator()->translate('The search file is currently empty. Use the Import Tool to populate the Search Results', 'mp3'));
                        exit;
                    }

                    $handle = fopen($filename, 'r');
                    $contents = fread($handle, filesize($filename));

                    $unserialize = preg_grep('/' . $name . '/i', unserialize($contents));

                    fclose($handle);

                    if (count($unserialize) > '0') {
                        foreach ($unserialize as $search) {
                            $this->MemoryUsage();

                            clearstatcache();

                            $dir = preg_replace('/(\/+)/', '/', $search);

                            if (is_dir($this->getBasePath() . $search)) {
                                $array[] = array(
                                    'name'     => ltrim($dir, '/'),
                                    'location' => $dir,
                                    'type'     => 'dir'
                                );
                            }

                            if (is_file($this->getBasePath() . $search)) {
                                $calculate = new Calculate($this->getBasePath() . $dir);
                                $meta = $calculate->get_metadata();

                                $array[] = array(
                                    'name'     => ltrim($dir, '/'),
                                    'location' => $dir,
                                    'type'     => 'file',
                                    'bit_rate' => (isset($meta['Bitrate'])) ? $meta['Bitrate'] : '-',
                                    'length'   => (isset($meta['Length mm:ss'])) ? $meta['Length mm:ss'] : '-',
                                    'size'     => (isset($meta['Filesize'])) ? $meta['Filesize'] : '-'
                                );

                                $total_length += (isset($meta['Length'])) ? $meta['Length'] : '0';
                                $total_size += (isset($meta['Filesize'])) ? $meta['Filesize'] : '0';
                            }
                        }
                    }
                } else {
                    throw new \Exception($filename . ' ' . $this->getTranslator()->translate('was not found', 'mp3'));
                }
            }

            $paginator = new Paginator(new ArrayAdapter($array));
            $paginator->setDefaultItemCountPerPage((count($array) > '0') ? count($array) : '1');

            if ($total_length > '0') {
                $total_length = sprintf("%d:%02d", ($total_length / 60), $total_length % 60);
            }

            return array(
                'paginator'    => $paginator,
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
    public function Import()
    {
        try {
            ini_set('max_execution_time', '0');

            clearstatcache();

            if (is_dir($this->getBasePath())) {
                $directory = new \RecursiveDirectoryIterator($this->getBasePath(), \FilesystemIterator::FOLLOW_SYMLINKS);

                $filename = $this->getConfig()['search_file'];

                clearstatcache();

                if (is_file($filename)) {
                    $handle = fopen($filename, 'w');

                    if (is_writable($filename)) {
                        if (!$handle) {
                            throw new \Exception($this->getTranslator()->translate('Cannot Open File') . ': ' . $filename);
                        }
                    } else {
                        throw new \Exception($this->getTranslator()->translate('File Is Not Writable') . ': ' . $filename);
                    }

                    $array = array();

                    /**
                     * @var \RecursiveDirectoryIterator $current
                     */
                    foreach (new \RecursiveIteratorIterator($directory) as $current) {
                        /**
                         * Do not index the main folder
                         */
                        if (substr($current->getPathName(), 0, -2) != $this->getBasePath()) {
                            /**
                             * Remove . and .. but translate the path into the base folder name
                             */
                            if (basename($current->getPathName()) == '.') {
                                $array[] = str_replace($this->getBasePath(), '', substr($current->getPathName(), 0, -2));
                            } elseif (basename($current->getPathName()) != '..' && substr($current->getPathName(), -4) == '.mp3') {
                                $array[] = str_replace($this->getBasePath(), '', $current->getPathName());
                            }
                        }
                    }

                    sort($array);

                    fwrite($handle, serialize($array));

                    fclose($handle);
                } else {
                    throw new \Exception($filename . ' ' . $this->getTranslator()->translate('was not found', 'mp3'));
                }
            } else {
                throw new \Exception($this->getBasePath() . ' ' . $this->getTranslator()->translate('was not found', 'mp3'));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function Help($help)
    {
        $green = "\033[49;32m";
        $end = "\033[0m";

        $array['import'] = array(
            'Import Search Results',
            $green . 'mp3 import' . $end,
            '',
            'Option          Description             Required    Default    Available Options',
            '--confirm=      Display Confirmation    No          Yes        Yes, No'
        );

        return implode("\n", $array[$help]) . "\n";
    }

    public function MemoryUsage()
    {
        $remaining = (memory_get_peak_usage() - memory_get_usage());

        $left = (memory_get_peak_usage() + $remaining);

        if ($left < memory_get_peak_usage(true)) {
            header('Location: /mp3/search/flash/' . $this->getTranslator()->translate('PHP Ran Out of Memory. Please Try Again', 'mp3'));
            exit;
        }
    }
}
