<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once "../Conexao/Conexao.php";
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';
require 'PDF/fpdf.php'; // Incluindo a biblioteca FPDF

/*
// Obtendo user_id e session_token do POST
$user_id = $_POST['user_id'] ?? null;
$session_token = $_POST['session_token'] ?? null;

// Verifica se os dados foram enviados corretamente
if (!$user_id || !$session_token) {
    echo json_encode(["status" => "error", "message" => "Dados ausentes"]);
    exit;
}*/
/*
try {
    $token_result = $conexao->prepare("
        SELECT token, TIMESTAMPDIFF(SECOND, login_time, NOW()) AS session_duration 
        FROM session_token 
        WHERE user_id = :user_id 
        ORDER BY login_time DESC 
        LIMIT 1
    ");
    $token_result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $token_result->execute();

    $token_data = $token_result->fetch(PDO::FETCH_ASSOC);

    if (!$token_data || $token_data['token'] !== $session_token || $token_data['session_duration'] >= 86400) {
        echo json_encode(["status" => "error", "message" => "Token inválido ou expirado"]);
        exit;
    }
*/

      // Gera um número aleatório de até 10 caracteres
    function gerarNumeroUnico($conexao) {
        do {
            // Gera um número aleatório de até 10 caracteres
            $numeroUnico = mt_rand(1000000000, 9999999999); 

            // Verifica se já existe no banco
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM detonacao WHERE numero_unico = :numero_unico");
            $stmt->bindParam(':numero_unico', $numeroUnico);
            $stmt->execute();
            $existe = $stmt->fetchColumn();
        } while ($existe > 0);

        return $numeroUnico;
    }

    $numeroUnico = gerarNumeroUnico($conexao);
function base64paraAssinaturas($base64_string, $output_file) {
    if (!preg_match('/^data:image\/(\w+);base64,/', $base64_string, $type)) {
        throw new Exception('Formato de Base64 inválido');
    }
    
    $data = explode(',', $base64_string);
    if (count($data) < 2) {
        throw new Exception('Dados Base64 incompletos');
    }
    
    $imageData = base64_decode($data[1]);
    if ($imageData === false) {
        throw new Exception('Falha ao decodificar Base64');
    }
    
    if (file_put_contents($output_file, $imageData) === false) {
        throw new Exception('Falha ao salvar a imagem');
    }
    
    return $output_file;
}
function enviarResposta($status, $message, $dados = null) {
    $response = [
        'status' => $status,
        'message' => $message
    ];

    // Se houver dados adicionais, adicione ao array de resposta
    if ($dados !== null) {
        $response['dados'] = $dados;
    }

    // Enviar a resposta JSON
    echo json_encode($response);
    exit;
}

function base64paraImagens($imagensBase64)
{
 $savedImages = [];
 $errors = [];

 foreach ($imagensBase64 as $index => $base64String) {
 $filename = 'uploads/imagens/imagem_' . uniqid() . '.png'; // Nome do arquivo PNG
 $data = explode(',', $base64String); // Separar metadados do conteúdo Base64

 if (count($data) === 2 && base64_decode($data[1], true)) {
 $decodedData = base64_decode($data[1]);

 // Validar se o conteúdo é uma imagem PNG
 $image = imagecreatefromstring($decodedData);
 if ($image !== false) {
 // Salvar a imagem no formato PNG
 if (imagepng($image, $filename)) {
 $savedImages[] = $filename; // Adiciona à lista de imagens salvas
 } else {
 $errors[] = "Erro ao salvar a imagem $index.";
 }
 imagedestroy($image);
 } else {
 $errors[] = "Imagem $index inválida ou corrompida.";
 }
 } else {
 $errors[] = "Formato inválido para a imagem $index.";
 }
 }

 return [
 'success' => empty($errors),
 'saved_images' => $savedImages,
 'errors' => $errors,
 ];
}


function gerarPDF($dados) {
    // Instanciando a classe FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);


 
    // Caminho relativo ao script
    $logoPath = __DIR__ . '/../../logo.png'; // Volta duas pastas para localizar a imagem

    // Verifica se o arquivo existe
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, ($pdf->GetPageWidth() - 40) / 2, $pdf->GetY(), 40); // Centraliza o logotipo sei la
        $pdf->Ln(50); // Espaçamento após o logotipo
    } else {
        $pdf->Cell(0, 10, utf8_decode('Logotipo não encontrado.'), 0, 1, 'C');
    }
    
    $pdf->Ln(10);
    $pdf->Ln(10);



    // Informações do Ministério da Defesa
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Ministério da Defesa'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode('Comando Militar do Sul'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode('Comando da 3ª Região Militar'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode('Serviço de Fiscalização de Produtos Controlados'), 0, 1, 'C');
    $pdf->Ln(10);
    
    // Título do termo de fiscalização
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 10, utf8_decode('Termo de Fiscalização/Vistoria Para Exposição De PCE'), 0, 1, 'C');
    $pdf->Ln(5);

        // Número do termo
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Número ______ /SFPC/3GAAE'), 0, 1, 'C');
        $pdf->Ln(10);
    
$campos = [
    'Razão Social' => $dados ['razaoSocial'],
    'Email' => $dados['email'],
    'Data' => $dados['data'],
    'TR/CR' => $dados['trcr'],
    'Endereço' => $dados['endereço'],
    'Telefone' => $dados['telefone'],
    'Telefone Residencial' => $dados['telefoneResidencial'],
    'CNPJ' => $dados['cnpj'],
    'Referência' => $dados['referencia'],
    'Coordenada' => $dados['coordenada'],
];
    // Título "Identificação do Fiscalizado"
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Identificação do Fiscalizado'), 1, 1, 'L');
    $pdf->Ln(5);

    // Razão Social (linha inteira)
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('Razão Social:'), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(150, 10, utf8_decode($dados['razaoSocial']), 1, 'L');
    $pdf->Ln(2); // Pequeno espaçamento

    // TR/CR e CNPJ (mesma linha, sem borda extra na resposta do CNPJ)
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('TR/CR:'), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(55, 10, utf8_decode($dados['trcr']), 'TBL', 0, 'L'); // Apenas borda superior, esquerda e inferior

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, utf8_decode('CNPJ:'), 'TBL', 0, 'L'); // Apenas borda superior, esquerda e inferior
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(75, 10, utf8_decode($dados['cnpj']), 'TBR', 1, 'L'); // Apenas borda superior, direita e inferior (sem borda extra na esquerda)
    $pdf->Ln(2); // Pequeno espaçamento

    // TR/CR e CNPJ (mesma linha, sem borda extra na resposta do CNPJ)
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('Telefone:'), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(55, 10, utf8_decode($dados['telefone']), 'TBL', 0, 'L'); // Apenas borda superior, esquerda e inferior

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, utf8_decode('Email'), 'TBL', 0, 'L'); // Apenas borda superior, esquerda e inferior
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(75, 10, utf8_decode($dados['email']), 'TBR', 1, 'L'); // Apenas borda superior, direita e inferior (sem borda extra na esquerda)
    $pdf->Ln(2); // Pequeno espaçamento

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('Endereço:'), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(150, 10, utf8_decode($dados['endereco']), 1, 'L');
    $pdf->Ln(2); // Pequeno espaçamento

     $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('Telefone Res:'), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(150, 10, utf8_decode($dados['telefoneResidencial']), 1, 'L');
    $pdf->Ln(2); // Pequeno espaçamento
