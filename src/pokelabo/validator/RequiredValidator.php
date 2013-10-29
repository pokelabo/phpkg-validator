<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：必須
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

/**
 * 妥当性判定：必須
 * @package pokelabo/validator
 */
class RequiredValidator extends AbstractValidator {
    /**
     * 空文字を許すか？<br/>
     * default: false
     * @var boolean
     */
    protected $_allow_empty_string = false;

    /**
     * 妥当性判定実行
     * @param string $key パラメータの妥当性判定対象キー
     * @param array $param_map パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        if (array_key_exists($key, $param_map)) {
            if ($this->_allow_empty_string || "{$param_map[$key]}" != '') {
                return true;
            }
        }

        $this->setError("'%s'を入力してください", $key);
        return false;
    }
}
