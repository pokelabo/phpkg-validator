<?php // -*- coding: utf-8 -*-
/**
 * validation related exceptions
 * @package pokelabo/validator
 * @copyright Copyright (c) 2013, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\validator;

/**
 * ライブラリ 実装関連例外クラス
 * @package pokelabo/validator
 */
class ValidatorException extends \Exception {
    const NO_ENTRY = 100;
    const NOT_CALLABLE = 101;
}