//perguntas

    $pdf->Ln(10); // 1ª quebra
    $pdf->Ln(10); // 2ª quebra
    $pdf->Ln(10); // 3ª quebra

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Verificação Documental'), 1, 1, 'L');
$pdf->Ln(2); // Espaço pequeno

     //Coluna da Verificação
     $pdf->SetFont('Arial', 'B', 12);
     $pdf->Cell(20, 10, utf8_decode('N Ordem'), 1, 0, 'C'); // N Ordem
     $pdf->Cell(60, 10, utf8_decode('Itens A Verificar:'), 1, 0, 'C');
     $pdf->Cell(20, 10, utf8_decode('SIM'), 1, 0, 'C');
     $pdf->Cell(20, 10, utf8_decode('NÂO'), 1, 0, 'C');
     $pdf->Cell(30, 10, utf8_decode('Não se Aplica'), 1, 0, 'C');
     $pdf->Cell(40, 10, utf8_decode('Observações'), 1, 1, 'C');



/*$campos[
    'Razão Social' => $dados['razaoSocial'],
    'Email' => $dados['Email'],
    'TR/CR' => $dados['TR/CR'],
    'Endereço' => $dados['Endereço'],
    'Telefone' => $dados['Telefone'],
    'CNPJ' => $dados['CNPJ'],
    'Referencia' => $dados['Referencia'],
    'Coordenada' => $dados['Coordenada'],


]*/


    // Exibindo as perguntas, respostas e observações
    $perguntasFixas = [
       "O Certificado de Registro (CR) da empresa responsável pela exposição está vigente?",
      "Possui a atividade de utilização - demonstração / exposição de arma de fogo apostilada ao CR válido? (Ou do PCE a ser exposto). (Anexo B5 da Port nº 56/COLOG; Nr III do Art 3º da Port no 150/COLOG).",
      "Possui autorização do SFPC Regional para a realização do evento? (Art. 140 do Dec nº 10.030/2019)",
          "Existe controle de que todas as empresas envolvidas na exposição possuam atividade de exposição do PCE exposto e GT dos itens a serem expostos, incluindo prévia autorização do SFPC Regional?(Art. 15 do § 1º da Port nº 1.729/Cmt Ex).",
      "Existe Plano de segurança do evento?",
      "Existe um responsável pela Segurança do evento/ Plano de segurança?",
      "Existem: barreiras físicas / sistemas de segurança eletrônicos (alarmes, monitoramento à distância, vigias) / restrição quanto ao acesso de pessoal ao local de armazenagem e guarda dos PCE descritos no Plano de Segurança? ",
      "Existe local de depósito destinado a armazenagem de PCE compatível com o estoque?",
          "O Certificado de Registro (CR) da empresa expositora está vigente?",
      "Possui a atividade de utilização - demonstração / exposição de arma de fogo apostilada ao CR (ou apostilado o material a ser exposto)?",
      "As Guias de Tráfego (GT) são correspondentes aos PCE expostos?(Parágrafo 2º do Art. 33 do Dec º11.615/2023)",
      "Apresentou cópias das Notas Fiscais (NF) de entrada/invoice (i) das armas expostas? Para casos de armas importadas - Parágrafo único e nº III do Art. 30 do Decreto nº 10.030/19 e Art. 15 da Port nº 1729- Cmt Ex/19)",
      "As armas importadas constam no SICOFA? (º VI do Art 55 da Port nº 1729 - Cmt Ex/2019)",
      "As armas estão desmuniciadas? (Nr VIII do art 2º do Decreto nº 11.615/23)",
      "As armas estão sem o percussor? (Anexo F Port nº 150 - COLOG/2019)",
      "As PCE expostos estão inertes? (granadas, morteiros e etc) (Anexo F Port № 150- COLOG/2019)",
      "As munições estão expostas sem ter contato manual com o público? (Anexo F da Port nº 150 COLOG/2019)",
      "As quantidades de PCE em exposição estão dentro dos limites das dotações autorizadas no CR?",
      "Existe local de depósito destinado a armazenagem de PCE compatível com o estoque?",
      "Existe responsável pelo armazenamento dos PCE ?",
          "O Certificado de Registro (CR) do Colecionador está vigente? (Art 31 do Dec nº 11.615/2023)",
      "Possui Guia de Tráfego correspondente aos PCE expostos? (Parágrafo 2º do Art. 33 do Dec nº 11615/2023 e Art. 46 da Port nº 150 COLOG/2019)",
      "Os PCE expostos estão inertes? (Obs: A arma será considerada inerte a partir da remoção de peça do seu mecanismo de disparo - Anexo F da Port nº 150-COLOG/2019)",
      "O acervo exposto pertence ao Colecionador?",
      "Existe local de depósito destinado a armazenagem de PCE compatível com o estoque?",
      "Existe responsável pelo armazenamento dos PCE ? (Art 8º da Port nº 150 - COLOG/2019)",
        // Adicione mais perguntas aqui
    ];
  
    foreach ($dados['respostaDocumentacao'] as $i => $resposta) {
        $pergunta = $perguntasFixas[$i] ?? 'Pergunta não definida';
        $observacao = $dados['observacoes'][$i] ?? 'Sem observação';

        // Calcula a altura da célula com base no texto
        $alturaPergunta = $pdf->GetStringWidth($pergunta) > 60 ? 20 : 10; // Aumenta a altura se necessário
        $alturaObservacao = $pdf->GetStringWidth($observacao) > 40 ? 20 : 10;
        $alturaLinha = max($alturaPergunta, $alturaObservacao);

        // Número da Ordem
        $pdf->Cell(20, $alturaLinha, utf8_decode($i + 1), 1, 0, 'C');

        // Itens a Verificar (mantém altura fixa e evita quebra de linha extra)
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(60, 5, utf8_decode($pergunta), 0, 'L'); // Sem borda
        $pdf->Rect($x, $y, 60, $alturaLinha); // Desenha a borda manualmente

        // Ajusta a posição para continuar na mesma linha
        $pdf->SetXY($x + 60, $y);

        // Respostas (Sim / Não / Não se aplica)
        $pdf->Cell(20, $alturaLinha, utf8_decode($resposta == 'Sim' ? 'X' : ''), 1, 0, 'C');
        $pdf->Cell(20, $alturaLinha, utf8_decode($resposta == 'Não' ? 'X' : ''), 1, 0, 'C');
        $pdf->Cell(30, $alturaLinha, utf8_decode($resposta == 'Não se aplica' ? 'X' : ''), 1, 0, 'C');

        // Observações (mantém altura fixa e evita quebra de linha extra)
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(40, 5, utf8_decode($observacao), 0, 'L');
        $pdf->Rect($x, $y, 40, $alturaLinha);

        // Vai direto para a próxima linha (sem espaço extra)
        $pdf->SetY($y + $alturaLinha);
    }

    $pdf->Ln(10); // Espaçamento final

    // Título "Informações sobre Infração"
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Informações sobre Infração'), 1, 1, 'L');
    $pdf->Ln(5);

    // Definição das larguras das colunas
    $larguraRotulo = 70;  // Largura fixa para os rótulos
    $larguraValor = 120;   // Largura para os valores (permitindo quebra de linha)
    $alturaLinha = 10;     // Altura da linha base
    $espacamento = 2;      // Espaçamento entre as linhas

    // Lista de campos que podem ter textos longos
    $camposMultilinha = ['Lista de Deficiências', 'Observações Gerais', 'Especificar Deficiências'];

    // Função para calcular a altura do MultiCell
    function getMultiCellHeight($pdf, $texto, $largura, $alturaLinha)
    {
        $numLinhas = $pdf->GetStringWidth($texto) / $largura;
        $numLinhas = ceil($numLinhas); // Arredonda para cima
        return max($alturaLinha, $numLinhas * $alturaLinha); // Retorna a altura mínima necessária
    }

