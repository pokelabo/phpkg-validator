<?php

require '../vendor/autoload.php';

$rules = array(
    array('username', 'required'),
    array('username', 'length', 'min' => 3, 'max' => 12),
    array('age', 'required'),
    array('age', 'int', 'min' => 18, 'max' => 120),
);

$validator = new \pokelabo\validator\Validator($rules);

$no = 0;

function report() {
    global $no, $inputs, $validator;
    printf("[%d]\ninputs = %s\nerror = %s\n\n",
           ++$no,
           print_r($inputs, true),
           print_r($validator->getErrorMap(), true));
}

$inputs = array();
if (!$validator($inputs)) {
    report();
}

$inputs['username'] = 'ab';
$inputs['age'] = 1;
if (!$validator($inputs)) {
    report();
}

$inputs['username'] = 'abc';
$inputs['age'] = 1;
if (!$validator($inputs)) {
    report();
}

$inputs['username'] = 'abc';
$inputs['age'] = 18;
if (!$validator($inputs)) {
    report();
} else {
    echo "no error.\n";
}

