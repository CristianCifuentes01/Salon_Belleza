<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\SalonBloqueo;
use App\Models\SalonHorario;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DisponibilidadService
{
    private const INTERVALO_MINUTOS = 30;

    public function horasDisponibles(string $fecha, array $servicioIds = [], ?int $ignorarCitaId = null): array
    {
        $duracion = $this->duracionServicios($servicioIds);
        $horario = $this->horarioParaFecha($fecha);

        if (!$horario || !$horario->activo || !$horario->hora_apertura || !$horario->hora_cierre || $this->diaCompletoBloqueado($fecha)) {
            return [];
        }

        $inicio = Carbon::parse($fecha . ' ' . $horario->hora_apertura);
        $cierre = Carbon::parse($fecha . ' ' . $horario->hora_cierre);
        $horas = [];

        while ($inicio->copy()->addMinutes($duracion)->lte($cierre)) {
            $hora = $inicio->format('H:i');

            if ($fecha !== now()->toDateString() || $hora > now()->format('H:i')) {
                if ($this->estaDisponible($fecha, $hora, $servicioIds, $ignorarCitaId)) {
                    $horas[] = $hora;
                }
            }

            $inicio->addMinutes(self::INTERVALO_MINUTOS);
        }

        return $horas;
    }

    public function estaDisponible(string $fecha, string $hora, array $servicioIds = [], ?int $ignorarCitaId = null): bool
    {
        $duracion = $this->duracionServicios($servicioIds);
        $inicio = Carbon::parse($fecha . ' ' . $hora);
        $fin = $inicio->copy()->addMinutes($duracion);
        $horario = $this->horarioParaFecha($fecha);

        if (!$horario || !$horario->activo || !$horario->hora_apertura || !$horario->hora_cierre) {
            return false;
        }

        $apertura = Carbon::parse($fecha . ' ' . $horario->hora_apertura);
        $cierre = Carbon::parse($fecha . ' ' . $horario->hora_cierre);

        if ($inicio->lt($apertura) || $fin->gt($cierre) || $this->diaCompletoBloqueado($fecha)) {
            return false;
        }

        if ($this->cruzaBloqueos($fecha, $inicio, $fin)) {
            return false;
        }

        return !$this->citasActivas($fecha, $ignorarCitaId)->contains(function (Cita $cita) use ($inicio, $fin) {
            $citaInicio = Carbon::parse($cita->fecha->format('Y-m-d') . ' ' . $cita->hora);
            $citaFin = $citaInicio->copy()->addMinutes(max(30, (int) $cita->servicios->sum('duracion')));

            return $inicio->lt($citaFin) && $fin->gt($citaInicio);
        });
    }

    private function duracionServicios(array $servicioIds): int
    {
        if (empty($servicioIds)) {
            return self::INTERVALO_MINUTOS;
        }

        return max(self::INTERVALO_MINUTOS, (int) Servicio::whereIn('id', $servicioIds)->sum('duracion'));
    }

    private function horarioParaFecha(string $fecha): ?SalonHorario
    {
        return SalonHorario::where('dia_semana', Carbon::parse($fecha)->dayOfWeekIso)->first();
    }

    private function diaCompletoBloqueado(string $fecha): bool
    {
        return SalonBloqueo::whereDate('fecha', $fecha)
            ->whereNull('hora_inicio')
            ->whereNull('hora_fin')
            ->exists();
    }

    private function cruzaBloqueos(string $fecha, Carbon $inicio, Carbon $fin): bool
    {
        return SalonBloqueo::whereDate('fecha', $fecha)
            ->whereNotNull('hora_inicio')
            ->whereNotNull('hora_fin')
            ->get()
            ->contains(function (SalonBloqueo $bloqueo) use ($fecha, $inicio, $fin) {
                $bloqueoInicio = Carbon::parse($fecha . ' ' . $bloqueo->hora_inicio);
                $bloqueoFin = Carbon::parse($fecha . ' ' . $bloqueo->hora_fin);

                return $inicio->lt($bloqueoFin) && $fin->gt($bloqueoInicio);
            });
    }

    private function citasActivas(string $fecha, ?int $ignorarCitaId): Collection
    {
        return Cita::whereDate('fecha', $fecha)
            ->when($ignorarCitaId, fn ($query) => $query->where('id', '!=', $ignorarCitaId))
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->with('servicios')
            ->get();
    }
}
