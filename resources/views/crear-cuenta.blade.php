<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cuenta creada!</title>
</head>
<body>
    <h1>Cuenta creada!</h1>
    <p>Hola {{$user->name}},Tu correo es valido, {{$user->email}}</p>
    <p>Para activar la cuenta, por favor haga click en el siguiente enlace:</p>
    <button type="button" class="btn btn-primary">
        <a href="{{$url}}">
            
        Activar cuenta</a></button>
        <p>{{$url}}</p>
    <p>Si no ha solicitado la creación de la cuenta, por favor ignore este mensaje.</p>
    <p>Gracias!</p>
    
</body>
</html>