Feature: RegexpValidator
  デフォルト：
    絵文字禁止
    空白許可
    改行許可
  
  Scenario Outline: RegexpValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"regexp"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map | option_map                       | result | error                 | description              |
    | x   | y:        | regexp: '/^\w+$/'                | true   |                       |                          |
    | x   | x:        | regexp: '/^\w+$/'                | true   |                       |                          |
    | x   | x: ''     | regexp: '/^\w+$/'                | true   |                       |                          |
    | x   | x: '123'  | regexp: '/^\d+$/'                | true   |                       |                          |
    | x   | x: '1a2'  | regexp: '/^\d+$/'                | false  | 'x'が正しくありません |                          |
    | x   | x: 'YeS'  | regexp: '/^yes$/i'               | true   |                       | 大文字小文字を区別しない |
    | x   | x: '123'  | { regexp: '/^\d+$/', not: true } | false  | 'x'が正しくありません | not                      |

  Scenario Outline: RegexpValidator 設定漏れ
    When "<key>", "<param_map>", "<option_map>"とあるとき、"regexp"で妥当性判定を行うと"pokelabo\validator\ValidatorException"例外が発生する

    Examples:
    | key | param_map | option_map     |
    | x   | x: 0      |                |




