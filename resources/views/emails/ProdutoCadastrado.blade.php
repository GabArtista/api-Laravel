<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MundPay</title>
</head>

<body>
    <p>Prezado(a) Colaborador {{$user->name}},</p>

    <p>Registramos um novo cadastro de produto na sua conta!</p>

    <p>Detalhes do produto:</p>
    <p>Nome: {{$request->name}}</p>
    <p>Detalhes: {{$request->description}}</p>
    <p>Preço: {{$request->price}}</p>
</body>

</html>