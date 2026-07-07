import { Component, OnInit } from '@angular/core';
import { Ventas as VentasService } from '../../servicios/ventas';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; // Asegura soporte para *ngFor

@Component({
  selector: 'app-ventas',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './ventas.html',
  styleUrls: ['./ventas.css'],
})
export class Venta implements OnInit {
  listaVentas: any[] = [];

  // 📦 Agregamos el 'id' para habilitar el modo edición
  nuevaVenta: any = { id: null, fecha: '', cliente_id: 0, usuario_id: 1, total: 0 };

  constructor(private ventasService: VentasService) {}

  ngOnInit(): void {
    this.obtenerVentas();
  }

  obtenerVentas() {
    this.ventasService.consulta().subscribe((datos: any) => {
      this.listaVentas = datos;
    });
  }

  crearVenta() {
    this.ventasService.insertar(this.nuevaVenta).subscribe(() => {
      this.obtenerVentas();
      this.limpiarFormulario();
    });
  }

  // ✏️ Carga la venta en el formulario
  seleccionarVenta(venta: any) {
    this.nuevaVenta = { ...venta };
  }

  // 💾 Envía los cambios al servidor
  actualizarVenta() {
    this.ventasService.editar(this.nuevaVenta.id, this.nuevaVenta).subscribe(() => {
      this.obtenerVentas();
      this.limpiarFormulario();
    });
  }

  // 🗑️ Elimina la venta y dispara la lógica de stock en el backend
  borrarVenta(id: number) {
    if (confirm('¿Confirmas eliminar esta venta? El inventario se actualizará automáticamente.')) {
      this.ventasService.eliminar(id).subscribe((res: any) => {
        alert(res.Mensaje);
        this.obtenerVentas();
      });
    }
  }

  limpiarFormulario() {
    this.nuevaVenta = { id: null, fecha: '', cliente_id: 0, usuario_id: 1, total: 0 };
  }
}
