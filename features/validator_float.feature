Feature: FloatValidator
  範囲は min <= n < max
  Scenario Outline: FloatValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"float"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map | option_map               | result | error                                |
    | x   | x:        | { min: 1, max: 10 }      | true   |                                      |
    | x   | x: ''     | { min: 1, max: 10 }      | true   |                                      |
    | x   | x: 0      |                          | true   |                                      |
    | x   | x: 0      | { min: 0, max: 10 }      | true   |                                      |
    | x   | x: 5.5    | { min: -1, max: 10 }     | true   |                                      |
    | x   | x: 10.0   | { min: -1.1, max: 10.1 } | true   |                                      |
    | x   | x: 10     | { min: -1, max: 10 }     | false  | 'x'が範囲外です (xは-1以上、10未満)  |
    | x   | x: 0      | { min: 0.1, max: 10 }    | false  | 'x'が範囲外です (xは0.1以上、10未満) |
    | x   | x: 10     | { min: 0 }               | true   |                                      |
    | x   | x: 10.1   | { min: 10.1 }            | true   |                                      |
    | x   | x: 10.09  | { min: 10.1 }            | false  | 'x'が範囲外です (xは10.1以上)        |
    | x   | x: -2     | { max: -1 }              | true   |                                      |
    | x   | x: 10.0   | { max: 10.1 }            | true   |                                      |
    | x   | x: 10.1   | { max: 10.1 }            | false  | 'x'が範囲外です (xは10.1未満)        |

  Scenario Outline: FloatValidator 設定漏れ
    When "<key>", "<param_map>", "<option_map>"とあるとき、"float"で妥当性判定を行うと"pokelabo\validator\ValidatorException"例外が発生する

    Examples:
    | key | param_map | option_map   |
    | x   | x: 0      | { min: 'a' } |
    | x   | x: 0      | { max: 'a' } |


