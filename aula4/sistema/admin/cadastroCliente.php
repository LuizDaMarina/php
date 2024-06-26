<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Sim</title>
  </head>
  <body>
    <div class="container">
     
      <?php require "includes/menu.php"; 
      session_start();


if( isset ($_SESSION['logar'])){
  echo "Bem Vindo";

}else{
  header("Location: ../index.php");
  session_destroy();


}
?>

    <h1>Cadastro Cliente</h1>
    <form method="post" action="acao/acaoCliente.php">

<h3 style="margin-top: 30px; color:gray">Dados Pessoais</h3>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
    <label for="exampleInputEmail1">Nome</label>
    <input type="text" name="nome" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu nome">
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-4">
      <div class="form-group">
      <label for="exampleInputEmail1">Cpf</label>
      <input type="text" name="cpf" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="000.000.000-00">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
      <label for="exampleInputEmail1">Data de nascimento<data value=""></data></label>
      <input type="date" name="data" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="dd/mm/yy">
      </div>
    </div>
    <div class="col-md-4">sexo
          <div class="form-check">
        <input class="form-check-input" type="radio" name="Radios"  value="Masculino">
        <label class="form-check-label" for="exampleRadios1">
          Masculino
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="Radios"  value="Feminino">
        <label class="form-check-label" for="exampleRadios2">
          Feminino
        </label>
      </div>
    </div>
</div>

  <div class="row">
  <div class="col-md-4">estado civil
  <select class="form-control" name="ec" style="margin-top: 5px;">
  <option>solteiro</option>
  <option>casado</option>
  <option>mamando</option>
</select>
  </div>
    <div class="col-md-4">
      <div class="form-group">
      <label for="exampleInputEmail1">Renda Mensal</label>
      <input type="text" name="renda" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="0,00">
      </div>
    </div>  
  </div>

<h3 style="margin-top: 30px; color:gray">Endereço</h3>
  <div class="row">
  <div class="col-md-6">
  <div class="form-group">
      <label for="exampleInputEmail1">Logradouro</label>
      <input type="text" name="logradouro" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="av.pau">
      </div>
  </div>
    <div class="col-md-3">
    <div class="form-group">
      <label for="exampleInputEmail1">Número</label>
      <input type="text" name="numero" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="ñ">
      </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
      <label for="exampleInputEmail1">Complemento</label>
      <input type="text" name="complemento" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Casa, Apto, Loja">
      </div>
    </div>
  </div>

  <div class="row">
  <div class="col-md-6">estado
  <select class="form-control" name="estado" style="margin-top: 5px;">
  <option>Acre</option>
  <option>Bahia</option>
  <option>Ceara</option>
</select>
  </div>
    <div class="col-md-6">
    <div class="form-group">
      <label for="exampleInputEmail1">Cidade</label>
      <input type="text" name="Cidade" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="São Paulo">
    </div>
    </div>
    <input type="submit" value="Enviar" name="enviar" class="btn btn-dark" style="margin-left: 15px;">
  </div>
</form>


  <?php require "includes/rodape.php";?>
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>