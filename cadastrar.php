<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastrar.css">
    <title>Cadastro</title>
</head>

<body>
    <div class="logo-container">
        <img src="../../imgIndex/bbbyynew.ico" alt="Logo" class="logo">
    </div>
    <form id="formUsuario" method="POST" action="backend/cadastrarUsuario.php" enctype ="multipart/form-data">
        <p>Bem-vindo à BabáBaby </p>
        <div id="escolhaPerfil">
            <fieldset>
                <legend>Tipo de Perfil</legend>
                <label for="paiRadio">
                    <span>Sou pai ou responsável</span>
                    <input type="radio" id="paiRadio" name="userType" value="pai" required>
                </label>
                <label for="babaRadio">
                    <span>Sou um(a) Babá</span>
                    <input type="radio" id="babaRadio" name="userType" value="baba" required>
                </label>
            </fieldset>
        </div>
        <div id="camposEmComum">
            <fieldset>
                <legend>Dados Cadastrais</legend>
                <label>
                    <strong>Nome:</strong>
                    <input required class="required" name="name" id="name" type="text" placeholder="Nome"
                        oninput="nameValidate()">
                        <span class="span-required"> Digite um nome com no mínimo 3 letras </span>

                </label>
                <label><strong>Sobrenome:</strong>
                    <input name="sobrenome" id="sobrenome" type="text" placeholder="Sobrenome" required>
                </label>
                <label id="dt">
                    <strong>Data de Nascimento:</strong>
                    <input class="required" type="date" id="data" name="dtaNascimento" oninput="validarData()" required />
                    <span class="span-required"> Digite o seu ano real de nascimento.</span>


                </label>
                <label>
                    <strong>CPF:</strong>
                    <input type="text" id="cpf" name="cpf" pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}"
                        title="Formato: XXX.XXX.XXX-XX" required />
                </label>
                <label>
                    <strong>Telefone:</strong>
                    <input type="text" id="tel" name="telefone" pattern="\d{2}\s?\d{4,5}-?\d{4}"
                        title="Formato: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX." required />
                </label>
                <label><strong>Cidade:</strong>
                    <input name="cidade" id="cidade" type="text" placeholder="Cidade" required>
                </label>
                <label><strong>Endereço:</strong>
                    <input name="endereco" id="endereco" type="text" placeholder="Endereço" required>
                </label>
                <label><strong>Foto:</strong>
                    <input name="foto" id="foto" type="file" required>
                    <span>Formato permitido: PNG</span>
                </label>
                <label><strong>Email:</strong>
                    <input class="required" id="email" type="email" name="email" placeholder="Email"
                        title="Email entre 10 e 50 letras, deve conter @." required oninput="emailValidate()" />
                        <span class="span-required">Digite um e-mail válido.</span>
                </label>
                <label>
                    <!--pattern="^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[^\da-zA-Z]).{8,16}$" regra da senha, por algum motivo estava dando problema-->
                    <strong>Senha: </strong>
                    <input type="password" id="senha" name="senha"
                         required
                        title="A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número, um caractere especial e ter entre 8 e 16 caracteres" />
                </label>
            </fieldset>
        </div>
        <div class="hidden" id="formPai">
            <fieldset>
                <legend>Dados Adicionais</legend>
                <div>
                    <label for="qnt-crianca">Quantidade de criança(s)</label>
                    <input type="text" placeholder="1" name="qtdeCrianca" pattern="[0-9]+"
                        title="Insira apenas números." />
                </div>
                <div>
                    <label for="descricao-familia">Fale sobre família</label>
                    <input type="text" placeholder="Somos descontraídos e adoramos jogos" name="descricao"
                        title="Detalhe um pouco sobre como é sua família." />
                </div>
                <div>
                </div>
            </fieldset>
        </div>
        <div class="hidden" id="formBaba">
            <fieldset>
                <legend>Dados Adicionais</legend>
                <div>
                    <label for="temp-exp">Trabalho como Baba desde </label>
                    <input type="text" name="tempoExp" pattern="[0-9]{4}"
                        title="Por favor, insira um ano válido (quatro dígitos)" placeholder="2005" />
                </div>
                <div>
                    <label for="referencia">Referência (contato)</label>
                    <input type="text" name="ref" placeholder="(XX) XXXXX-XXXX"
                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}|^\d{10,}$"
                        title="Por favor, insira um email ou um número de telefone válido para contato (min. 10 dígitos)." />
                </div>
                <div>
                    <label for="sobre">Sobre</label>
                    <input type="text" placeholder="Fale um pouco sobre você" name="sobre"
                        title="Descreva um pouco sobre você e suas experiências." />
                </div>
                <div>
                    <label for="preferencias-baba">Preferência em cuidade de</label>
                    <select name="fk_idFxEtaria">
                        <option value="1">Bebê</option>
                        <option value="2">Criança</option>
                        <option value="3">Infantojuvenil</option>
                        <option value="4">Adolescente</option>
                    </select>
                </div>
                <div>
                    <label for="valor">Valor</label>
                    <input type="text" placeholder="150,00" name="valor" pattern="\d+(\.\d+)?"
                        title="Insira um valor em reais (com ponto ao invés de vírgula!)" />
                </div>
                <?php require "componente/diasSemana.php"; ?>
                <?php require "componente/horarios.php"; ?>
            </fieldset>
        </div>
        <input type="submit" name="cadastrar" value="Cadastrar">
        <a href="/baba-baby2/">Cancelar</a>
    </form>
    <script src="js/cadastrar.js"></script>
    
</body>

</html>
