<?php
// 代码生成时间: 2025-09-17 11:49:58
class SortingService {
    /**
     * Sort array using Bubble Sort algorithm.
     *
     * @param array $array
     * @return array
     */
    public function bubbleSort(array $array): array {
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // Swap elements
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }
        return $array;
    }

    /**
     * Sort array using Selection Sort algorithm.
     *
     * @param array $array
     * @return array
     */
    public function selectionSort(array $array): array {
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            // Find the minimum element in the remaining array
            $min_idx = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                if ($array[$j] < $array[$min_idx]) {
                    $min_idx = $j;
                }
            }
            // Swap the found minimum element with the first element of the unsorted part
            if ($min_idx != $i) {
                $temp = $array[$i];
                $array[$i] = $array[$min_idx];
                $array[$min_idx] = $temp;
            }
        }
        return $array;
    }

    /**
     * Sort array using Insertion Sort algorithm.
     *
     * @param array $array
     * @return array
     */
    public function insertionSort(array $array): array {
        for ($i = 1; $i < count($array); $i++) {
            $key = $array[$i];
            $j = $i - 1;
            while ($j >= 0 && $array[$j] > $key) {
                $array[$j + 1] = $array[$j];
                $j--;
            }
            $array[$j + 1] = $key;
        }
        return $array;
    }

    /**
     * Sort array using Merge Sort algorithm.
     *
     * @param array $array
     * @return array
     */
    public function mergeSort(array $array): array {
        if (count($array) == 1) {
            return $array;
        }
        $mid = count($array) / 2;
        $left = array_slice($array, 0, $mid);
        $right = array_slice($array, $mid);

        $left = $this->mergeSort($left);
        $right = $this->mergeSort($right);

        return $this->merge($left, $right);
    }

    /**
     * Merge two sorted arrays.
     *
     * @param array $left
     * @param array $right
     * @return array
     */
    private function merge(array $left, array $right): array {
        $result = [];
        while (count($left) > 0 && count($right) > 0) {
            if ($left[0] < $right[0]) {
                array_push($result, array_shift($left));
            } else {
                array_push($result, array_shift($right));
            }
        }

        while (count($left) > 0) {
            array_push($result, array_shift($left));
        }

        while (count($right) > 0) {
            array_push($result, array_shift($right));
        }

        return $result;
    }
}
