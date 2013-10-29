phpkg-validator
===============

Installation
------------

Install composer in your project:

    curl -s https://getcomposer.org/installer | php

Create a composer.json file in your project root:

    {
        "require": {
            "pokelabo/validator": "*"
        },
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/pokelabo/phpkg-validator.git"
            }
        ],
        "minimum-stability": "dev"
    }

Usage
-----

You can validate values in an array with specified rules.

Assume that you have values as below:

    $inputs = array('username' => 'abc'
                    'age'      => 18);

You can define against the values in form of array of array.  

    $rules = array(
        array('username', 'required'),
        array('username', 'length', 'min' => 3, 'max' => 12),
        array('age', 'required'),
        array('age', 'int', 'min' => 18, 'max' => 120),
    );

It has four rules, each rule forms array(`key of array`, `validator name` [, `options`...]).  

Now it is ready to validate:

    $validator = new \pokelabo\validator\Validator($rules);
    if (!$validator($inputs)) {
        var_dump($validator->getErrorMap());
    }

Validators
----------

#### required

Validates whether the `input` has non-empty value.

Fails if:

1. `input` does does not have the specified entry.
2. The value is either empty string or `null`.

| options            | default | description                                         |
| :----------------- | :------ | :-------------------------------------------------- |
| allow_empty_string | false   | If `true`, it allows either empty string or `null`. |

#### email

Validates whether the `input` is in form of valid email format.

| options            | default | description                                                               |
| :----------------- | :------ | :--------------------------------------------------                       |
| pattern            | ...     | Regexp pattern of format of email part.                                   |
| fullpattern        | ...     | Regexp pattern of fully email format, name + email part.                  |
| allow_name         | false   | If `true`, it allows _name part_ in `input`.                              |
| check_mx           | false   | If `true`, it will check `MX RECORD` of the domain.                       |
| check_port         | false   | If `true`, it will test validity by connecting to the destination server. |

#### enum

Validates whether the `input` is in the specified value list.

| options            | default | description                                         |
| :----------------- | :------ | :-------------------------------------------------- |
| value_list         |         | _Required._ List of valid values.                   |

#### float

Validates whether the `input` looks as a float value.

| options            | default | description                                         |
| :----------------- | :------ | :-------------------------------------------------- |
| min                | null    | If specified, it validates `min` <= `input`.        |
| max                | null    | If specified, it validates `input` <= `max`.        |

#### int

Validates whether the `input` looks as an int value.

| options            | default | description                                         |
| :----------------- | :------ | :-------------------------------------------------- |
| min                | null    | If specified, it validates `min` <= `input`.        |
| max                | null    | If specified, it validates `input` <= `max`.        |

#### length

Validates whether the length of `input` is along with the specified rule.

The length is calculated by `mb_strlen`. So it is _character length_ instead of _string bytes_.

| options            | default | description                                             |
| :----------------- | :------ | :--------------------------------------------------     |
| min                | null    | If specified, it validates `min` <= _character length_. |
| max                | null    | If specified, it validates _character length_ <= `max`. |

#### regexp

Validates `input` against the specified _regexp pattern_.

| options            | default | description                                                           |
| :----------------- | :------ | :--------------------------------------------------                   |
| regexp             |         | _Required_. Perl compatible regexp pattern to test with.              |
| not                | false   | If `true`, it validates whether `input` does not match with `regexp`. |

#### uuid

Validates whether the `input` is in form of valid [UUID/GUID format][].

[UUID/GUID format]: http://en.wikipedia.org/wiki/Globally_unique_identifier#Text_encoding

License
-------

MIT Public License
