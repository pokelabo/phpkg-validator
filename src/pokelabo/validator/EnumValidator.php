<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：指定した値のどれか
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

use pokelabo\validator\ValidatorException;

/**
 * 妥当性判定：指定した値のどれか
 * @package pokelabo/validator
 */
class EnumValidator extends AbstractValidator {
    /**
     * 候補値
     * @var array
     */
    protected $_value_list;

    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        if (!is_array($this->_value_list)) {
            throw new ValidatorException(
                "value_listオプション設定が正しくありません",
                ValidatorException::NO_ENTRY);
        }

        if (!array_key_exists($key, $param_map)) {
            return true;
        }

        $value = $param_map[$key];
        if (is_scalar($value) || is_null($value)) {
            if ("$value" == '') {
                return true;
            }

            if (in_array($value, $this->_value_list)) {
                return true;
            }
        }

        $this->setError('\'%1$s\'が正しくありません', $key);
        return false;
    }
}
