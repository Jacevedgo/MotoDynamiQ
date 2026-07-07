import { Component, OnInit } from '@angular/core';
import { ProveedoresService as ProveedoresService } from '../../servicios/proveedores';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; // 👈 Importante para el *ngFor y *ngIf

@Component({
  selector: 'app-proveedores',
  standalone: true, // Asegúrate de que sea standalone si usas Angular 17+
  imports: [FormsModule, CommonModule],
  templateUrl: './proveedores.html',
  styleUrls: ['./proveedores.css'],
})
export class Proveedor implements OnInit {
  listaProveedores: any[] = [];

  // Incluimos 'id' para que la lógica de edición funcione igual que en Motocicletas
  nuevoProveedor: any = { id: null, nombre: '', telefono: '', email: '', direccion: '' };

  constructor(private proveedoresService: ProveedoresService) {}

  ngOnInit(): void {
    this.obtenerProveedores();
  }

  obtenerProveedores() {
    this.proveedoresService.consulta().subscribe((datos: any) => {
      this.listaProveedores = datos;
    });
  }

  crearProveedor() {
    this.proveedoresService.insertar(this.nuevoProveedor).subscribe(() => {
      this.obtenerProveedores();
      this.limpiarFormulario();
    });
  }

  // ✏️ Carga los datos en el formulario para editar
  seleccionarProveedor(prov: any) {
    this.nuevoProveedor = { ...prov };
  }

  // 💾 Ejecuta la actualización
  actualizarProveedor(id: number) {
    this.proveedoresService.editar(id, this.nuevoProveedor).subscribe(() => {
      this.obtenerProveedores();
      this.limpiarFormulario();
    });
  }

  // 🗑️ Elimina el registro
  borrarProveedor(id: number) {
    if (confirm('¿Estás seguro de eliminar este proveedor?')) {
      this.proveedoresService.eliminar(id).subscribe((res: any) => {
        alert(res.Mensaje);
        this.obtenerProveedores();
      });
    }
  }

  limpiarFormulario() {
    this.nuevoProveedor = { id: null, nombre: '', telefono: '', email: '', direccion: '' };
  }
}
