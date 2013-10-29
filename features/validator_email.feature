Feature: EmailValidator

  Scenario Outline: EmailValidator
    When "<key>", "<param_map>", "<option_map>"とあるとき、"email"で妥当性判定を行う
    Then <result>である
     And 妥当性判定のエラーは"<error>"である

    Examples:
    | key | param_map         | option_map       | result | error                 |
    | x   | y:                |                  | true   |                       |
    | x   | x:                |                  | true   |                       |
    | x   | x: ''             |                  | true   |                       |
    | x   | x: 0@x.com        |                  | true   |                       |
    | x   | x: 0+1@x.com      |                  | true   |                       |
    | x   | x: hoge <0@x.com> |                  | false  | 'x'が正しくありません |
    | x   | x: hoge <0@x.com> | allow_name: true | true   |                       |
    | x   | x: 0@yahoo.com    | check_mx: true   | true   |                       |
    | x   | x: 0@yahoo.com    | check_port: 25   | true   |                       |
    | x   | x: 0@pokelab.jp   | check_mx: true   | false  | 'x'が正しくありません |
    | x   | x: 0@pokelab.jp   | check_port: 25   | false  | 'x'が正しくありません |





