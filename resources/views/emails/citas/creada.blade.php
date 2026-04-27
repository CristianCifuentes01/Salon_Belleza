<x-mail::message>
# Confirmación de Reserva

Hola {{ $cita->usuario->nombre }},

Hemos recibido tu solicitud de reserva en **AppSalon**. Los detalles de tu cita están a continuación:

- **Fecha:** {{ $cita->fecha->format('d/m/Y') }}
- **Hora:** {{ $cita->hora }}
- **Servicios:** {{ $cita->servicios->pluck('nombre')->implode(', ') }}
- **Total:** {{ $cita->total_formateado }}
- **Estado:** {{ ucfirst($cita->estado) }}

<x-mail::button :url="route('citas.show', $cita)">
Ver Cita
</x-mail::button>

Si necesitas cancelar o reprogramar, por favor contáctanos lo antes posible.

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
