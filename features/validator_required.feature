Feature: RequiredValidator

  Scenario Outline: RequiredValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"required"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map   | option_map                               | result | error                   |
    | x   | y:          |                                          | false  | 'x'を入力してください   |
    | x   | x:          |                                          | false  | 'x'を入力してください   |
    | x   | x: ''       | display_name: XYZ                        | false  | 'XYZ'を入力してください |
    | x   | x: ''       | { message: '%sは？', display_name: XYZ } | false  | XYZは？                 |
    | x   | x:          | allow_empty_string: true                 | true   |                         |
    | x   | x: ''       | allow_empty_string: true                 | true   |                         |
    | x   | x: 0        |                                          | true   |                         |
    | x   | x: []       |                                          | true   |                         |
    | x   | x: { 1: 1 } |                                          | true   |                         |


