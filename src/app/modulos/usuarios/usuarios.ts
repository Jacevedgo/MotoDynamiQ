import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common'; // 👈 IMPORTANTE
import { FormsModule } from '@angular/forms';
import { Usuarios } from '../../servicios/usuarios';

@Component({
  selector: 'app-usuarios',
  standalone: true,
  imports: [CommonModule, FormsModule], // 👈 AÑADIDO CommonModule
  templateUrl: './usuarios.html',
  styleUrls: ['./usuarios.css'],
})
export class Usuario implements OnInit {
  listaUsuarios: any[] = [];

  // 👈 CORREGIDO: 'identificacion' debe coincidir con la BD
  nuevoUsuario = {
    id: null,
    nombre: '',
    identificacion: '',
    password: '',
    rol: '',
  };

  constructor(private usuariosService: Usuarios) {}

  ngOnInit(): void {
    this.obtenerUsuarios();
  }

  obtenerUsuarios() {
    this.usuariosService.consulta().subscribe((datos: any) => {
      this.listaUsuarios = datos;
    });
  }

  guardarUsuario() {
    // 1. Validar campos vacíos
    if (!this.nuevoUsuario.nombre || !this.nuevoUsuario.identificacion) {
      alert('Por favor completa los campos obligatorios');
      return;
    }

    // 2. Validar duplicados en la lista actual (solo para nuevos registros)
    if (!this.nuevoUsuario.id) {
      const existe = this.listaUsuarios.find(
        (u) => u.identificacion === this.nuevoUsuario.identificacion,
      );
      if (existe) {
        alert(
          'Error: Ya existe un usuario con la identificación ' + this.nuevoUsuario.identificacion,
        );
        return;
      }
    }

    // 3. Proceder con el guardado
    if (this.nuevoUsuario.id) {
      this.usuariosService.editar(this.nuevoUsuario.id, this.nuevoUsuario).subscribe({
        next: () => {
          this.obtenerUsuarios();
          this.limpiarFormulario();
        },
        error: (e) => alert('Error al editar: ' + e.error.Mensaje),
      });
    } else {
      this.usuariosService.insertar(this.nuevoUsuario).subscribe({
        next: (res: any) => {
          if (res.Resultado === 'OK') {
            this.obtenerUsuarios();
            this.limpiarFormulario();
          } else {
            alert('Error: ' + res.Mensaje);
          }
        },
        error: (e) => alert('Error en la conexión con el servidor'),
      });
    }
  }

  limpiarFormulario() {
    this.nuevoUsuario = { id: null, nombre: '', identificacion: '', password: '', rol: '' };
  }

  seleccionarUsuario(usuario: any) {
    this.nuevoUsuario = { ...usuario };
  }

  borrarUsuario(id: number) {
    if (confirm('¿Seguro que deseas eliminar?')) {
      this.usuariosService.eliminar(id).subscribe(() => this.obtenerUsuarios());
    }
  }
}
