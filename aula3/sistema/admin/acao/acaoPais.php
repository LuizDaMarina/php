<?php

if( isset($_POST['enviar'] ) ){

    $nome = $_POST['nome'];

 if(empty(trim($nome)) ){
       echo "<script> alert('Campo em Branco');window.location.href='../cadastroPais.php'; </script>";

 }else{
    echo "<script> alert('Cadastrado com Sucesso');window.location.href='../cadastroPais.php'; </script>";
  //  header("Location: ../cadastroPais.php");
 }

}else{
    echo "Não foi possivel cadastrar";
}

?>