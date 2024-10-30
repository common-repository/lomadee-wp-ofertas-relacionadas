=== Lomadee WP - Ofertas Relacionadas ===
Contributors: Equipe Lomadee - com consultoria especializada Apiki
Donate link: http://br.lomadee.com/
Tags: buscape, compra, lomadee, ofertas, plugin, produtos, venda, wordpress, relacionadas, Lomade
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.2

Exiba ofertas relacionadas aos seus posts e aumente o faturamento com publicidade.

== Description ==

Relacione facilmente ofertas do BuscaPé integrando cada post de seu site/blog WordPress à Lomadee.

Com esta ferramenta, você torna sua divulgação muito mais efetiva , pois você diminui a dispersão de conteúdo junto ao leitor de seu site/blog. Dessa forma, você aumenta seu seu faturamento!

= Automático =
A cada novo post, o aplicativo seleciona automaticamente as principais tags utilizadas e busca no sistema do  BuscaPé ofertas relacionadas à este termo.

= Manual =
Integre completamente seu conteúdo aos produtos que você deseja divulgar. Com ele, você seleciona a categoria e as palavras chave específicas para aquele post e ele lhe retornará as ofertas de produtos dando mais autonomia para que você tenha mais autonomia na escolha das ofertas que deseja divulgar.

Os parâmetros acima são definidos direto no Plugin quando você estiver logado em sua conta no WordPress.

= Source ID =
O Source ID é único e individual  por site cadastrado em sua conta. Caso você possua mais de um site cadastrado na Lomadee, cada um deles terá seu próprio Source ID. Além disso, o Source ID uma vez gerado, será sempre o mesmo. Isso significa que, caso você tente gerar um Source ID duas vezes para o mesmo site, o sistema lhe devolverá o mesmo Source ID gerado pela primeira vez.

= Limite de Ofertas =
A cada novo post, este plugin publicará até 5 ofertas relacionadas ao tema do post ou escolhidas por você, caso  você utilize o modo manual.

= Importante =
Para utilizar este plugin, o afiliado deve ser self host do WordPress (WordPress.org). Caso tenha a conta gratuíta (WordPress.com), você não conseguirá instalá-lo.

== Installation ==

Para instalar o plugin Lomadee WP - Ofertas Relacionadas é simples:

1. Faça o download do plugin por meio da interface WordPress
1. Faça o upload  do arquivos do plugin e mova para o diretório `/wp-content/plugins/` no seu WordPress
1. Ative o plugin no menu 'Plugins'
1. Gere seu Source ID por meio da plataforma Lomadee
1. Configure o plugin no menu 'Lomadee Ofertas' informando seu Source ID, o país de origem das ofertas e a forma de visualização.

Para  usuários iniciantes ou sem tempo para se dedicar a plataforma, sugerimos a configuração da ferramenta como 'Automática'. Para os Afiliados mais dedicados e focados na monetização dos seus sites, você pode utilizar o modo 'Manual' e focar o tipo de divulgação que tem mais adequação aos leitores de seu site/blog.

== Frequently Asked Questions ==

= Como obter um Source ID para meu plugin? =

Para adquirir um Source ID, cadastre-se no <a href="http://br.lomadee.com/">Lomadee</a> acesse a aba aplicativos e clique na ferramenta Lomadee WP e gere o seu.

= Posso definir os produtos de acordo com palavras-chave? =

Sim, é disponibilizado pelo plugin a opção de exibir produtos através de categorias e/ou palavras-chave.

= Os produtos não estão aparecendo. O que pode estar acontecendo? =

Se a forma de visualização configurada for a 'Automática', verifique se há <strong>tags</strong>
relacionadas ao post. Caso a forma de visualização seja 'Manual', verifique se inseriu em seus posts
o código gerado através do botão do plugin, relacionando os produtos.

= Quais os países suportados pelo plugin para exibição das ofertas? =

Os países suportados são definidos de acordo com os disponibilizados pela API do BuscaPé, que são:

* Argentina
* Brasil
* Chile
* Colômbia
* México
* Peru
* Venezuela

= Importante =
Para utilizar este plugin, o afiliado deve ser self host do WordPress (WordPress.org). Caso tenha a conta gratuíta (WordPress.com), você não conseguirá instalá-lo.

== Screenshots ==

1. Exemplo do visual de ofertas relacionadas
2. Página de configurações do plugin
3. Janela para seleção de ofertas manualmente no post

== Changelog ==

= 1.0 =
* Versão inicial

= 1.0.1 =
* Inclusão do IP do cliente nas requisições feitas à API do BuscaPé usando o ambiente de produção

= 1.0.2 =
* Remoção das (...) ao final do nome dos produtos
* Correção de bug relacionado ao array_slice do PHP

= 1.1 =
* Definição dos elementos CSS usados na exibição dos produtos como !important, evitando conflito com o CSS do tema do usuário
* Exibição da imagem padrão do BuscaPé quando um produto não tem uma imagem a ser exibida
* Inclusão da versão 2.0.2 do Wrapper PHP Apiki BuscaPé API
* Bugfix - Ao relacionar palavras-chave com espaços
* Novas configurações para a exibição das informações dos produtos

= 1.1.1 =
* Bugfix - Imagens com tamanho 100x100px ao contrário de 150x150px
* Bugfix - Atualizar a manter as opções já ativadas ao fazer o upgrade do plugin
* Bugfix - Classe CSS para definir como último produto

= 1.2 =
* Bugfix - Centralização das ofertas quando exibidas
* Bugfix - Geração do link da oferta com uso de cache ou não no WordPress