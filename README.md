# http-php-laravel
![CI status](https://github.com/jetimob/http-php-laravel/actions/workflows/ci.yml/badge.svg)
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)

Essa biblioteca funciona como um envelope em torno do [Guzzle](https://docs.guzzlephp.org/) para facilitar a troca de
requisições que precisam de alguma forma de autorização (como o [OAuth 2.0](https://tools.ietf.org/html/rfc6749)) e para
automatizar a deserialização das respostas HTTP em instâncias de objetos que representam essas respostas.

> Para mais informações sobre a deserialização automática de respostas para classes, olhar o método
[complexResponseWithArrayTyping](./tests/Feature/SimpleRequestTest.php).

Toda parte fazer a requisição HTTP é delegada ao Guzzle, sendo assim, a maioria das configurações utilizadas pode ser
retirada da [documentação oficial](https://docs.guzzlephp.org/).

## OAuth 2.0



### Flows

#### Client Credentials

#### Authorization code

## Configuração

## Serialização

**Importante!**

Se o *OPcache* for utilizado, a opção `opcache.save_comments` precisa ser ativada, i.e.: `opcache.save_comments=1`.\
Isso é necessário para que os vetores de uma resposta possam ser automaticamente deserializados com um PHP docblock.
Por exemplo, considere o código abaixo:

```php
/**
 * @var \Jetimob\Http\Response[] $response_array
 */
protected array $response_array = [];
```

Se essa declaração estiver dentro de uma classe que estenda `\Jetimob\Http\Response` e a resposta HTTP conter uma
propriedade nomeada `response` do tipo `array`, ela será automaticamente deserializada num vetor de objetos do tipo
declarado no docblock (`\Jetimob\Http\Response` nesse caso).\
**Para funcionar corretamente, o tipo da variável definida deve usar a trait `Jetimob\Http\Traits\Serializable`**.

A definição do tipo da variável no docblock **precisa** estar na sua forma completa, isso é, `\Jetimob\Http\Response`
e **não** `Response` junto de uma importação de namespace `use Jetimob\Http\Response;`.
