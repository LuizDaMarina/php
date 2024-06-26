<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <?php require  "includes/menu.php"; ?>
       
        <h1>Cadastro Fornecedor</h1>
    <form method="post" action="acao/acaoFornecedor.php">
        <div class="container">
            <!-- Conteúdo aqui -->
               <div class="row">
                 <div class="col-md-1">
                   <div class="form-group">
                    <label for="codigo">Codigo</label>
                    <input type="text" name="codigo" value="39" readonly style=" width: 40px; height: 45px;">
                   </div>
                 </div>
                 <div class="col-md-3">
                    <div class="form-group">
                     <label for="nome">Nome</label>
                     <input type="text" name="nome" class="form-control" id="nome completo do cliente" placeholder="digite seu nome aqui">
                    </div>
                 </div>
                 <div class="col-md-4">
                    <div class="form-group">
                     <label for="email">Email</label>
                     <input type="email" name="email" class="form-control" id="email" placeholder="digite seu email aqui">
                    </div>
                 </div>
                 <div class="col-md-4">
                        <div class="form-group">
                         <label for="cpf">CPF</label>
                         <input type="text" name="cpf" class="form-control" id="cpf" placeholder="digite seu CPF (Only numbers)">
                        </div>
                 </div>
               </div>
               <div class="row">
                  <div class="col-md-1">
                    <div class="form-group">
                     <label for="telefone">Telefone</label>
                     <input type="text" name="telefone" class="form-control" id="telefone"  placeholder="N°Telefone">
                    </div>
                  </div>
                  <div class="col-md-4">
                   
                    <div class="form-group">
                        <label for="telefone-fixo">Telefone</label>
                        <input type="text" name="telefone" class="form-control" id="telefone-fixo" placeholder="N°Telefone fixo">
                       </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                     <label for="cep">CEP</label>
                     <input type="text" name="cep" class="form-control" id="cep" placeholder="digite seu CEP">
                  </div>
                 </div>
                  <div class="col-md-1">
                    <div class="form-group">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro"  placeholder="logradouro">
                   </div>
                  </div>
                  <div class="col-md-2">
                  
                    <div class="form-group">
                        <label for="numero">N°</label>
                        <input type="text" class="form-control" id="numero" placeholder="N°">
                  </div>
               </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" id="cidade" placeholder="cidade">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="uf">UFFF</label>
                        <input type="text" class="form-control" id="uf" placeholder="UF">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select>
                    </div>
                </div>
               </div>
             </div>
        
               <input type="submit" name="enviar" id="enviar" value="Cadastrar" style="background-color: green; float: right;" >
                 
                  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </form>
    
    <?php require  "includes/rodape.php"; ?> 
</body>
</html>



