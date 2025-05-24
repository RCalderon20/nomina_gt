<?php
require_once '../libs/fpdf.php';

class GeneradorPDF extends FPDF {
    public function generarNominaPDF($datos) {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Detalle de Nomina', 0, 1, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', '', 12);

        $campos = [
            'ID Empleado' => $datos['id_empleado'],
            'Nombre' => $datos['nombre_empleado'] . ' ' . $datos['apellido_empleado'],
            'Departamento' => $datos['departamento'],
            'Tipo de Pago' => ucfirst($datos['tipo_pago']),
            'Salario Base' => 'Q. ' . number_format($datos['salario_base'], 2),
            'Horas Extra' => $datos['horas_extra'],
            'Valor Horas Extra' => 'Q. ' . number_format($datos['valor_horas_extra'], 2),
            'Comisiones' => 'Q. ' . number_format($datos['comisiones'], 2),
            'Bonificación' => 'Q. ' . number_format($datos['bonificacion'], 2),
            'IGSS' => 'Q. ' . number_format($datos['igss'], 2),
            'ISR' => 'Q. ' . number_format($datos['isr'], 2),
            'Total Devengado' => 'Q. ' . number_format($datos['total_devengado'], 2),
            'Total Descuentos' => 'Q. ' . number_format($datos['total_descuentos'], 2),
            'Total Líquido' => 'Q. ' . number_format($datos['total_liquido'], 2),
            'Descripción del Préstamo' => $datos['descripcion_prestamo'] ?? 'N/A'
        ];

        foreach ($campos as $etiqueta => $valor) {
            $this->Cell(70, 10, $etiqueta, 0);
            $this->Cell(0, 10, $valor, 0, 1);
        }

        // Descargar automáticamente
        $this->Output('D', 'nomina_empleado_' . $datos['id_empleado'] . '.pdf');
    }
}
