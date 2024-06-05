<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <h1>Olvidé mi contraseña</h1>
    <p>Para restablecer tu contraseña, por favor haga click en el siguiente enlace:</p>
    <button type="button" class="btn btn-primary">
        <a href="{{$url}}">
            
        Restablecer contraseña</a></button>
        <p>{{$url}}</p>
    <p>Si no ha solicitado restablecer la contraseña, por favor ignore este mensaje.</p>
    <p>Gracias!</p>
</body>
</html>