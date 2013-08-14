<?php

class Validator {
    private $options = Array(
        'ignore_other_if_required_failed' => true,
        'strict' => false
    );

    public function validate($data, $rules, &$errors = Array(), $options = Array()) {
        $options = array_merge($this->options, $options);

        foreach ($rules as $field => $field_rules) {
            if (!is_array($field_rules)) {
                $field_rules = explode(',', $field_rules);
            }

            $field_rules = $this->normalizeRules($field_rules);
            $field_errors = Array();
            $field_value = (array_key_exists($field, $data))? $data[$field] : null;

            if (empty($field_rules)) {
                $field_errors[] = 'unknown_rule';
            } else {
                foreach ($field_rules as $rule_key => $rule_value) {
                    $is_valid = $this->validateValueByRule($field_value, $rule_key, $rule_value, $options);

                    if (!$is_valid) {
                        if ($rule_key == 'required' && $options['ignore_other_if_required_failed']) {
                            // Ignore other errors
                            $field_errors = Array('required');
                            break;
                        }

                        $field_errors[] = $rule_key;
                    }
                }
            }

            if (!empty($field_errors)) {
                $errors[$field] = $field_errors;
            }
        }

        return !$errors;
    }

    public function validateValue($value, $rules, &$errors = Array(), $options = Array()) {
        $result = $this->validate(Array('field' => $value), Array('field' => $rules), $errors, $options);

        if (!empty($errors['field'])) {
            $errors = $errors['field'];
        }

        return $result;
    }

    private function validateValueByRule($value, $rule_key, $rule_value, $options = Array()) {
        if (!$options) {
            $options = $this->options;
        }

        switch ($rule_key) {
            case 'required':
                if ($value === null || (!$options['strict'] && $value === '')) {
                    return false;
                }

                break;

            case 'min':
                if ($value < $rule_value) {
                    return false;
                }

                break;

            case 'max':
                if ($value > $rule_value) {
                    return false;
                }

                break;

            case 'min_len':
                if (mb_strlen($value) < $rule_value) {
                    return false;
                }

                break;

            case 'max_len':
                if (mb_strlen($value) > $rule_value) {
                    return false;
                }

                break;

            case 'num':
                if (!is_numeric($value)) {
                    return false;
                }

                break;

            case 'int':
                if (($options['strict'] && !is_integer($value)) || !ctype_digit($value)) {
                    return false;
                }

                break;

            case 'real':
                if (($options['strict'] && !is_real($value)) || !is_numeric($value)) {
                    return false;
                }

                break;


            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                break;

            case 'regexp':
                if (!preg_match($rule_value, $value)) {
                    return false;
                }
        }

        return true;
    }

    private function normalizeRules($rules) {
        $normalized_rules = Array();

        foreach ($rules as $key => $value) {
            if (!is_string($key) && is_string($value)) {
                $key = $value;
            }

            $key = trim(mb_strtolower($key));
            $normalized_rules[$key] = $value;
        }

        return $normalized_rules;
    }

    public function filter($array, $sample, $rules = null) {
        if (!is_array($sample)) {
            return Array();
        }

        $filtered_array = Array();

        foreach ($array as $key => $value) {
            if (!array_key_exists($key, $sample)) {
                continue;
            }

            if (is_array($value)) {
                $value = $this->filter($value, $sample[$key], $rules);
            }

            $filtered_array[$key] = $value;
        }

        return $filtered_array;
    }
}

?>