import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { Nav } from './estructura/nav/nav';
import { Header } from './estructura/header/header';
import { SidebarService } from './servicios/sidebar.service'; // ❌ Esto da error porque ahí no está

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, Nav, Header], // 🟢 Elimina los errores de las etiquetas HTML <app-nav> y <app-header>
  templateUrl: './app.html',
  styleUrl: './app.css',
})
export class App {
  // 🟢 IMPORTANTE: "public" es obligatorio para que app.html reconozca 'sidebarService' sin errores
  constructor(public sidebarService: SidebarService) {}
}
