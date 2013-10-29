<?php // -*- coding: utf-8 -*-

use pokelabo\validator\Validator;

$steps->When('/^"([^"]*)", "([^"]*)", "([^"]*)"とあるとき、"([^"]*)"で妥当性判定を行う$/', function($world, $arg1, $arg2, $arg3, $arg4) {
        $key = yaml_parse($arg1);
        $param_map = yaml_parse($arg2);

        // UnicodeValidatorの場合、HTML Entity化されて渡ってくる場合がある
        if ($arg4 == 'unicode' && reset($param_map)) {
            $param_map[key($param_map)] = preg_replace_callback(
                '/(&#x[0-9a-f]+;)/i', function($m) {
                    return mb_convert_encoding($m[1], 'UTF-8', 'HTML-ENTITIES');
                }, reset($param_map));
        }
        $option_map = $arg3 ? yaml_parse($arg3) : array();

        $rule = array($key, $arg4) + $option_map;
        $rule_list = array($rule);

        $world->validator = new Validator($rule_list);
        $world->output = $world->validator->validate($param_map);

        $error_map = $world->validator->getErrorMap();
        $error_key = is_array($key) ? reset($key) : $key;
        $world->error = array_key_exists($error_key, $error_map) ? $error_map[$error_key] : null;
    });

$steps->Given('/^妥当性判定のエラーは"([^"]*)"である$/', function($world, $arg1) {
        if ($arg1 == '') {
            assertNull($world->error);
        } else {
            // UnicodeValidatorの場合、HTML Entity化されて渡ってくる場合がある
            $arg1 = preg_replace_callback(
                '/(&#x[0-9a-f]+;)/i', function($m) {
                    return mb_convert_encoding($m[1], 'UTF-8', 'HTML-ENTITIES');
                }, $arg1);
            assertEquals($arg1, $world->error);
        }
    });

$steps->When('/^"([^"]*)", "([^"]*)", "([^"]*)"とあるとき、"([^"]*)"で妥当性判定を行うと"([^"]*)"例外が発生する$/', function($world, $arg1, $arg2, $arg3, $arg4, $arg5) {
        $key = yaml_parse($arg1);
        $param_map = yaml_parse($arg2);
        $option_map = $arg3 ? yaml_parse($arg3) : array();

        $rule = array($key, $arg4) + $option_map;
        $rule_list = array($rule);

        $world->validator = new Validator($rule_list);
        assertThrowException($arg5, function() use ($world, $param_map) {
                $world->validator->validate($param_map);
            });
    });
