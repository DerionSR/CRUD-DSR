<?php

function get_endereco($cep){


  $cep = preg_replace("/[^0-9]/", "", $cep);
  $url = "http://viacep.com.br/ws/$cep/xml/";

  $xml = simplexml_load_file($url);
  return $xml;
}

?>
<meta charset="utf-8">
<h1>Pesquisar Endereço</h1>
<form action="" method="post">
  <input type="text" name="cep">
  <button type="submit">Pesquisar Endereço</button>
</form>
<?php if($_GET['cep']){ ?>
<h2>Resultado da Pesquisa</h2>
<p>
  <?php $endereco = get_endereco($_GET['cep']); ?>
  <b>CEP: </b> <?php echo $endereco->cep; ?><br>
  <b>Localidade: </b> <?php echo $endereco->localidade; ?><br>
  <b>UF: </b> <?php echo $endereco->uf; ?><br>
</p>
<?php } ?>