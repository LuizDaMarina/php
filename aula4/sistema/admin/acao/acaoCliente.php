<?php
    if (isset($_POST ['enviar'])){
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $data = $_POST['data'];
        $radios = $_POST['radios'];
        $ec = $_POST['ec'];
        $renda = $_POST['renda'];
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        

        if (empty(trim($nome))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($cpf))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($data))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($ec))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($renda))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($logradouro))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($numero))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($complemento))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($estado))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        } else if (empty(trim($cidade))){
        echo "<script> alert ('campo em branco');window.location.href='../cadastroCliente.php'; </script>";
        }else{
            echo $nome . "<br>" . $cpf . "<br> " . $data . "<br> " . $radios . "<br> " . $ec . " <br>" . $renda . "<br> " . $logradouro . "<br> " . $numero . " <br>" . $complemento . " <br>" . $estado . "<br> " . $cidade;
        }
    }
        
?>