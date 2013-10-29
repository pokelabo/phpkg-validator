<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定処理基底
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

use pokelabo\validator\ValidatorException;

/**
 * 妥当性判定処理基底
 * @package pokelabo/validator
 */
abstract class AbstractValidator {
    /**
     * 妥当性判定エラー
     * @var string|null 妥当性判定エラー
     */
    protected $_error;

    /**
     * 妥当性判定キーの表示用名称
     */
    protected $_display_name;

    /**
     * カスタムエラーメッセージ
     */
    protected $_message;

    /**
     * オプションを設定する
     * @param array $option_map
     */
    public function setOptions($option_map) {
        foreach ($option_map as $key => $value) {
            $property = '_' . $key;
            if (!property_exists($this, $property)) {
                throw new ValidatorException("不明なオプション: '$key'",
                                             ValidatorException::NO_ENTRY);
            }
            $this->$property = $value;
        }
    }

    /**
     * 妥当性判定実行
     * @param string $key パラメータの妥当性判定対象キー
     * @param array $param_map パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validate($key, $param_map) {
        $class = get_class($this);
        throw new ValidatorException(
            "妥当性判定クラス $class は単体パラメータ判定に対応していません",
            ValidatorException::NOT_CALLABLE);
    }

    /**
     * 組み合わせ妥当性判定実行
     * @param array $key_list パラメータの妥当性判定対象キーの組み合わせ
     * @param array $param パラメータ
     * @return boolean 妥当であればtrue
     */
    public function validateCombination($key_list, $param_map) {
        $class = get_class($this);
        throw new ValidatorException(
            "妥当性判定クラス $class は組合せパラメータ判定に対応していません",
            ValidatorException::NOT_CALLABLE);
    }

    /**
     * 妥当性判定エラー取得
     * @return array 妥当性判定エラー
     */
    public function getError() {
        return $this->_error;
    }

    /**
     * 妥当性判定エラー設定
     * @param string|array key
     * @param string $message
     */
    protected function setError($message, $key) {
        $args = func_get_args();
        array_shift($args);

        if (isset($this->_message)) {
            $message = $this->_message;
        }

        $args[0] = $this->getDisplayName($key);
        $this->_error = vsprintf($message, $args);
    }

    /**
     * 表示名を取得する
     */
    protected function getDisplayName($key) {
        if ($this->_display_name != '') {
            return $this->_display_name;
        } else if (!is_array($key)) {
            return $key;
        } else {
            return reset($key);
        }
    }
}
