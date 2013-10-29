<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：文字長 (範囲：min <= n <= max)
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

use pokelabo\exception\ExceptionCode;
use pokelabo\exception\ImplementationException;

/**
 * 妥当性判定：文字長 (範囲：min <= n <= max)
 * @package pokelabo/validator
 */
class LengthValidator extends AbstractValidator {
    /**
     * 最短
     * @var numeric
     */
    protected $_min;

    /**
     * 最長
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
            throw new ImplementationException(
                "min/maxオプション設定が正しくありません",
                ExceptionCode::NOT_ENTRY);
        }

        if (is_scalar($param_map[$key]) || is_null($param_map[$key])) {
            if ("{$param_map[$key]}" == '') {
                return true;
            }
        }

        $value = "{$param_map[$key]}";
        $length = mb_strlen($value);

        if ($this->_min !== null) {
            if ($this->_max !== null) {
                if (($length < $this->_min) || ($this->_max < $length)) {
                    $this->setError('\'%1$s\'は%2$d文字以上、%3$d文字以下',
                                    $key, $this->_min, $this->_max);
                    return false;
                }
            } else {
                if ($length < $this->_min) {
                    $this->setError('\'%1$s\'は%2$d文字以上', $key, $this->_min, $this->_max);
                    return false;
                }
            }
        } else {
            if ($this->_max !== null) {
                if (($this->_max < $length)) {
                    $this->setError('\'%1$s\'は%3$d文字以下', $key, $this->_min, $this->_max);
                    return false;
                }
            }
        }

        return true;
    }
}
