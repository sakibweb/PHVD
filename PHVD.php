<?php
/**
 * PHVD is PHP Validation Library
 * @author Sakibur Rahman (@sakibweb)
 * 
 * A PHP library for input validation including data type validation, range validation, format validation, and consistency validation.
 */
class PHVD {
    /**
     * Perform validation on input data based on type and options.
     *
     * @param mixed $input The input data to validate.
     * @param string $type The type of validation ('email', 'phone', 'mobile', 'text', 'url', 'date', 'time', 'alphanumeric', 'alphabetic', 'numeric', 'boolean', 'integer', 'float', 'range', 'pattern', 'password', 'file_type', 'file_size', 'credit_card', 'multiple_of').
     * @param array $options Optional settings for validation.
     * @return array Validation result including 'type', 'required', and additional details based on type.
     */
    public static function check($input, $type, $options = []) {
        $response = [
            'type' => false,
            'required' => isset($options['required']) ? $options['required'] : false,
        ];

        if (empty($input) && $response['required']) {
            return $response;
        }

        $response['required'] = true;

        switch ($type) {
            case 'email':
                if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                    $response['type'] = true;
                    $emailParts = explode('@', $input);
                    $response['username'] = $emailParts[0];
                    $response['domain'] = $emailParts[1];
                    $response['length'] = strlen($input);
                    if (isset($options['domain']) && $options['domain'] !== $response['domain']) {
                        $response['type'] = false;
                    }
                }
                break;

            case 'phone':
                if (preg_match('/^\+?[0-9]{10,15}$/', $input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                    $response['country_code'] = substr($input, 0, strlen($input) - 10);
                    $response['phone_number'] = substr($input, -10);
                }
                break;

            case 'mobile':
                if (preg_match('/^\+?[1-9]{1}[0-9]{1,14}$/', $input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                    $response['country_code'] = substr($input, 0, strlen($input) - 10);
                    $response['mobile_number'] = substr($input, -10);
                }
                break;

            case 'text':
                if (is_string($input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                    if (isset($options['min_length']) && strlen($input) < $options['min_length']) {
                        $response['type'] = false;
                    }
                    if (isset($options['max_length']) && strlen($input) > $options['max_length']) {
                        $response['type'] = false;
                    }
                    if (isset($options['pattern']) && !preg_match($options['pattern'], $input)) {
                        $response['type'] = false;
                    }
                }
                break;

            case 'url':
                if (filter_var($input, FILTER_VALIDATE_URL)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                }
                break;

            case 'date':
                $format = $options['format'] ?? 'Y-m-d';
                $d = DateTime::createFromFormat($format, $input);
                if ($d && $d->format($format) === $input) {
                    $response['type'] = true;
                }
                break;

            case 'time':
                $format = $options['format'] ?? 'H:i:s';
                $d = DateTime::createFromFormat($format, $input);
                if ($d && $d->format($format) === $input) {
                    $response['type'] = true;
                }
                break;

            case 'alphanumeric':
                if (ctype_alnum($input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                }
                break;

            case 'alphabetic':
                if (ctype_alpha($input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                }
                break;

            case 'numeric':
                if (is_numeric($input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                    $response['value'] = (float)$input;
                }
                break;

            case 'boolean':
                if (filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) {
                    $response['type'] = true;
                    $response['value'] = filter_var($input, FILTER_VALIDATE_BOOLEAN);
                }
                break;

            case 'integer':
                if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
                    $response['type'] = true;
                    $response['value'] = (int)$input;
                }
                break;

            case 'float':
                if (filter_var($input, FILTER_VALIDATE_FLOAT) !== false) {
                    $response['type'] = true;
                    $response['value'] = (float)$input;
                }
                break;

            case 'range':
                $min = $options['min'] ?? PHP_INT_MIN;
                $max = $options['max'] ?? PHP_INT_MAX;
                if (is_numeric($input) && $input >= $min && $input <= $max) {
                    $response['type'] = true;
                    $response['value'] = (float)$input;
                }
                break;

            case 'pattern':
                if (isset($options['pattern']) && preg_match($options['pattern'], $input)) {
                    $response['type'] = true;
                    $response['length'] = strlen($input);
                }
                break;

            case 'password':
                $response['type'] = true;
                if (isset($options['min_length']) && strlen($input) < $options['min_length']) {
                    $response['type'] = false;
                }
                if (isset($options['max_length']) && strlen($input) > $options['max_length']) {
                    $response['type'] = false;
                }
                if (isset($options['require_special_chars']) && !preg_match('/[\W_]/', $input)) {
                    $response['type'] = false;
                }
                if (isset($options['require_numbers']) && !preg_match('/\d/', $input)) {
                    $response['type'] = false;
                }
                if (isset($options['require_uppercase']) && !preg_match('/[A-Z]/', $input)) {
                    $response['type'] = false;
                }
                if (isset($options['require_lowercase']) && !preg_match('/[a-z]/', $input)) {
                    $response['type'] = false;
                }
                break;

            case 'file_type':
                $allowed_types = $options['allowed_types'] ?? [];
                $file_ext = pathinfo($input, PATHINFO_EXTENSION);
                if (in_array($file_ext, $allowed_types)) {
                    $response['type'] = true;
                    $response['file_extension'] = $file_ext;
                }
                break;

            case 'file_size':
                $max_size = $options['max_size'] ?? PHP_INT_MAX;
                if (file_exists($input) && filesize($input) <= $max_size) {
                    $response['type'] = true;
                    $response['file_size'] = filesize($input);
                }
                break;

            case 'credit_card':
                if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $input)) {
                    $response['type'] = true;
                    $response['card_type'] = 'Visa';
                }
                break;

            case 'multiple_of':
                $multiple = $options['multiple'] ?? 1;
                if (is_numeric($input) && $input % $multiple === 0) {
                    $response['type'] = true;
                    $response['value'] = (float)$input;
                }
                break;

            default:
                $response['type'] = false;
                break;
        }
        return $response;
    }
}
?>
