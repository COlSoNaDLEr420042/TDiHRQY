<?php
// 代码生成时间: 2025-09-23 17:02:21
class MathToolkit {

    /**<
     * Adds two numbers.
     *
     * @param float $a The first number.
     * @param float $b The second number.
     * @return float The sum of the two numbers.
     * @throws InvalidArgumentException If the inputs are not numbers.
     */
    public function add($a, $b) {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new InvalidArgumentException('Both parameters must be numeric.');
        }
        return $a + $b;
    }

    /**<
     * Subtracts the second number from the first.
     *
     * @param float $a The first number.
     * @param float $b The second number.
     * @return float The difference between the two numbers.
     * @throws InvalidArgumentException If the inputs are not numbers.
     */
    public function subtract($a, $b) {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new InvalidArgumentException('Both parameters must be numeric.');
        }
        return $a - $b;
    }

    /**<
     * Multiplies two numbers.
     *
     * @param float $a The first number.
     * @param float $b The second number.
     * @return float The product of the two numbers.
     * @throws InvalidArgumentException If the inputs are not numbers.
     */
    public function multiply($a, $b) {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new InvalidArgumentException('Both parameters must be numeric.');
        }
        return $a * $b;
    }

    /**<
     * Divides the first number by the second.
     *
     * @param float $a The first number.
     * @param float $b The second number.
     * @return float The quotient of the two numbers.
     * @throws InvalidArgumentException If the inputs are not numbers or if division by zero occurs.
     */
    public function divide($a, $b) {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new InvalidArgumentException('Both parameters must be numeric.');
        }
        if ($b == 0) {
            throw new InvalidArgumentException('Cannot divide by zero.');
        }
        return $a / $b;
    }

}
