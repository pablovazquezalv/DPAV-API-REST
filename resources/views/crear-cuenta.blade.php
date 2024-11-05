<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cuenta creada!</title>
    <style>
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            display: inline-block;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Cuenta creada!</h1>
    <p>Hola {{$user->name}}, Tu correo es válido: {{$user->email}}</p>
    <p>Para activar la cuenta, por favor haga clic en el siguiente botón:</p>
    <a href="{{$url}}" class="btn-primary">Activar cuenta</a>
       
    <p>Si no ha solicitado la creación de la cuenta, por favor ignore este mensaje.</p>
    <p>Gracias!</p>
</body>
</html>
