<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：整数値 (範囲：min <= n <= max)
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

use pokelabo\validator\ValidatorException;

/**
 * 妥当性判定：整数値 (範囲：min <= n <= max)
 * @package pokelabo/validator
 */
class IntValidator extends AbstractValidator {
    /**
     * 最小値
     * @var numeric
     */
    protected $_min;

    /**
     * 最大値
     * @var numeric
     */
    protected $_max;

    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        if (($this->_min !== null && !is_int($this->_min)) ||
            ($this->_max !== null && !is_int($this->_max))) {
            throw new ValidatorException(
                "min/maxオプション設定が正しくありません",
                ValidatorException::NO_ENTRY);
        }

        if (!array_key_exists($key, $param_map)) {
            return true;
        }

        $value = $param_map[$key];
        $temp_value = $value + 0;
        if (!is_int($temp_value) || "$temp_value" != "$value") {
            if (is_scalar($value) || is_null($value)) {
                if ("$value" == '') {
                    return true;
                }
            }

            $this->setError('\'%1$s\'が整数ではありません', $key);
            return false;
        }

        if ($this->_min !== null) {
            if ($this->_max !== null) {
                if (($value < $this->_min) || ($this->_max < $value)) {
                    $this->setError('\'%1$s\'が範囲外です (%1$sは%2$d以上、%3$d以下)', $key, $this->_min, $this->_max);
                    return false;
                }
            } else {
                if ($value < $this->_min) {
                    $this->setError('\'%1$s\'が範囲外です (%1$sは%2$d以上)', $key, $this->_min, $this->_max);
                    return false;
                }
            }
        } else {
            if ($this->_max !== null) {
                if (($this->_max < $value)) {
                    $this->setError('\'%1$s\'が範囲外です (%1$sは%3$d以下)', $key, $this->_min, $this->_max);
                    return false;
                }
            }
        }

        return true;
    }
}
