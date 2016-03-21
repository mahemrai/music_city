<?php
namespace MusicCity\Services\App;

/**
 * @package MusicCity
 * @author  Mahendra Rai
 */
class ListGenerator
{
    /**
     * @var array
     */
    protected $alphabets;

    public function __construct()
    {
        $this->alphabets = range('a', 'z');
    }

    /**
     * @param  array $dataArray
     * @param  string $selector
     * @return array
     */
    public function createAlphabeticalList($dataArray, $selector)
    {
        $sortedArray = array();

        foreach ($this->alphabets as $alphabet) {
            foreach ($dataArray as $item) {
                $letter = strtolower(substr($item->$selector, 0, 1));
                if (strcasecmp($alphabet, $letter) == 0) {
                    $sortedArray[$alphabet][] = array(
                        'id'   => $item->id,
                        'name' => $item->name
                    );
                }
            }
        }

        return $sortedArray;
    }
}