# PHVD
## PHVD is PHP Validation Library
### This PHP library for input validation including data type validation, range validation, format validation, and consistency validation.
```
// Example usage
$emailValidation = PHVD::check("sakib.sr20@gmail.com", "email", ['domain' => 'gmail.com']);
print_r($emailValidation);

$phoneValidation = PHVD::check("+12345678901", "phone");
print_r($phoneValidation);

$phoneValidation = PHVD::check("+12345678901", "mobile");
print_r($phoneValidation);

$textValidation = PHVD::check("Some text", "text", ['min_length' => 5, 'max_length' => 50]);
print_r($textValidation);

$urlValidation = PHVD::check("https://www.example.com", "url");
print_r($urlValidation);

$dateValidation = PHVD::check("2023-06-15", "date", ['format' => 'Y-m-d']);
print_r($dateValidation);

$timeValidation = PHVD::check("14:30:00", "time", ['format' => 'H:i:s']);
print_r($timeValidation);

$alphanumericValidation = PHVD::check("abc123", "alphanumeric");
print_r($alphanumericValidation);

$alphabeticValidation = PHVD::check("abcdef", "alphabetic");
print_r($alphabeticValidation);

$numericValidation = PHVD::check("123456", "numeric");
print_r($numericValidation);

$booleanValidation = PHVD::check("true", "boolean");
print_r($booleanValidation);

$integerValidation = PHVD::check("123", "integer");
print_r($integerValidation);

$floatValidation = PHVD::check("123.45", "float");
print_r($floatValidation);

$rangeValidation = PHVD::check("50", "range", ['min' => 1, 'max' => 100]);
print_r($rangeValidation);

$patternValidation = PHVD::check("2023-06-15", "pattern", ['pattern' => '/^\d{4}-\d{2}-\d{2}$/']);
print_r($patternValidation);

$passwordValidation = PHVD::check("P@ssw0rd", "password", ['min_length' => 8, 'require_special_chars' => true, 'require_numbers' => true, 'require_uppercase' => true, 'require_lowercase' => true]);
print_r($passwordValidation);

$fileTypeValidation = PHVD::check("example.pdf", "file_type", ['allowed_types' => ['pdf', 'docx']]);
print_r($fileTypeValidation);

$fileSizeValidation = PHVD::check("example.pdf", "file_size", ['max_size' => 2048000]);
print_r($fileSizeValidation);

$creditCardValidation = PHVD::check("4111111111111111", "credit_card");
print_r($creditCardValidation);

$multipleOfValidation = PHVD::check("10", "multiple_of", ['multiple' => 5]);
print_r($multipleOfValidation);
```
