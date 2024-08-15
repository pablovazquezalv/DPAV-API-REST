<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Microchip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .certificate-container {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            width: 800px;
            height: 450px;
            position: relative;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #3366cc;
            margin-bottom: 30px;
        }

        .dog-info, .owner-info {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }

        .dog-info {
            margin-right: 5%;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            font-size: 100px;
            color: gray;
            font-weight: bold;
            z-index: -1;
        }

        .dog-image {
            position: absolute;
            top: 50%;
            left: 25%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            z-index: -1;
        }

        .dog-image img {
            width: 150px;
            height: auto;
        }

    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="title">Certificado de Microchip</div>

        <div class="dog-info">
            <span class="info-label">Nombre del ejemplar:{{$perro->perro_nombre}}  </span>
            <span class="info-label">Raza: 
                {{$perro->raza}}
            </span>
            <span class="info-label">Distintivo:
                @if($perro->distintivo == null)
                    Sin Distintivo
                @else
                    {{$perro->distintivo}}
                @endif

            </span>
            <span class="info-label">Sexo:
                {{$perro->perro_sexo}}
            </span>
            <span class="info-label">Peso:
                {{$perro->perro_peso}} kg
            <span class="info-label">Fecha de Nacimiento:
                {{$perro->fecha_nacimiento}}
            </span>
            <span class="info-label">Microchip:
                {{$perro->perro_chip}}
            </span>
        </div>

        <div class="owner-info">
            <span class="info-label">Propietario:
                {{$perro->nombre}}
                
            </span>
            <span class="info-label">Dirección:
                @if($perro->direccion == null)
                    Sin Dirección
                @else
                    {{$perro->direccion}}
                @endif
            </span>
            <span class="info-label">
                
                
                Código Postal:
                @if($perro->codigo_postal == null)
                    Sin Código Postal
                @else
                    {{$perro->codigo_postal}}
                @endif

            </span>
            <span class="info-label">Estado:
                @if($perro->estado == null)
                    Sin Estado
                @else
                    {{$perro->estado}}
                @endif



            </span>
            <span class="info-label">Colonia:
                @if($perro->colonia == null)
                    Sin Colonia
                @else
                    {{$perro->colonia}}
                @endif


            </span>
            <span class="info-label">Teléfono:
               
                @if($perro->telefono == null)
                    Sin Teléfono
                @else
                    {{$perro->telefono}}
                @endif

            </span>
            <span class="info-label">Correo:
                @if($perro->correo == null)
                    Sin Correo
                @else
                    {{$perro->correo}}
                @endif


            </span>
        </div>

        <div class="watermark">DPAV</div>

        <div class="dog-image">
            <img src="https://dpav.shop/assets/img/icono.png" alt="Dog">
        </div>
    </div>
</body>
</html>