import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common'; // 👈 Necesario para *ngFor y *ngIf
import { FormsModule } from '@angular/forms'; // 👈 Necesario para el enlace bidireccional [(ngModel)]
import { Cliente } from '../../servicios/clientes';

@Component({
  selector: 'app-clientes',
  standalone: true, // 👈 Aseguramos la arquitectura Standalone del ecosistema MotoDynamiQ
  imports: [CommonModule, FormsModule], // 👈 Registramos los módulos para habilitar la vista premium
  templateUrl: './clientes.html',
  styleUrls: ['./clientes.css'],
})
export class Clientes implements OnInit {
  listaClientes: any[] = [];

  // 🔍 Almacena la cadena de texto escrita en el input de búsqueda
  textoBusqueda: string = '';

  // 📦 Estructura reactiva acoplada 1:1 con las columnas de tu tabla en la BD
  nuevoCliente = {
    nombre: '',
    telefono: '',
    email: '',
    direccion: '',
  };

  // 🔄 NUEVO: Variables de control de estado para la Ventana Modal de Edición
  modalAbierta: boolean = false;
  clienteSeleccionado: any = {
    id: 0,
    nombre: '',
    telefono: '',
    email: '',
    direccion: '',
  };

  constructor(private clienteService: Cliente) {}

  ngOnInit(): void {
    this.obtenerClientes();
  }

  // Consulta el listado completo original de clientes
  obtenerClientes() {
    this.clienteService.consulta().subscribe({
      next: (datos: any) => {
        this.listaClientes = datos;
        console.log('Clientes cargados con éxito:', this.listaClientes);
      },
      error: (error) => {
        console.error('Error al obtener clientes:', error);
      },
    });
  }

  // 🔍 Filtra los clientes en tiempo real conforme el usuario escribe
  buscarCliente() {
    if (this.textoBusqueda.trim() !== '') {
      this.clienteService.filtrar(this.textoBusqueda).subscribe({
        next: (datos: any) => {
          this.listaClientes = datos;
        },
        error: (error) => console.error('Error en el filtrado:', error),
      });
    } else {
      this.obtenerClientes(); // Si se borra el buscador, regresa la lista completa
    }
  }

  // Envía el objeto reactivo completo para realizar la inserción masiva en la BD
  registrarCliente() {
    if (!this.nuevoCliente.nombre.trim()) {
      alert('⚠️ El nombre del cliente es obligatorio para el registro.');
      return;
    }

    this.clienteService.insertar(this.nuevoCliente).subscribe({
      next: (resultado: any) => {
        if (resultado.Resultado === 'OK') {
          this.obtenerClientes(); // 🔄 Recarga automática e instantánea de la grilla oscura

          // 🧹 Limpieza perfecta de los campos para dejar el formulario listo
          this.nuevoCliente = {
            nombre: '',
            telefono: '',
            email: '',
            direccion: '',
          };
        } else {
          alert('Error en el servidor: ' + resultado.Mensaje);
        }
      },
      error: (error) => console.error('Error al registrar cliente:', error),
    });
  }

  // Elimina físicamente un registro controlando la integridad referencial
  borrarCliente(id: number) {
    if (window.confirm('¿Está seguro de eliminar este cliente?')) {
      this.clienteService.eliminar(id).subscribe({
        next: (res: any) => {
          // Aquí leemos la respuesta estructurada que enviamos desde el modelo
          if (res.Resultado === 'OK') {
            alert('✅ ' + res.Mensaje);
            this.obtenerClientes();
          } else {
            // Si el servidor dijo ERROR, mostramos el mensaje amigable del catch
            alert('🚫 ' + res.Mensaje);
          }
        },
        error: (err) => {
          console.error('Error crítico:', err);
          alert('❌ Ocurrió un error inesperado al intentar eliminar.');
        },
      });
    }
  }

  // ==========================================================================
  // ⚡ NUEVOS MÉTODOS: CONTROL DE LA VENTANA MODAL (EDITAR)
  // ==========================================================================

  // 🔓 Abre la modal y monta una copia de respaldo del cliente seleccionado
  abrirEditar(cliente: any) {
    this.clienteSeleccionado = { ...cliente }; // Rompe la referencia en vivo para no alterar la grilla antes de guardar
    this.modalAbierta = true;
  }

  // 🔒 Cierra la ventana flotante
  cerrarModal() {
    this.modalAbierta = false;
  }

  // 💾 Despacha la actualización final al servidor PHP mediante el servicio
  guardarEdicion() {
    if (!this.clienteSeleccionado.nombre.trim()) {
      alert('⚠️ El nombre del cliente no puede guardarse en blanco.');
      return;
    }

    const id = this.clienteSeleccionado.id;
    this.clienteService.editar(id, this.clienteSeleccionado).subscribe({
      next: (resultado: any) => {
        if (resultado.Resultado === 'OK') {
          this.obtenerClientes(); // Refresca los cambios asíncronamente en segundo plano
          this.cerrarModal(); // Oculta la modal de manera exitosa
        } else {
          alert('Error al actualizar datos: ' + resultado.Mensaje);
        }
      },
      error: (error) => console.error('Error al procesar la edición:', error),
    });
  }
}
