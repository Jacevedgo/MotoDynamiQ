import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class Motocicletas {
  url = 'http://localhost/motodynamiq/backend/controladores/motocicletas.php';

  constructor(private http: HttpClient) {}

  // Consultar el listado completo de motocicletas
  consulta() {
    return this.http.get(`${this.url}?control=consulta`);
  }

  // 🏷️ NUEVO MÉTODO: Obtener las categorías de la BD para los menús desplegables
  obtenerCategorias() {
    return this.http.get(`${this.url}?control=categorias`);
  }

  // Insertar un nuevo registro de motocicleta
  insertar(params: any) {
    return this.http.post(`${this.url}?control=insertar`, JSON.stringify(params));
  }

  // Editar una motocicleta existente pasando el ID por URL y los datos en el cuerpo
  editar(id: number, params: any) {
    return this.http.post(`${this.url}?control=editar&id=${id}`, JSON.stringify(params));
  }

  // Eliminar una motocicleta física por su ID único
  eliminar(id: number) {
    return this.http.get(`${this.url}?control=eliminar&id=${id}`);
  }
}
