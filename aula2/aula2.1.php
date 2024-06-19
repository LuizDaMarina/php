
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script> 


    <title>Cadastro de Cliente</title>
  </head>
  <body>
    <div class="container">
    <h1>Cadastro de Cliente</h1>

    <form method="post" action="aula2.2.php">
  <div class="form-group">
    <label for="exampleFormControlInput1">Nome do Cliente </label>
    <input type="text" name="nome" class="form-control" placeholder="Nome do Cliente">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">idade </label>
    <input type="text" name="idade" class="form-control" placeholder="Idade">
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect1">Estado</label>
    <select name="estado" id="exampleFormControlSelect1">
      <option value="RS">RS</option>
      <option value="SC">SC</option>
      <option value="PR">PR</option>
      <option value="AL">AL</option>
      <option value="BA">BA</option>
    </select>
  </div>

  <div class="form-group">
    <label for="exampleFormControlTextarea1">Mensagem</label>
    <textarea class="form-control" name="area2" id="area2" rows="3"></textarea>
    </div>

<input name= "eae" type="submit" value="CADASTRO DO CLIENTE" class="btn btn-primary"></input>
</form>
  <script type="text/javascript">
      bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
      </script>

    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
