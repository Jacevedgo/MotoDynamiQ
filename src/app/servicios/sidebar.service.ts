import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root', // 🌍 Esto permite que cualquier componente de Angular pueda usarlo
})
export class SidebarService {
  // 🔓 Estado inicial: false significa que la barra lateral inicia visible
  isSidebarHidden: boolean = false;

  // 🔄 Esta función actúa como un interruptor (on/off)
  toggleSidebar() {
    this.isSidebarHidden = !this.isSidebarHidden;
  }
}
