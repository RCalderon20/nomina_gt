<?php 
require_once '../modelo/Nomina.php';
require_once '../libs/fpdf.php';

class NominaControlador {
    public $modelo;

    public function __construct() {
        $this->modelo = new Nomina();
    }

    public function procesarNomina($id_empleado, $horas_extra = 0, $comisiones = 0, $prestamo = 0, $descripcion_prestamo = '') {
        $validacion = $this->validarDatosEmpleado($id_empleado);
        if (isset($validacion['error'])) return $validacion;

        $empleado = $validacion['empleado'];
        $salario_base = (float)$empleado['salario_base'];
        $tipo_pago = trim(strtolower($empleado['tipo_pago']));

        $horas_extra = max(0, (float)$horas_extra);
        $comisiones = max(0, (float)$comisiones);
        $prestamo = max(0, (float)$prestamo);

        $valor_horas_extra = $this->calcularValorHorasExtra($salario_base, $horas_extra, $tipo_pago);
        $igss = $this->calcularIGSS($salario_base);
        $isr = $this->calcularISR($salario_base, $tipo_pago);
        $bonificacion = 250.00;

        $total_devengado = $salario_base + $valor_horas_extra + $comisiones + $bonificacion;
        $total_descuentos = $igss + $isr + $prestamo;
        $total_liquido = $total_devengado - $total_descuentos;

        $datos_nomina = [
            'id_empleado' => $id_empleado,
            'nombre_empleado' => $empleado['nombre'],
            'apellido_empleado' => $empleado['apellido'],
            'departamento' => $empleado['departamento'],
            'tipo_pago' => $tipo_pago,
            'salario_base' => $salario_base,
            'horas_extra' => $horas_extra,
            'valor_horas_extra' => $valor_horas_extra,
            'comisiones' => $comisiones,
            'bonificacion' => $bonificacion,
            'igss' => $igss,
            'isr' => $isr,
            'total_devengado' => $total_devengado,
            'total_descuentos' => $total_descuentos,
            'total_liquido' => $total_liquido,
        ];

        if ($this->modelo->guardarNomina($datos_nomina)) {
            $id_nomina = $this->modelo->obtenerUltimoIdNomina();
            if ($prestamo > 0) {
                $this->modelo->guardarPrestamo($id_nomina, $prestamo, $descripcion_prestamo);
            }
            $pdf = new FPDF();
            $pdf->AddPage();

            $pdf->SetFont('Arial', 'B', 20);
            $pdf->SetTextColor(0, 51, 102);
            $pdf->Cell(0, 15, 'Recibo de Nomina', 0, 1, 'C');
            $pdf->Ln(3);


            $pdf->SetDrawColor(0, 51, 102);
            $pdf->SetLineWidth(0.8);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
            $pdf->Ln(8);

            //  Datos empleado
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 10, 'Datos del Empleado', 0, 1, 'L');
            $pdf->Ln(2);

            $pdf->SetFont('Arial', '', 12);
            $fill = false;
            $datos_empleado = [
                'Empleado:' => $empleado['nombre'] . ' ' . $empleado['apellido'],
                'Departamento:' => $empleado['departamento'],
                'Tipo de pago:' => ucfirst($tipo_pago)
            ];
                foreach ($datos_empleado as $label => $value) {
                    $pdf->SetFillColor($fill ? 240 : 255, $fill ? 240 : 255, $fill ? 240 : 255);
                    $pdf->Cell(50, 10, $label, 1, 0, 'L', true);
                    $pdf->Cell(0, 10, $value, 1, 1, 'L', true);
                    $fill = !$fill;
                }

                $pdf->Ln(8);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(0, 10, 'Detalles de la Nomina', 0, 1, 'L');
                $pdf->Ln(2);

                $pdf->SetFont('Arial', '', 12);
                $fill = false;
                $datos_nomina = [
                    'Salario Base:' => 'Q' . number_format($salario_base, 2),
                    'Horas Extra:' => $horas_extra . ' hrs (Q' . number_format($valor_horas_extra, 2) . ')',
                    'Comisiones:' => 'Q' . number_format($comisiones, 2),
                    'Bonificacion Incentivo:' => 'Q' . number_format($bonificacion, 2),
                    'IGSS:' => 'Q' . number_format($igss, 2),
                    'ISR:' => 'Q' . number_format($isr, 2),
                    'Prestamo:' => 'Q' . number_format($prestamo, 2),
                ];
                foreach ($datos_nomina as $label => $value) {
                    $pdf->SetFillColor($fill ? 250 : 255, $fill ? 250 : 255, $fill ? 250 : 255);
                    $pdf->Cell(70, 10, $label, 1, 0, 'L', true);
                    $pdf->Cell(0, 10, $value, 1, 1, 'R', true);
                    $fill = !$fill;
                }

                $pdf->Ln(10);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor(0, 51, 102);
                $pdf->Cell(0, 10, 'Totales', 0, 1, 'L');
                $pdf->Ln(2);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetFillColor(200, 220, 255);

                $pdf->Cell(70, 12, 'Total Devengado:', 1, 0, 'L', true);
                $pdf->Cell(0, 12, 'Q' . number_format($total_devengado, 2), 1, 1, 'R', true);

                $pdf->Cell(70, 12, 'Total Descuentos:', 1, 0, 'L', true);
                $pdf->Cell(0, 12, 'Q' . number_format($total_descuentos, 2), 1, 1, 'R', true);

                $pdf->Cell(70, 12, 'Total Liquido a Recibir:', 1, 0, 'L', true);
                $pdf->Cell(0, 12, 'Q' . number_format($total_liquido, 2), 1, 1, 'R', true);

                $pdf->Ln(20);

                $pdf->SetFont('Arial', 'I', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(0, 8, '______________________________', 0, 1, 'C');
                $pdf->Cell(0, 6, 'Firma del Empleado', 0, 1, 'C');
                $pdf->Ln(5);
                $pdf->Cell(0, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'C');

                $pdf->Output('D', 'nomina_empleado_' . $id_empleado . '.pdf');
                exit();
        } else {
            return ['error' => 'Error al guardar la nómina en la base de datos.'];
        }
    }

