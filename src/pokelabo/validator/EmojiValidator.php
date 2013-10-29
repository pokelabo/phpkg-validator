<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：絵文字
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

/**
 * 妥当性判定：絵文字
 * @package pokelabo/validator
 */
class EmojiValidator extends AbstractValidator {
    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        if (!array_key_exists($key, $param_map)) {
            return true;
        }

        if (is_scalar($param_map[$key]) || is_null($param_map[$key])) {
            if ("{$param_map[$key]}" == '') {
                return true;
            }
        }

        // UTF-8のいろんな文字も許可したいけど、
        // 現状では絵文字を判別するのが技術的に困難なので...

        $value = "{$param_map[$key]}";
        $sjis_value = mb_convert_encoding($value, 'SJIS-win', 'UTF-8');
        $reversed_value = mb_convert_encoding($sjis_value, 'UTF-8', 'SJIS-win');
        if ($value === $reversed_value) {
            return true;
        }

        $invalid_chars = array();
        $len = mb_strlen($value);
        for ($i = 0; $i < $len; ++$i) {
            $utf8_char = mb_substr($value, $i, 1, 'UTF-8');
            $sjis_char = mb_convert_encoding($utf8_char, 'SJIS-win', 'UTF-8');
            $reversed_char = mb_convert_encoding($sjis_char, 'UTF-8', 'SJIS-win');
            if ($utf8_char !== $reversed_char) {
                $invalid_chars[] = $utf8_char;
            }
        }

        $this->setError("使用できない文字が含まれています(%s)", implode("、", $invalid_chars));
        return false;
    }
}
