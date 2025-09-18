<?php
// 代码生成时间: 2025-09-19 05:31:57
 * This tool allows users to compute hash values of strings.
 */

use Phalcon\Mvc\Controller;
use Phalcon\Crypt;

class HashCalculatorController extends Controller
{
    /**
     * Calculate and return the hash of a given string.
     *
     * @param string $input The string to be hashed.
     * @return string The computed hash value.
     */
    public function calculateAction($input)
    {
        try {
            // Initialize the Phalcon Crypt component.
            $crypt = new Crypt();
            $crypt->setKey('your-secret-key'); // Replace with your own secret key.

            // Compute the hash.
            $hash = $crypt->hash($input);

            // Return the hash value.
            return $this-&gt;response-&gt;setContent($hash);

        } catch (Exception $e) {
            // Handle any errors that occur during the hash calculation.
            return $this-&gt;response-&gt;setContent("Error: " . $e-&gt;getMessage());
        }
    }
}