// Informações sobre infração
$camposInfracao = [
    'Lista de Deficiências' => $dados['lista_deficiencia'],
    'Observações Gerais' => $dados['observacoes_gerais'],
    'Qtd Autos de Infração' => $dados['qtd_autos_infracao'],
    'Qtd Termos de Infração' => $dados['qtd_termos_aprensao'],
    'Qtd Termos Depositário' => $dados['qtd_termos_depositario'],
];

foreach ($camposInfracao as $rotulo => $valor) {
    $pdf->SetFont('Arial', 'B', 12);
    
    // Calcula a altura da célula baseada no conteúdo
    if (in_array($rotulo, $camposMultilinha)) {
        $altura = getMultiCellHeight($pdf, $valor, $larguraValor, $alturaLinha);
    } else {
        $altura = $alturaLinha; // Usa a altura padrão
    }

    // Cria a célula do rótulo
    $pdf->Cell($larguraRotulo, $altura, utf8_decode($rotulo . ':'), 1, 0, 'L');

    // Cria a célula do valor com MultiCell
    $pdf->SetFont('Arial', '', 12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell($larguraValor, $alturaLinha, utf8_decode($valor), 1, 'L');

    // Ajusta a posição para a próxima linha
    $pdf->SetXY($x + $larguraValor, $y + $altura); 
    $pdf->Ln($espacamento);
}

// Espaçamento antes das infrações
$pdf->Ln(5);

// Adicionando lista de infrações (se existirem)
if (!empty($dados['infracao'])) {
    foreach ($dados['infracao'] as $infracao) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(0, $alturaLinha, utf8_decode('Infração: ' . $infracao), 1, 'L');
    }
}

    $pdf->Ln(10); // 1ª quebra
        $pdf->Ln(10); // 1ª quebra
    // Informações sobre infrações
   // $pdf->Cell(95, 10, utf8_decode('Qtd Autos de Infração: ' . $dados['qtd_autos_infracao']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Qtd Termos de Infração: ' . $dados['qtd_termos_aprensao']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('Qtd Termos Depositário: ' . $dados['qtd_termos_depositario']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Especificar Deficiências: ' . $dados['especificar_deficiencias_encontradas']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('Prazo Deficiências: ' . $dados['prazo_deficiencias']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Nome Fiscal Militar: ' . $dados['nome_fiscal_militar']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('P/G Fiscal Militar: ' . $dados['fiscal_pg']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Idt. Fiscal Militar: ' . $dados['idtmilitar']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('OM Fiscal Militar: ' . $dados['ommilitar']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Empresa Fiscalizada: ' . $dados['nome_empresa']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('CPF Empresa: ' . $dados['cpf_empresa']), 0, 0);
  //  $pdf->Cell(95, 10, utf8_decode('Testemunha 1: ' . $dados['testemunha1']), 0, 1);
   // $pdf->Cell(95, 10, utf8_decode('Idt Testemunha 1: ' . $dados['itdtestemunha1']), 0, 0);
   // $pdf->Cell(95, 10, utf8_decode('Testemunha 2: ' . $dados['testemunha2']), 0, 1);
  //  $pdf->Cell(95, 10, utf8_decode('Idt Testemunha 2: ' . $dados['itdtestemunha2']), 0, 0);

    // Adicionando título e imagens
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Imagens:'), 0, 1, 'C');
$pdf->Ln(5); // Espaçamento após o título

