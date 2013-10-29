<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：UUID
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

/**
 * 妥当性判定：UUID
 * @package pokelabo/validator
 */
class UuidValidator extends AbstractValidator {
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

            if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $param_map[$key])) {
                return true;
            }
        }

        $this->setError("'%s'が正しくありません", $key);
        return false;
    }
}
