import { Routes } from '@angular/router';
import { Main } from './estructura/main';
import { Dashboard } from './modulos/dashboard/dashboard';
import { Clientes } from './modulos/clientes/clientes';
import { Inventario } from './modulos/inventario/inventario';
import { Reporta } from './modulos/reportes/reportes';
import { Venta } from './modulos/ventas/ventas';
import { Login } from './modulos/login/login';
import { Inicio } from './modulos/inicio/inicio';
import { Motocicletas } from './modulos/motocicletas/motocicletas';
import { Proveedor } from './modulos/proveedores/proveedores';
import { Usuario } from './modulos/usuarios/usuarios';
import { Compra } from './modulos/compras/compras';

const authGuard = () => !!localStorage.getItem('usuarioLogueado');

export const routes: Routes = [
  { path: 'login', component: Login }, // Ruta pública
  {
    path: '',
    component: Main,
    canActivate: [authGuard], // PROTECCIÓN: Solo entra si hay sesión
    children: [
      { path: 'dashboard', component: Dashboard },
      { path: 'clientes', component: Clientes },
      { path: 'inventario', component: Inventario },
      { path: 'motocicletas', component: Motocicletas },
      { path: 'proveedores', component: Proveedor },
      { path: 'usuarios', component: Usuario },
      { path: 'reportes', component: Reporta },
      { path: 'compras', component: Compra },
      { path: 'ventas', component: Venta },
      { path: 'inicio', component: Inicio },
      { path: '', redirectTo: 'inicio', pathMatch: 'full' },
    ],
  },
  { path: '**', redirectTo: 'login' },
];