// Adicionando imagens
if (!empty($_POST['imagens'])) {
    $imagensBase64 = json_decode($_POST['imagens'], true);
    if (is_array($imagensBase64)) {
        $resultadoImagens = base64paraImagens($imagensBase64);
        if (!empty($resultadoImagens['saved_images'])) {
            $xPosition = 10;
            $yPosition = $pdf->GetY(); // Pega a posição Y atual após o título
            $maxWidth = 190; // Largura máxima disponível por linha
            $maxImagesPerRow = 5; // Máximo de imagens por linha
            $imageWidth = ($maxWidth - ($maxImagesPerRow - 1) * 5) / $maxImagesPerRow; // Calculando a largura das imagens para se ajustarem

            foreach ($resultadoImagens['saved_images'] as $index => $imagem) {
                if (file_exists($imagem)) {
                    $imageInfo = getimagesize($imagem);
                    if ($imageInfo !== false && $imageInfo[2] === IMAGETYPE_PNG) {
                        try {
                            // Ajustar a altura automaticamente para manter a proporção
                            $aspectRatio = $imageInfo[0] / $imageInfo[1]; // Largura / Altura da imagem original
                            $imageHeight = $imageWidth / $aspectRatio; // Calcula a altura proporcional

                            // Adicionar a imagem no PDF
                            $pdf->Image($imagem, $xPosition, $yPosition, $imageWidth, $imageHeight);

                            // Atualizar a posição para a próxima imagem
                            $xPosition += $imageWidth + 5; // 5 de espaçamento entre as imagens

                            // Quando atinge o limite de 5 imagens, começa uma nova linha
                            if (($index + 1) % $maxImagesPerRow === 0) {
                                $xPosition = 10; // Reinicia a posição horizontal
                                $yPosition += $imageHeight + 5; // Move para a próxima linha com base na altura da imagem
                            }

                            // Verificar se há espaço suficiente para as assinaturas, caso contrário, adicionar uma nova página
                            if ($yPosition + 50 > $pdf->getPageHeight()) {
                                $pdf->AddPage(); // Adiciona uma nova página se o espaço for insuficiente
                                $yPosition = 10; // Reseta a posição Y para o topo
                            }
                        } catch (Exception $e) {
                            $pdf->MultiCell(0, 10, "Erro ao inserir a imagem: " . $e->getMessage());
                        }
                    }
                }
            }
        }
           $pdf->Ln(10); // 1ª quebra
    }
    $pdf->Ln(10); // 2ª quebra
    $pdf->Ln(10); // 3ª quebra
    $pdf->Ln(10); // 4ª quebra
       $pdf->Ln(10); // 5ª quebra
          $pdf->Ln(10); // 6ª quebra
             $pdf->Ln(10); // 7ª quebra
}

   $pdf->Ln(10); // 8ª quebra
   $pdf->Ln(10); // 9ª quebra
    $pdf->Ln(10); // 10ª quebra

$pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Correção de Deficiências Encontradas'), 1, 1, 'L');
    $pdf->Ln(5);// Espaço pequeno

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, utf8_decode('Nada a corrigir ou ' . $dados['especificar_deficiencias_encontradas'] .  ' (especificar)'),1 , 1, 'L');
    $pdf->Ln(5);
     $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(190, 10, utf8_decode('A empresa deve atentar ao observado no item 4, "LISTA DE DEFICIÊNCIAS ENCONTRADAS" e providenciar a correção de irregularidades verificadas no prazo de ' . $dados['prazo_deficiencias'] . ' dias, sob pena de instauração de Processo Administrativo Sancionador'),1, 'L');

    $pdf->Ln(10); // 3ª quebra


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Da comprovação do cumprimento:
:'), 0, 1, 'C');
$pdf->Ln(5); // Espaçamento após o título

     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('É de responsabilidade do fiscalizado fornecer documentos, fotos, relatórios,'), 0, 1, 'L');
     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('termos e demais subsídios de forma a comprovar o cumprimento das pendências'), 0, 1, 'L');
     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('apontadas no item 4. LISTA DE DEFICIÊNCIAS ENCONTRADAS, apresentando-as junto'), 0, 1, 'L');
     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('ao SFPC/RM. O não cumprimento das pendências e/ou a não informação deste à'), 0, 1, 'L');
     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('fiscalização de produtos controlados dentro do prazo estipulado neste item'), 0, 1, 'L');
     $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0,10, utf8_decode('implicará na instauração de Processo Administrativo Sancionador.'), 0, 1, 'L');

$pdf->Ln(5); //  quebra

// Garantindo espaço suficiente para as assinaturas
$pdf->Ln(10); // Deixe um espaçamento extra após as imagens antes das assinaturas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Assinaturas:'), 1, 1, 'C');
$pdf->Ln(5); // Pequeno espaçamento

// Continue com as assinaturas
$larguraTitulo = 60;
$larguraAssinatura = 90;
$alturaLinha = 10;
$alturaAssinatura = 40;
$alturaTexto = 8;
$alturaTotalAssinatura = $alturaAssinatura + (count($assinaturas[0]['campos_extras']) + 1) * $alturaTexto + 10; // Altura total estimada

// Dados das assinaturas
$assinaturas = [
    [
        "titulo" => "Fiscal Militar",
        
        "campos_extras" => [
            "Nome Fiscal Militar" => $dados['nome_fiscal_militar'],
            "P/G" => $dados['fiscal_pg'],
            "IDT" => $dados['idtmilitar'],
            "OM" => $dados['ommilitar']
        ]
    ],
    [
        "titulo" => "Responsável Empresa",
    
        "campos_extras" => [
            "Nome" => $dados['nome_empresa'],
            "CPF" => $dados['cpf_empresa']
        ]
    ],
    [
        "titulo" => "Testemunha 1",
        
        "campos_extras" => [
            "Nome" => $dados['testemunha1'],
            "IDT" => $dados['idtestemunha1']
        ]
    ],
    [
        "titulo" => "Testemunha 2",

        "campos_extras" => [
            "Nome" => $dados['testemunha2'],
            "IDT" => $dados['idtestemunha2']
        ]
    ]
];
// Processando assinaturas
foreach ($assinaturas as $i => $assinatura) {
    $campoAssinatura = 'assinatura' . ($i + 1);

    if (isset($dados[$campoAssinatura]) && !empty($dados[$campoAssinatura])) {
        try {
            $assinaturaImg = base64paraAssinaturas($dados[$campoAssinatura], 'PDF/FormulariosPDF/' . $campoAssinatura . '.png');

            // **Verifica se há espaço suficiente na página antes de adicionar a assinatura**
            if ($pdf->GetY() + $alturaTotalAssinatura > $pdf->GetPageHeight() - 20) {
                $pdf->AddPage();
                $pdf->Ln(10); // Pequeno espaçamento no topo da nova página
            }

            // Linha do título
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, $alturaLinha, utf8_decode($assinatura['titulo']), 1, 1, 'C');

            // Linha da assinatura
            $posX = $pdf->GetX();
            $posY = $pdf->GetY();
            $pdf->Cell(0, $alturaAssinatura, '', 1, 1, 'C'); // Célula para a assinatura
            $pdf->Image($assinaturaImg, $posX + 40, $posY + 5, 60, 0);  // Ajuste fino da largura



            // Campos extras (P/G, IDT, OM ou CPF)
            foreach ($assinatura['campos_extras'] as $campo => $valor) {
                $pdf->Cell(40, $alturaTexto, utf8_decode($campo . ":"), 1, 0, 'L');
                $pdf->Cell(150, $alturaTexto, utf8_decode($valor), 1, 1, 'L');
            }

            $pdf->Ln(5); // Pequeno espaçamento entre as assinaturas

        } catch (Exception $e) {
            error_log('Erro ao processar a assinatura: ' . $e->getMessage());
        }
    }
}

    // Gerando nome do arquivo PDF com timestamp
    $timestamp = time();
    $nomeArquivo = 'vistoria_' . $dados['user_id'] . '_' . $timestamp . '.pdf';
    
    // Saída do PDF
    $pdf->Output('F', 'PDF/FormulariosPDF/' . $nomeArquivo); 
    return $nomeArquivo; 
}


