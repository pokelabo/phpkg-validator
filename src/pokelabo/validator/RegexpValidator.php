<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：正規表現
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
class RegexpValidator extends AbstractValidator {
    /**
     * 正規表現パターン(Perl互換)<br/>
     * 例: /^(y(es)?|no?)$/i
     * @var array
     */
    protected $_regexp;

    /**
     * 結果反転フラグ<br/>
     * trueの場合はマッチしなければ妥当となる<br/>
     * default: false
     * @var boolean
     */
    protected $_not = false;

    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        if (!is_string($this->_regexp) || $this->_regexp == '') {
            throw new ValidatorException(
                "regexpオプション設定が正しくありません",
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

            if (!!preg_match($this->_regexp, $value) == !$this->_not) {
                return true;
            }
        }

        $this->setError('\'%s\'が正しくありません', $key);
        return false;
    }
}
