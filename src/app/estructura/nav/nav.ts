import { Component } from '@angular/core';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-nav',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './nav.html',
  styleUrl: './nav.css',
})
export class Nav {
  // 🔘 Interruptor: false significa que la barra está visible al iniciar
  isSidebarHidden: boolean = false;

  // 🔄 Inyectamos Router para poder redirigir al cerrar sesión
  constructor(private router: Router) {}

  // 🔄 Función para cambiar el estado (de visible a oculto, o viceversa)
  toggleSidebar() {
    this.isSidebarHidden = !this.isSidebarHidden;
  }

  // 🚪 Función para cerrar sesión
  cerrarSesion() {
    if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
      localStorage.removeItem('usuarioLogueado');
      this.router.navigate(['/login']);
    }
  }
}