    public function calcularNominaVistaPrevia($id_empleado, $horas_extra = 0, $comisiones = 0, $prestamo = 0) {
        $validacion = $this->validarDatosEmpleado($id_empleado);
        if (isset($validacion['error'])) return $validacion;

        $empleado = $validacion['empleado'];
        $salario_base = (float)$empleado['salario_base'];
        $tipo_pago = trim(strtolower($empleado['tipo_pago']));

        $horas_extra = max(0, (float)$horas_extra);
        $comisiones = max(0, (float)$comisiones);
        $prestamo = max(0, (float)$prestamo);

        $valor_horas_extra = $this->calcularValorHorasExtra($salario_base, $horas_extra, $tipo_pago);
        $igss = $this->calcularIGSS($salario_base);
        $isr = $this->calcularISR($salario_base, $tipo_pago);
        $bonificacion = 250.00;

        $total_devengado = $salario_base + $valor_horas_extra + $comisiones + $bonificacion;
        $total_descuentos = $igss + $isr + $prestamo;
        $total_liquido = $total_devengado - $total_descuentos;

        return [
            'nombre_empleado' => $empleado['nombre'],
            'apellido_empleado' => $empleado['apellido'],
            'departamento' => $empleado['departamento'],
            'tipo_pago' => $tipo_pago,
            'salario_base' => $salario_base,
            'horas_extra' => $horas_extra,
            'valor_horas_extra' => $valor_horas_extra,
            'comisiones' => $comisiones,
            'bonificacion' => $bonificacion,
            'igss' => $igss,
            'isr' => $isr,
            'prestamo' => $prestamo,
            'total_devengado' => $total_devengado,
            'total_descuentos' => $total_descuentos,
            'total_liquido' => $total_liquido,
        ];
    }

    private function validarDatosEmpleado($id_empleado) {
        if (!is_numeric($id_empleado) || $id_empleado <= 0) {
            return ['error' => 'ID de empleado inválido.'];
        }

        $empleado = $this->modelo->obtenerEmpleadoPorId($id_empleado);
        if (!$empleado) {
            return ['error' => 'Empleado no encontrado.'];
        }

        $tipo_pago = trim(strtolower($empleado['tipo_pago']));
        $tipos_validos = ['semanal', 'quincenal', 'mensual'];
        if (!in_array($tipo_pago, $tipos_validos)) {
            return ['error' => 'Tipo de pago inválido para el empleado.'];
        }

        return ['empleado' => $empleado];
    }

  private function calcularValorHorasExtra($salario_base, $horas_extra, $tipo_pago) {
    $salario_mensual = match ($tipo_pago) {
        'semanal' => $salario_base * 4,
        'quincenal' => $salario_base * 2,
        'mensual' => $salario_base,
        default => 0
    };

    if ($salario_mensual <= 0) return 0;
    $valor_hora = ($salario_mensual / 30) / 8;
    $valor_hora_extra = $valor_hora * 1.5;
    return round($valor_hora_extra * $horas_extra, 2);
}

    private function calcularIGSS($salario_base) {
        return round($salario_base * 0.0483, 2);
    }

    private function calcularISR($salario_base, $tipo_pago) {
        $bonificacion = 250.00; 
        $total_estimado = $salario_base + $bonificacion;
        $monto_base_exento = 4000 + ($salario_base * 0.0483);
        $diferencia = max(0, $total_estimado - $monto_base_exento);
        $isr_anual = $diferencia * 0.05;
        $divisor = match ($tipo_pago) {
            'semanal' => 4,
            'quincenal' => 2,
            'mensual' => 1,
            default => 1
        };
        
        return round($isr_anual / $divisor, 2);
    }

}
