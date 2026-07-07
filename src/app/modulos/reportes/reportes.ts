import { Component, OnInit } from '@angular/core';
import { Reportes as ReportesService } from '../../servicios/reportes';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; // Necesario para *ngFor

@Component({
  selector: 'app-reportes',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './reportes.html',
  styleUrls: ['./reportes.css'],
})
export class Reporta implements OnInit {
  listaReportes: any[] = [];

  // Agregamos 'id' al objeto para poder editar
  nuevoReporte: any = { id: null, titulo: '', descripcion: '', fecha: '', usuario_id: 1 };

  constructor(private reportesService: ReportesService) {}

  ngOnInit(): void {
    this.obtenerReportes();
  }

  obtenerReportes() {
    this.reportesService.consulta().subscribe((datos: any) => {
      this.listaReportes = datos;
    });
  }

  crearReporte() {
    this.reportesService.insertar(this.nuevoReporte).subscribe(() => {
      this.obtenerReportes();
      this.limpiarFormulario();
    });
  }

  // ✏️ Carga los datos del reporte seleccionado en el formulario
  seleccionarReporte(reporte: any) {
    this.nuevoReporte = { ...reporte };
  }

  // 💾 Actualiza un reporte existente
  actualizarReporte() {
    this.reportesService.editar(this.nuevoReporte.id, this.nuevoReporte).subscribe(() => {
      this.obtenerReportes();
      this.limpiarFormulario();
    });
  }

  // 🗑️ Elimina un reporte
  borrarReporte(id: number) {
    if (confirm('¿Eliminar este reporte permanentemente?')) {
      this.reportesService.eliminar(id).subscribe(() => {
        this.obtenerReportes();
      });
    }
  }

  limpiarFormulario() {
    this.nuevoReporte = { id: null, titulo: '', descripcion: '', fecha: '', usuario_id: 1 };
  }
}
