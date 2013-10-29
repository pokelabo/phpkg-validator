Feature: EnumValidator

  Scenario Outline: EnumValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"enum"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map | option_map            | result | error                 |
    | x   | x:        | value_list: [0, 2, 5] | true   |                       |
    | x   | x: ''     | value_list: [0, 2, 5] | true   |                       |
    | x   | x: 0      | value_list: [0, 2, 5] | true   |                       |
    | x   | x: 2      | value_list: [0, 2, 5] | true   |                       |
    | x   | x: 5      | value_list: [0, 2, 5] | true   |                       |
    | x   | x: '5'    | value_list: [0, 2, 5] | true   |                       |
    | x   | x: 1      | value_list: [0, 2, 5] | false  | 'x'が正しくありません |
    | x   | x: 6      | value_list: [0, 2, 5] | false  | 'x'が正しくありません |

  Scenario Outline: EnumValidator 設定漏れ
    When "<key>", "<param_map>", "<option_map>"とあるとき、"enum"で妥当性判定を行うと"pokelabo\validator\ValidatorException"例外が発生する

    Examples:
    | key | param_map | option_map     |
    | x   | x: 0      |                |
    | x   | x: 0      | value_list: [] |



