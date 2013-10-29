Feature: UuidValidator

  Scenario Outline: UuidValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"uuid"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map                               | option_map                                 | result | error                 |
    | x   | y:                                      |                                            | true   |                       |
    | x   | x:                                      |                                            | true   |                       |
    | x   | x: 00000000-0000-0000-0000-000000000000 |                                            | true   |                       |
    | x   | x: ffffffff-ffff-ffff-ffff-ffffffffffff |                                            | true   |                       |
    | x   | x: fffffff-ffff-ffff-ffff-ffffffffffff  |                                            | false  | 'x'が正しくありません |
    | x   | x: ffffffff-fff-ffff-ffff-ffffffffffff  |                                            | false  | 'x'が正しくありません |
    | x   | x: ffffffff-ffff-fff-ffff-ffffffffffff  |                                            | false  | 'x'が正しくありません |
    | x   | x: ffffffff-ffff-ffff-fff-ffffffffffff  |                                            | false  | 'x'が正しくありません |
    | x   | x: ffffffff-ffff-ffff-ffff-fffffffffff  |                                            | false  | 'x'が正しくありません |
    | x   | x: 0                                    |                                            | false  | 'x'が正しくありません |
    | x   | x: []                                   |                                            | false  | 'x'が正しくありません |
    | z   | z: []                                   | message: '%sがだめ'                        | false  | zがだめ               |

