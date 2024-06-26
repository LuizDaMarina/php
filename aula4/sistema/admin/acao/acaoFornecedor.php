<?php  if(isset($_POST['enviar'])) {
     $cpf = $_POST['cpf'];
     $cidade = $_POST['cidade'];
     $logradouro = $_POST['logradouro'];
     $numero = $_POST['numero'];
     $numerofixo = $_POST['numero-fixo'];
     $email = $_POST['email'];
     $cep = $_POST['cep'];
     $telefone = $_POST['telefone'];
     $uf = $_POST['uf'];
     $status = $_POST['status'];
     $codigo = $_POST['codigo'];
     
     echo $cpf. "<br>";
     echo $cidade. "<br>";
     echo $logradouro . "<br>";
     echo $numero . "<br>";
     echo $numerofixo. "<br>";
     echo $email. "<br>";
     echo $cep. "<br>";
     echo $telefone. "<br>";
     echo $uf. "<br>";
     echo $status. "<br>";
     echo $codigo. "<br>";
     
     if (empty(trim($cpf))) {
        echo "<script> alert('campo em branco'); window.location.href='beckend/cadastroFornecedor.php'</script>";
     } else {
        echo "<script> alert('campo preenchido'); window.location.href='beckend/cadastroFornecedor.php'</script>";
     }

     if (empty(trim($pais))) {
        echo "<script> alert('campo em branco'); window.location.href='./cadastroPais.php'</script>";
     } else {
        echo "<script> alert('campo preenchido'); window.location.href='./cadastroPais.php'</script>";
        //echo header(location: '../cadastroFornecedorphp');
     }
  } else {
    echo "sorry buddy, your document wasn´t send <script> alert('������������������'); window.location.href='./cadastroPais.php'</script>";
  };
?>