<?php
      //Função de data e hora
      echo date("d/m/y") . "<br>";
      echo date("d/m/Y") . "<br>";
      echo date("H:i:s") . "<br>";

      //Funções para Servidor Globais
      echo $_SERVER['PHP_SELF'] . "<br>"; //Arquiv
      echo $_SERVER['SERVER_NAME'] . "<br>"; //localhost
      echo $_SERVER['REMOTE_ADDR'] . "<br>"; //status
      echo $_SERVER['REMOTE_HOST'] . "<br>"; // dominio
  


?>