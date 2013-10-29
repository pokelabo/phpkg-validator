Feature: IntValidator
  範囲は min <= n < max
  Scenario Outline: IntValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"int"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map | option_map           | result | error                               |
    | x   | x:        | { min: 1, max: 10 }  | true   |                                     |
    | x   | x: ''     | { min: 1, max: 10 }  | true   |                                     |
    | x   | x: 0      |                      | true   |                                     |
    | x   | x: -6     | { min: -5, max: -3 } | false  | 'x'が範囲外です (xは-5以上、-3以下) |
    | x   | x: -5     | { min: -5, max: -3 } | true   |                                     |
    | x   | x: -4     | { min: -5, max: -3 } | true   |                                     |
    | x   | x: -3     | { min: -5, max: -3 } | true   |                                     |
    | x   | x: -2     | { min: -5, max: -3 } | false  | 'x'が範囲外です (xは-5以上、-3以下) |
    | x   | x: -1     | { min: 0, max: 10 }  | false  | 'x'が範囲外です (xは0以上、10以下)  |
    | x   | x: 0      | { min: 0, max: 10 }  | true   |                                     |
    | x   | x: 10     | { min: 0, max: 10 }  | true   |                                     |
    | x   | x: 11     | { min: 0, max: 10 }  | false  | 'x'が範囲外です (xは0以上、10以下)  |
    | x   | x: 0      | { min: 1 }           | false  | 'x'が範囲外です (xは1以上)          |
    | x   | x: 1      | { min: 1 }           | true   |                                     |
    | x   | x: 2      | { min: 1 }           | true   |                                     |
    | x   | x: 19     | { max: 20 }          | true   |                                     |
    | x   | x: 20     | { max: 20 }          | true   |                                     |
    | x   | x: 21     | { max: 20 }          | false  | 'x'が範囲外です (xは20以下)         |

  Scenario Outline: IntValidator 設定漏れ
    When "<key>", "<param_map>", "<option_map>"とあるとき、"int"で妥当性判定を行うと"pokelabo\validator\ValidatorException"例外が発生する

    Examples:
    | key | param_map | option_map    |
    | x   | x: 0      | { min: 'a' }  |
    | x   | x: 0      | { min: 10.1 } |
    | x   | x: 0      | { max: 10.1 } |



