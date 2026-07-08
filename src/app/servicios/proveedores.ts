import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class ProveedoresService {
  // Nombre corregido
  // private url = 'http://localhost/motodynamiq/backend/controladores/proveedores.php';
  url = 'https://motodynamiq.store/backend/controladores/proveedores.php';

  constructor(private http: HttpClient) {}

  consulta() {
    return this.http.get(`${this.url}?control=consulta`);
  }

  insertar(params: any) {
    return this.http.post(`${this.url}?control=insertar`, params);
  }

  editar(id: number, params: any) {
    return this.http.post(`${this.url}?control=editar&id=${id}`, params);
  }

  eliminar(id: number) {
    return this.http.get(`${this.url}?control=eliminar&id=${id}`);
  }
}
