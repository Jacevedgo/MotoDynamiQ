import { Component, OnInit } from '@angular/core';
import { Compras as ComprasService } from '../../servicios/compras';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; // Importante para *ngFor y lógica de vista

@Component({
  selector: 'app-compras',
  standalone: true, // Si usas Angular 17+
  imports: [FormsModule, CommonModule],
  templateUrl: './compras.html',
  styleUrls: ['./compras.css'],
})
export class Compra implements OnInit {
  listaCompras: any[] = [];

  // 📦 Agregamos el 'id' para la lógica de edición
  nuevaCompra: any = { id: null, fecha: '', proveedor_id: 0, usuario_id: 1, total: 0 };

  constructor(private comprasService: ComprasService) {}

  ngOnInit(): void {
    this.obtenerCompras();
  }

  obtenerCompras() {
    this.comprasService.consulta().subscribe((datos: any) => {
      this.listaCompras = datos;
    });
  }

  crearCompra() {
    this.comprasService.insertar(this.nuevaCompra).subscribe(() => {
      this.obtenerCompras();
      this.limpiarFormulario();
    });
  }

  // ✏️ Prepara el formulario para editar
  seleccionarCompra(compra: any) {
    this.nuevaCompra = { ...compra };
  }

  // 💾 Actualiza el encabezado de la compra
  actualizarCompra() {
    this.comprasService.editar(this.nuevaCompra.id, this.nuevaCompra).subscribe(() => {
      this.obtenerCompras();
      this.limpiarFormulario();
    });
  }

  // 🗑️ Elimina la compra (Recuerda que en el backend esta función debe manejar el ajuste de stock)
  borrarCompra(id: number) {
    if (confirm('¿Estás seguro de eliminar esta compra? Esta acción revertirá el stock.')) {
      this.comprasService.eliminar(id).subscribe((res: any) => {
        alert(res.Mensaje);
        this.obtenerCompras();
      });
    }
  }

  limpiarFormulario() {
    this.nuevaCompra = { id: null, fecha: '', proveedor_id: 0, usuario_id: 1, total: 0 };
  }
}
