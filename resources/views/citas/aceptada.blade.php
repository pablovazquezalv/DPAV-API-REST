
<div>

    <h1>¡Cita Aceptada!</h1>
    <h1>Hola {{$cita->nombre}} {{$cita->apellido}}</h1>

    <p>¡Tu cita ha sido aceptada!</p>

    <p>La cita se llevará a cabo el día {{$cita->fecha}} a las {{$cita->hora}}.</p>

    <p>Por favor, asegúrate de llegar a tiempo.</p>
    <p> Nos pondremos en contacto contigo si hay algún cambio en la cita.</p>
    <p> al telefono: {{$cita->telefono}}</p>
    <p> al correo: {{$cita->email}}</p>
    <p>¡Gracias por confiar en nosotros!</p>

    <p>Saludos cordiales,</p>
</div>