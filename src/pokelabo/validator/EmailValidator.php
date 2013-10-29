<?php // -*- coding: utf-8 -*-
/**
 * 妥当性判定：email
 * @package pokelabo/validator
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

/**
 * 妥当性判定：email
 * @package pokelabo/validator
 */
class EmailValidator extends AbstractValidator {
    /**
     * email部検査用正規表現(DoCoMo/auのRFC違反も許す、なんちゃって仕様)
     * @var string
     */
    public $_pattern = "/^[a-zA-Z0-9_!#\\$\\%&\\'*+\\/=?\\^`{}~|.\\-]+@[a-zA-Z0-9_!#\\$\\%&\\'*+\\/=?\\^`{}~|.\\-]+$/";
    /**
     * 名前部分を含む、email部検査用正規表現
     * @var string
     */
    public $_fullpattern = "/^[^@]*<[a-zA-Z0-9_!#\\$\\%&\\'*+\\/=?\\^`{}~|.\\-]+@[a-zA-Z0-9_!#\\$\\%&\\'*+\\/=?\\^`{}~|.\\-]+>$/";
    /**
     * 名前部分を含めることを許可するか？(e.g. "hanako <hanako@example.com>")<br/>
     * default: false
     * @var boolean
     * @see fullPattern
     */
    public $_allow_name = false;
    /**
     * MXレコードをチェックするか？<br/>
     * default: false<br/>
     * 使用時にはPHP関数checkdnsrr()のインストールが必要。
     * @var boolean
     */
    public $_check_mx = false;
    /**
     * ポート接続チェックを行うか？<br/>
     * default: false.
     * @var boolean|int
     */
    public $_check_port = false;

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

        $email = "{$param_map[$key]}";

        if ($this->_allow_name) {
            $regexp = $this->_fullpattern;
        } else {
            $regexp = $this->_pattern;
        }

        // DoS攻撃を避けるため、文字長を制限
        $result = (strlen($email) <= 254) && preg_match($regexp, $email);

        if ($result && $this->_check_mx) {
            $result = $this->validateMx($email);
        }
        if ($result && $this->_check_port) {
            $result = $this->validateConnection($email, $this->_check_port);
        }

        if ($result) {
            return true;
        } else {
            $this->setError("'%s'が正しくありません", $key);
            return false;
        }
    }

    /**
     * DNS MXレコードを利用した妥当性判定
     * @param string $email
     * @return boolean
     */
    protected function validateMx($email) {
        if (!function_exists('checkdnsrr')) {
            // checkdnsrr()関数がインストールされていない：仕方がないのでtrueで返す
            return true;
        }

        $domain = rtrim(substr($email, strpos($email,'@') + 1), '>');
        return checkdnsrr($domain, 'MX');
    }

    /**
     * SMTPサーバに接続できるか？<br/>
     * SMTPサーバに接続できない場合1秒待つことになるので、この判定はすべきではない
     * @param string $email
     * @return boolean
     */
    protected function validateConnection($email, $port) {
        if (!function_exists('getmxrr') || !function_exists('fsockopen')) {
            // 必要な関数がインストールされていない：仕方がないのでtrueで返す
            return true;
        }

        $domain = rtrim(substr($email, strpos($email,'@') + 1), '>');

        if (getmxrr($domain, $host_list)) {
            $domain = reset($host_list);
        }

        return @fsockopen($domain, $port, $errno, $errstr, 1) !== false;
    }
}