try {
    // Receber dados
    $user_id = trim($_POST['user_id']);
    $razaoSocial = trim($_POST['razaoSocial']);
    $email = trim($_POST['email']);
    $data = trim($_POST['data']);
    $trcr = trim($_POST['trcr']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $telefoneResidencial = trim($_POST['telefoneResidencial']);
    $cnpj = trim($_POST['cnpj']);

    $observacoes = json_decode($_POST['observacoes'] , true);
    $respostaDocumentacao = json_decode($_POST['respostaDocumentacao'] , true);

    $lista_deficiencia = trim($_POST['lista_deficiencia']);
    $observacoes_gerais = trim($_POST['observacoes_gerais']);
    $infracao = json_decode($_POST['infracao'] , true);
    $qtd_autos_infracao = trim($_POST['qtd_autos_infracao']);
    $qtd_termos_aprensao = trim($_POST['qtd_termos_aprensao']);
    $qtd_termos_depositario = trim($_POST['qtd_termos_depositario']);
    $especificar_deficiencias_encontradas = trim($_POST['especificar_deficiencias_encontradas']);
    $prazo_deficiencias = trim($_POST['prazo_deficiencias']);
    $nome_fiscal_militar = trim($_POST['nome_fiscal_militar']);
    $fiscal_pg = trim($_POST['fiscal_pg']);
    $idtmilitar = trim($_POST['idtmilitar']);
    $ommilitar = trim($_POST['ommilitar']);
    $nome_empresa = trim($_POST['nome_empresa']);
    $cpf_empresa = trim($_POST['cpf_empresa']);
    $testemunha1 = trim($_POST['testemunha1']);
    $idtestemunha1 = trim($_POST['idtestemunha1']);
    $testemunha2 = trim($_POST['testemunha2']);
    $idtestemunha2 = trim($_POST['idtestemunha2']);
    $assinatura1 = $_POST['assinatura1'];
    $assinatura2 = $_POST['assinatura2'];
    $assinatura3 = $_POST['assinatura3'];
    $assinatura4 = $_POST['assinatura4'];

    $imagensBase64 = json_decode($_POST['imagens'], true);

    // Log dos dados recebidos
    error_log(print_r($_POST, true));

    $naoPreenchidas = [];

    // Função para verificar se as respostas estão preenchidas
    function verificarPreenchimento($perguntas, $tipoPergunta) {
        global $naoPreenchidas;

        foreach ($perguntas as $indice => $resposta) {
            // Verifica se a resposta é nula, vazia ou não foi enviada
            if (is_null($resposta) || $resposta === '' || !isset($resposta)) {
                $naoPreenchidas[] = "Pergunta " . ($indice + 1) . " não foi preenchida ($tipoPergunta)";
            }
        }
    }

    // Função para substituir valores nulos ou vazios por "Não se Aplica"
    function substituirPorNaoSeAplica(&$array) {
        foreach ($array as $indice => $valor) {
            if (is_null($valor) || $valor === '') {
                $array[$indice] = "Não se Aplica";
            }
        }
    }

    // Substitui valores vazios ou nulos nas observações e respostas por "Não se Aplica"
    substituirPorNaoSeAplica($observacoes);

    // Verifica se as variáveis são arrays antes de chamar a função
    if (is_array($respostaDocumentacao)) {
        verificarPreenchimento($respostaDocumentacao, 'Verificação da Empresa (Sim) ou (Não)');
    } else {
        $naoPreenchidas[] = "Verificação da Empresa (Sim) ou (Não) não foi preenchida.";
    }

    if (is_array($observacoes)) {
        verificarPreenchimento($observacoes, 'Verificação da Empresa em Observações');
    } else {
        $naoPreenchidas[] = "Observações não foram preenchidas.";
    }

    // Verificação da infração
    if (is_array($infracao)) {
        verificarPreenchimento($infracao, 'Infração');
    } else {
        $naoPreenchidas[] = "Infração não foi preenchida.";
    }

    // Se houver perguntas não preenchidas, retorna um erro
    if (!empty($naoPreenchidas)) {
        // Juntar as mensagens em uma única string
        $mensagem = implode("\n", $naoPreenchidas);
        echo json_encode(["status" => "error", "message" => $mensagem]);
        exit;
    } 
    if (empty($email)){
        echo json_encode(["status" => "error", "message" => "Campo Email em branco."]);
        exit;
    }
    if (empty($data)){
        echo json_encode(["status" => "error", "message" => "Campo Data em branco."]);
        exit;
    }
    if (empty($razaoSocial)){
        echo json_encode(["status" => "error", "message" => "Campo RazaoSocial em branco."]);
        exit;
    }
    if (empty($trcr)){
        echo json_encode(["status" => "error", "message" => "Campo TRCR em branco."]);
        exit;
    }
    if (empty($endereco)){
        echo json_encode(["status" => "error", "message" => "Campo Endereço em branco."]);
        exit;
    }
    if (empty($cnpj)){
        echo json_encode(["status" => "error", "message" => "Campo CNPJ em branco."]);
        exit;
    }
    if (empty($telefone)){
            echo json_encode(["status" => "error", "message" => "Campo Telefone em branco."]);
            exit;
    }
    if (empty($telefoneResidencial)){
        echo json_encode(["status" => "error", "message" => "Campo Telefone Residencialem branco."]);
        exit;
}
if (empty($lista_deficiencia)){
    echo json_encode(["status" => "error", "message" => "Campo Lista Deficiencia em branco."]);
    exit;
}
if (empty($observacoes_gerais)){
    echo json_encode(["status" => "error", "message" => "Campo Observações Gerais em branco."]);
    exit;
}
if (empty($qtd_autos_infracao)){
    echo json_encode(["status" => "error", "message" => "Campo Qtd Autos Infração em branco."]);
    exit;
}
if (empty($qtd_termos_aprensao)){
    echo json_encode(["status" => "error", "message" => "Campo Qtd Termos Apreensão em branco."]);
    exit;
}
if (empty($qtd_termos_depositario)){
    echo json_encode(["status" => "error", "message" => "Campo Qtd Termos Depositário em branco."]);
    exit;
}
if (empty($especificar_deficiencias_encontradas)){
    echo json_encode(["status" => "error", "message" => "Campo Especificar Deficiencias Encontradas em branco."]);
    exit;
}
if (empty($prazo_deficiencias)){
    echo json_encode(["status" => "error", "message" => "Campo Prazo Deficiencias em branco."]);
    exit;
}
if (empty($nome_fiscal_militar)){
    echo json_encode(["status" => "error", "message" => "Campo Nome Fiscal Militar em branco."]);
    exit;
}
if (empty($idtmilitar)){
    echo json_encode(["status" => "error", "message" => "Campo IDT Militar em branco."]);
    exit;
}
if (empty($fiscal_pg)){
    echo json_encode(["status" => "error", "message" => "Campo Fiscal PG em branco."]);
    exit;
}
if (empty($ommilitar)){
    echo json_encode(["status" => "error", "message" => "Campo OM Militar em branco."]);
    exit;
}
    
if (empty($nome_empresa)){
    echo json_encode(["status" => "error", "message" => "Campo Nome Empresa em branco."]);
    exit;
}
if (empty($cpf_empresa)){
    echo json_encode(["status" => "error", "message" => "Campo CPF Empresa em branco."]);
    exit;
}
if (empty($testemunha1)){
    echo json_encode(["status" => "error", "message" => "Campo Nome Testemunha 1 em branco."]);
    exit;
}
if (empty($idtestemunha1)){
    echo json_encode(["status" => "error", "message" => "Campo IDT Testemunha 1 em branco."]);
    exit;
}
if (empty($testemunha2)){
    echo json_encode(["status" => "error", "message" => "Campo Nome Testemunha 2 em branco."]);
    exit;
}
if (empty($idtestemunha2)){
    echo json_encode(["status" => "error", "message" => "Campo Idt Testemunha 2 em branco."]);
    exit;
}

$maximoComprimentoCamposRazaoSocial = 200;
if(strlen($razaoSocial) > $maximoComprimentoCamposRazaoSocial ){
    $response = array("status" => "error", "message" => "A Razão Social não pode conter mais que $maximoComprimentoCamposRazaoSocial caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposTRCR = 200;
if(strlen($trcr) > $maximoComprimentoCamposTRCR ){
    $response = array("status" => "error", "message" => "O TR/CR não pode conter mais que $maximoComprimentoCamposTRCR caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposCnpj = 18;
if(strlen($cnpj) < $maximoComprimentoCamposCnpj ){
    $response = array("status" => "error", "message" => "O campo do CNPJ não pode conter menos que $maximoComprimentoCamposCnpj caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposEndereco = 200;
if(strlen($endereco) > $maximoComprimentoCamposEndereco ){
    $response = array("status" => "error", "message" => "O campo Endereço não pode conter mais que $maximoComprimentoCamposEndereco caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposTelefone = 15;
if(strlen($telefone) < $maximoComprimentoCamposTelefone ){
    $response = array("status" => "error", "message" => "O Campo Telefone não pode conter mais que $maximoComprimentoCamposTelefone caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposTelefoneResidencial = 14;
if(strlen($telefone) < $maximoComprimentoCamposTelefone ){
    $response = array("status" => "error", "message" => "O Campo Telefone Residencial não pode conter mais que $maximoComprimentoCamposTelefone caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoEmail = 200;
if(strlen($email) > $maximoComprimentoCampoEmail ){
    $response = array("status" => "error", "message" => "O Email não pode conter mais que $maximoComprimentoCampoEmail caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCamposData = 10;
if(strlen($data) < $maximoComprimentoCamposData ){
    $response = array("status" => "error", "message" => "O Campo Data não pode conter menos que $maximoComprimentoCamposData caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoDeficiencia = 200;
if(strlen($lista_deficiencia) > $maximoComprimentoCampoDeficiencia ){
    $response = array("status" => "error", "message" => "O Campo Lista Deficiência não pode conter mais que $maximoComprimentoCampoDeficiencia caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoOBSgerais = 200;
if(strlen($observacoes_gerais) > $maximoComprimentoCampoOBSgerais ){
    $response = array("status" => "error", "message" => "O Campo Observações Gerais não pode conter mais que $maximoComprimentoCampoOBSgerais caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoQtdAutoInfracao = 2;
if(strlen($qtd_autos_infracao) > $maximoComprimentoCampoQtdAutoInfracao ){
    $response = array("status" => "error", "message" => "A Qtd Auto Infração não pode conter mais que $maximoComprimentoCampoQtdAutoInfracao caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoQtdTermosApreensao = 2;
if(strlen($qtd_termos_aprensao) > $maximoComprimentoCampoQtdTermosApreensao ){
    $response = array("status" => "error", "message" => "A Qtd Termos Apreensão  não pode conter mais que $maximoComprimentoCampoQtdTermosApreensao caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoQtdTermosFielDepositario = 2;
if(strlen($qtd_termos_depositario) > $maximoComprimentoCampoQtdTermosFielDepositario ){
    $response = array("status" => "error", "message" => "A Qtd Termos Fiel Depositário não pode conter mais que $maximoComprimentoCampoQtdTermosFielDepositario caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoEspecificarDef = 50;
if(strlen($especificar_deficiencias_encontradas) > $maximoComprimentoCampoEspecificarDef ){
    $response = array("status" => "error", "message" => "O Campo Especificar Deficiências não pode conter mais que $maximoComprimentoCampoEspecificarDef caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoPrazoDef = 2;
if(strlen($prazo_deficiencias) > $maximoComprimentoCampoPrazoDef ){
    $response = array("status" => "error", "message" => "O Campo Prazo não pode conter mais que $maximoComprimentoCampoPrazoDef caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoNomeFiscalMilitar = 200;
if(strlen($nome_fiscal_militar) > $maximoComprimentoCampoNomeFiscalMilitar ){
    $response = array("status" => "error", "message" => "O Nome Fiscal Militar não pode conter mais que $maximoComprimentoCampoNomeFiscalMilitar caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoIDTmilitar = 200;
if(strlen($idtmilitar) > $maximoComprimentoCampoIDTmilitar ){
    $response = array("status" => "error", "message" => "O Campo IDT Militar não pode conter mais que $maximoComprimentoCampoIDTmilitar caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoPG = 200;
if(strlen($fiscal_pg) > $maximoComprimentoCampoPG ){
    $response = array("status" => "error", "message" => "O Campo P/G não pode conter mais que $maximoComprimentoCampoPG caracteres");
    echo json_encode($response);
    exit;
}   
$maximoComprimentoCampoOM = 200;
if(strlen($ommilitar) > $maximoComprimentoCampoOM ){
    $response = array("status" => "error", "message" => "O Campo OM Militar não pode conter mais que $maximoComprimentoCampoOM caracteres");
    echo json_encode($response);
    exit;
} 
$maximoComprimentoCampoNomeEmpresa = 200;
if(strlen($nome_empresa) > $maximoComprimentoCampoNomeEmpresa ){
    $response = array("status" => "error", "message" => "O Campo Nome Empresa não pode conter mais que $maximoComprimentoCampoNomeEmpresa caracteres");
    echo json_encode($response);
    exit;
}  
$maximoComprimentoCampoCPFempresa = 200;
if(strlen($cpf_empresa) > $maximoComprimentoCampoCPFempresa ){
    $response = array("status" => "error", "message" => "O Campo CPF Empresa não pode conter mais que $maximoComprimentoCampoCPFempresa caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoTestemunha1Nome = 200;
if(strlen($testemunha1) > $maximoComprimentoCampoTestemunha1Nome ){
    $response = array("status" => "error", "message" => "O Campo Nome Testemunha 1 não pode conter mais que $maximoComprimentoCampoTestemunha1Nome caracteres");
    echo json_encode($response);
    exit;
} 
$maximoComprimentoCampoTestemunha1IDT = 200;
if(strlen($idtestemunha1) > $maximoComprimentoCampoTestemunha1IDT ){
    $response = array("status" => "error", "message" => "O Campo IDT Militar Testemunha 1 não pode conter mais que $maximoComprimentoCampoTestemunha1IDT caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoTestemunha2Nome = 200;
if(strlen($testemunha2) > $maximoComprimentoCampoTestemunha2Nome ){
    $response = array("status" => "error", "message" => "O Campo Nome Testemunha 2 não pode conter mais que $maximoComprimentoCampoTestemunha2Nome caracteres");
    echo json_encode($response);
    exit;
}
$maximoComprimentoCampoTestemunha2IDT = 200;
if(strlen($idtestemunha2) > $maximoComprimentoCampoTestemunha2IDT ){
    $response = array("status" => "error", "message" => "O Campo IDT Militar Testemunha 2 não pode conter mais que $maximoComprimentoCampoTestemunha2IDT caracteres");
    echo json_encode($response);
    exit;
}
   
    // Gerar PDF
    $dadosPDF = [
        'user_id' => $user_id,
        'razaoSocial' => $razaoSocial,
        'email' => $email,
        'data' => $data,
        'trcr' => $trcr,
        'endereco' => $endereco,
        'telefone' => $telefone,
        'telefoneResidencial' => $telefoneResidencial,
        'cnpj' => $cnpj,
        'referencia' => $referencia,
        'coordenada' => $coordenada,
        'respostaDocumentacao' => $respostaDocumentacao,
        'observacoes' => $observacoes,
        'lista_deficiencia' => $lista_deficiencia,
        'observacoes_gerais' => $observacoes_gerais,
        'infracao' => $infracao,
        'qtd_autos_infracao' => $qtd_autos_infracao,
        'qtd_termos_aprensao' => $qtd_termos_aprensao,
        'qtd_termos_depositario' => $qtd_termos_depositario,
        'especificar_deficiencias_encontradas' => $especificar_deficiencias_encontradas,
        'prazo_deficiencias' => $prazo_deficiencias,
        'assinatura1' => $assinatura1,
        'assinatura2' => $assinatura2,
        'assinatura3' => $assinatura3,
        'assinatura4' => $assinatura4,

        'nome_fiscal_militar' => $nome_fiscal_militar,
        'fiscal_pg' => $fiscal_pg,
        'idtmilitar' => $idtmilitar,
        'ommilitar' => $ommilitar,
        'nome_empresa' => $nome_empresa,
        'cpf_empresa' => $cpf_empresa,
        'testemunha1' => $testemunha1,
        'idtestemunha1' => $idtestemunha1,
        'testemunha2' => $testemunha2,
        'idtestemunha2' => $idtestemunha2,
        'imagens' => $imagensSalvas,

    ];

    $nomeArquivoPDF = gerarPDF($dadosPDF);

    // Inserir no banco
    $stmt = $conexao->prepare("INSERT INTO vistoriapce (user_id, razaoSocial, email, data, trcr, endereco, telefone, telefoneResidencial, cnpj, pdf_file) VALUES (:user_id, :razaoSocial, 
    :email, :data, :trcr, :endereco, :telefone,:telefoneResidencial, :cnpj, :pdf_file)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':razaoSocial', $razaoSocial);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':data', $data);
    $stmt->bindParam(':trcr', $trcr);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':telefoneResidencial', $telefoneResidencial);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':pdf_file', $nomeArquivoPDF); // Adiciona o nome do PDF

    $stmt->execute();

    $vistoriapce_id = $conexao->lastInsertId();

    // Inserção na tabela respostas_execucao_detonacao
$stmt_respostas_execucao = $conexao->prepare("INSERT INTO verificacao_documental_vistoria (user_id, vistoriapce_id, respostaDocumentacao, observacoes) VALUES (:user_id, :vistoriapce_id, :respostaDocumentacao, :observacoes)");
for ($i = 0; $i < count($respostaDocumentacao); $i++) {
    $stmt_respostas_execucao->bindParam(':user_id', $user_id);
    $stmt_respostas_execucao->bindParam(':vistoriapce_id', $vistoriapce_id);
    $stmt_respostas_execucao->bindValue(':respostaDocumentacao', $respostaDocumentacao[$i]);
    $stmt_respostas_execucao->bindValue(':observacoes', $observacoes[$i]);

    $stmt_respostas_execucao->execute();
}
// Inserção na tabela deficiencias_observacoes_entidade
$smtpinfracao = $conexao->prepare("INSERT INTO deficiencias_observacoes_vistoriapce (user_id, vistoriapce_id, lista_deficiencia, observacoes_gerais, infracao, qtd_autos_infracao, qtd_termos_aprensao, qtd_termos_depositario, especificar_deficiencias_encontradas, prazo_deficiencias, nome_fiscal_militar, fiscal_pg, idtmilitar, ommilitar, nome_empresa, cpf_empresa, testemunha1, idtestemunha1, testemunha2, idtestemunha2) VALUES (:user_id, :vistoriapce_id, :lista_deficiencia, :observacoes_gerais, :infracao, :qtd_autos_infracao, :qtd_termos_aprensao, :qtd_termos_depositario, :especificar_deficiencias_encontradas, :prazo_deficiencias, :nome_fiscal_militar, :fiscal_pg, :idtmilitar, :ommilitar, :nome_empresa, :cpf_empresa, :testemunha1, :idtestemunha1, :testemunha2, :idtestemunha2)");

// Bind dos parâmetros fora do loop
$smtpinfracao->bindParam(':user_id', $user_id);
$smtpinfracao->bindParam(':vistoriapce_id', $vistoria);
$smtpinfracao->bindParam(':lista_deficiencia', $lista_deficiencia);
$smtpinfracao->bindParam(':observacoes_gerais', $observacoes_gerais);
$smtpinfracao->bindParam(':qtd_autos_infracao', $qtd_autos_infracao);
$smtpinfracao->bindParam(':qtd_termos_aprensao', $qtd_termos_aprensao);
$smtpinfracao->bindParam(':qtd_termos_depositario', $qtd_termos_depositario);
$smtpinfracao->bindParam(':especificar_deficiencias_encontradas', $especificar_deficiencias_encontradas);
$smtpinfracao->bindParam(':prazo_deficiencias', $prazo_deficiencias);
$smtpinfracao->bindParam(':nome_fiscal_militar', $nome_fiscal_militar);
$smtpinfracao->bindParam(':fiscal_pg', $fiscal_pg);
$smtpinfracao->bindParam(':idtmilitar', $idtmilitar);
$smtpinfracao->bindParam(':ommilitar', $ommilitar);
$smtpinfracao->bindParam(':nome_empresa', $nome_empresa);
$smtpinfracao->bindParam(':cpf_empresa', $cpf_empresa);
$smtpinfracao->bindParam(':testemunha1', $testemunha1);
$smtpinfracao->bindParam(':idtestemunha1', $idtestemunha1);
$smtpinfracao->bindParam(':testemunha2', $testemunha2);
$smtpinfracao->bindParam(':idtestemunha2', $idtestemunha2);

for ($i = 0; $i < count($infracao); $i++) {
    $smtpinfracao->bindValue(':infracao', $infracao[$i]);
    $smtpinfracao->execute();
}
    //Comentario só para dizer que eu é SEXTA-FEIRA

    // Configurar e enviar o e-mail
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.florestasenegocios.com.br';  // Endereço do servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'sindi@florestasenegocios.com.br';  // Endereço de e-mail usado para o envio
        $mail->Password = 'sindi123@A';  // Senha do e-mail
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;  // Tipo de criptografia
        $mail->Port = 587;

        // Configurações do e-mail
        $mail->setFrom('sindi@florestasenegocios.com.br', 'Software Exercito Fiscalizacao');
        $mail->addAddress($email, $nome);  // E-mail e nome do destinatário

        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Cadastro';
        
  // Corpo do e-mail em HTML com as variáveis corretas
  $respostaDocumentalHTML = '';
  for ($i = 0; $i < count($respostaDocumentacao); $i++) {
      $respostasDocumentalHTML .= '<strong>Resposta ' . ($i + 1) . ':</strong> ' . $respostaDocumentacao[$i] . '<br />';
      $respostasDocumentalHTML .= '<strong>Observação ' . ($i + 1) . ':</strong> ' . $observacoes[$i] . '<br /><br />';
  }

        // Corpo do e-mail em HTML com as variáveis
        $mail->Body = "
             <p>Seu cadastro foi realizado com sucesso! Seguem abaixo os detalhes:</p>
            <strong>RazaoSocial:</strong> $razaoSocial<br />
            <strong>Email:</strong> $email<br />
            <strong>Data:</strong> $data<br />
            <strong>TRCR:</strong> $trcr<br />
            <strong>Endereço:</strong> $endereco<br />
            <strong>Telefone:</strong> $telefone<br />
            <strong>Telefone Residencial:</strong> $telefoneResidencial<br />

            <strong>CNPJ:</strong> $cnpj<br />
            <strong>Referência:</strong> $referencia<br />
            <strong>Coordenada:</strong> $coordenada<br /><br />
             $respostaDocumentalHTML
            $respostasObservacoesExecucaoHTML
            <strong>$lista_deficiencia<br /><br />
            <strong>$observacoes_gerais<br /><br />
            <strong>$qtd_autos_infracao<br /><br />
            <strong>$qtd_termos_aprensao<br /><br />
            <strong>$qtd_termos_depositario<br /><br />
            <strong>$especificar_deficiencias_encontradas<br /><br />
            <strong>$prazo_deficiencias<br /><br />
            <p><em>Você não precisa responder a este e-mail.</em></p>
             <p><a href='https://woodexport.com.br/turmati/brian/aplicativo/FormFiscalizacao/PDF/FormulariosPDF/$nomeArquivoPDF'>Baixe seu PDF aqui</a></p>
        "; // Link para o PDF

        // Enviar o e-mail antes de retornar a resposta
        if ($mail->send()) {
            echo json_encode(["status" => "success", "message" => "Cadastro bem-sucedido. Confirmação enviada por e-mail."]);
        } else {
            error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
            echo json_encode(["status" => "error", "message" => "Cadastro bem-sucedido, mas erro ao enviar o e-mail."]);
        }
    } catch (Exception $e) {
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
        echo json_encode(["status" => "error", "message" => "Erro ao enviar o e-mail: " . $e->getMessage()]);
    }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Erro na conexão com o banco de dados: " . $e->getMessage()]);
    }  
    /*
    }catch(Exception $ex){
    echo json_encode(["status" => "error", "message" => "Token Inválido " . $e->getMessage()]);   
}*/
