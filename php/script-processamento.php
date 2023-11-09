<?php
// Dados de conexão ao banco de dados
$servername = "localhost";
$username = "u647134516_webadmin";
$password = "V1q@&rZpS?";
$dbname = "u647134516_webemconstruct";

// Conexão ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);




if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if (isset($_POST["enviar"])) {
				    if (!empty($_POST['g-recaptcha-response'])) {
				        $url = "https://www.google.com/recaptcha/api/siteverify";
				        $secret = "6Le65twoAAAAABJ15bpALkrtL58Couc80kRfdG6c";
				        $response = $_POST['g-recaptcha-response'];
				        $variaveis = "secret=".$secret."&response=".$response;
				        
				        $ch = curl_init($url);
				        curl_setopt( $ch, CURLOPT_POST, 1);
				        curl_setopt( $ch, CURLOPT_POSTFIELDS, $variaveis);
				        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				        curl_setopt( $ch, CURLOPT_HEADER, 0);
				        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				        $resposta = curl_exec($ch);
				        $resultado = json_decode($resposta);
				        
				        if ($resultado->success == 1) { 
				        
                            $nome = $_POST["nome"];
                            $telefone = $_POST["telefone"];
                            $email = $_POST["email"];
                            $mensagem = $_POST["mensagem"];                         
                            $targetDir = "arquivos/"; 
                            $targetFile = $targetDir . basename($_FILES["arquivo"]["name"]); 
                            
                            if (isset($_FILES["arquivo"]) && !empty($_FILES["arquivo"]["name"])) {
                                
                            $tamArquivo = $_FILES['arquivo'];
                            $nomeDoArquivo = $tamArquivo['name'];
                            $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                                       
                                        if ($tamArquivo['size'] > 20971520) {
                                            die("Arquivo excede tamanho permitido. Max: 20mb");
                                        }
                                        if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != "pdf") {
                                            var_dump($_FILES['arquivo']);
                                            die ("Formato de Arquivo não suportado. Formatos suportados: jpg, png, pdf.");
                                        }
                                   
                            		    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $targetFile)) {
                                        
                                        $arquivo = $targetFile;
                                
                                        } else {
                                        
                                        $arquivo = " Nenhum arquivo enviado.";
                                        }
                            } else {
                                $arquivo = "Nenhum arquivo enviado.";
                            }
                                    
                            
                            
                            
                            // Inserção dos dados no banco de dados
                            $sql = "INSERT INTO mensagens (nome, telefone, email, mensagem, arquivo) VALUES (?,?,?,?,?)";
                            $stmt = $conn ->prepare($sql);
                            $stmt -> bind_param("sssss", $nome, $telefone, $email, $mensagem, $arquivo);
                            
                            
                            if ($stmt->execute()) {
                                // Dados inseridos no banco de dados com sucesso
                            
                                // Envio de email
                                $to = "contato@webemconstrucao.com.br";
                                $subject = "Nova mensagem de contato de $nome";
                                $message = "Nome: $nome\nTelefone: $telefone\nEmail: $email\nMensagem: $mensagem";
                            
                                // Função mail() para enviar o email
                                $headers = "From: $email"; // Endereço de email do remetente
                                $attachment = chunk_split(base64_encode(file_get_contents($arquivo))); // Anexa o arquivo
                            
                                $boundary = md5(date('r', time())); // Cria um limite para o email
                            
                                $headers .= "\nMIME-Version: 1.0\n";
                                $headers .= "Content-Type: multipart/mixed; boundary=\"_1_$boundary\"";
                                
                                
                                $message = "Nome: $nome \n Telefone: $telefone \n Email do Remetente: $email \n $mensagem";
                                
                            
                                if (mail($to, $subject, $message, $headers)) {
                                    echo "Dados salvos no banco de dados e email enviado com sucesso.";
                                } else {
                                    echo "Erro ao enviar o email.";
                                }
                            } else {
                                echo "Erro ao inserir dados no banco de dados: " . $conn->error;
                            }
                        } else {
                            echo "Erro com o captcha.";
                        }
				        
				        
				        
				    } else {
				        echo "Erro. Marque a caixinha de verificação.";
				    }
}


$conn->close();
?>
