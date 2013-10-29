<?php // -*- coding: utf-8 -*-
/**
 * Validation class.
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

use pokelabo\validator\ValidatorException;
use pokelabo\utility\StringUtility;

/**
 * 妥当性判定<br/>
 * 配列に対し、予め設定した妥当性判定ルールを元に妥当性判定処理を行う。<br/>
 * エラー内容は内部配列に格納する。<br/>
 * [ルール]<br/>
 * 各ルールの第一要素には、配列のキーまたはキーの組み合わせを指定する。<br/>
 * そのキーと値に対して妥当性判定を行うことになる。<br/>
 * 第二要素には妥当性判定名、または配列のクラスを指定する<br/>
 * 第三要素以降は、妥当性判定処理への引数を指定する。
 * [処理]<br/>
 * 妥当性判定は先頭ルールから順に行われる。<br/>
 * 二つ目以降、妥当性判定対象キーについてすでに妥当性エラーが起きている場合は
 * その判定ルールはスキップされる。
 * @example
 * <pre>
 * $rules = array(
 *   array('username', 'required'),
 *   array('username', 'length', 'min' => 3, 'max' => 12),
 *   array('password', 'required'),
 *   array('password', 'compare', 'with' => 'password2'),
 *   array(array('username', 'password'), 'authenticate'),
 * </pre>
 * @package pokelabo/validator
 */
class Validator {
    /**
     * 妥当性判定ルールリスト<br/>
     * 各ルールの第一要素には、配列のキーまたはキーの組み合わせを指定する。<br/>
     * そのキーと値に対して妥当性判定を行うことになる。<br/>
     * 第二要素には妥当性判定名、または配列のクラスを指定する<br/>
     * 第三要素以降は、妥当性判定処理への引数を指定する。
     * @var array
     */
    protected $_rule_list;

    /**
     * 妥当性判定エラー
     * @var array
     */
    protected $_error_map;

    /**
     * コンストラクタ
     * @param array $rule_list
     */
    public function __construct($rule_list) {
        $this->_rule_list = $rule_list;
    }

    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 全パラメータが妥当の場合はtrue
     */
    public function __invoke($param_map) {
        return $this->validate($param_map);
    }

    /**
     * 妥当性判定実行
     * @param array $param 対象パラメータ
     * @return boolean 全パラメータが妥当の場合はtrue
     */
    public function validate($param_map) {
        $this->_error_map = array();

        foreach ($this->_rule_list as $rule) {
            $param_key = array_shift($rule);
            $spec_validator = $this->getSpecValidator(array_shift($rule));

            if ($rule) {
                $spec_validator->setOptions($rule);
            }

            if (is_array($param_key)) {
                // すでに妥当性判定エラーを起こしている値なら処理をスキップ
                $skip = false;
                foreach ($param_key as $key) {
                    if (array_key_exists($key, $this->_error_map)) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }

                if (!$spec_validator->validateCombination($param_key, $param_map)) {
                    $this->addError($param_key, $spec_validator->getError());
                }
            } else {            // is_array($param_key)
                // すでに妥当性判定エラーを起こしている値なら処理をスキップ
                if (array_key_exists($param_key, $this->_error_map)) {
                    continue;
                }

                if (!$spec_validator->validate($param_key, $param_map)) {
                    $this->addError($param_key, $spec_validator->getError());
                    break;
                }
            }
        }

        return empty($this->_error_map);
    }

    /**
     * 妥当性判定エラー取得
     * @return array 妥当性判定エラー
     */
    public function getErrorMap() {
        return $this->_error_map;
    }

    /**
     * 個別妥当性判定クラスインスタンスを取得する
     * @param string $validator_name_or_class_name 妥当性判定処理名かクラス名
     * @return ValidatorInterface 個別妥当性判定クラスインスタンス
     */
    protected function getSpecValidator($validator_name_or_class_name) {
        $class_name = 'pokelabo\validator\\' .
            StringUtility::toCamel($validator_name_or_class_name) . 'Validator';
        if (!class_exists($class_name)) {
            if (class_exists($validator_name_or_class_name)) {
                $class_name = $validator_name_or_class_name;
            } else {
                throw new ValidatorException(
                    "妥当性判定クラスが存在しません: $validator_name_or_class_name",
                    ValidatorException::NO_ENTRY);
            }
        }

        return new $class_name;
    }

    /**
     * エラーを追加
     * @param string $key パラメータキー名
     * @param string $error エラー
     */
    protected function addError($key, $error) {
        if (is_array($key)) {
            $this->_error_map[reset($key)] = $error;
        } else {
            $this->_error_map[$key] = $error;
        }
    }
}
