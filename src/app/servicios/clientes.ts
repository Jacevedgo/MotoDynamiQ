import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class Cliente {
  // url = 'http://localhost/motodynamiq/backend/controladores/clientes.php';
  url = 'https://motodynamiq.store/backend/controladores/clientes.php';

  constructor(private http: HttpClient) {}

  // Consultar todos los clientes en la base de datos
  consulta() {
    return this.http.get(`${this.url}?control=consulta`);
  }

  // 🔍 NUEVO MÉTODO: Buscar clientes por coincidencia en el nombre
  filtrar(dato: string) {
    return this.http.get(`${this.url}?control=filtro&dato=${dato}`);
  }

  // Insertar un nuevo cliente desde el formulario reactivo
  insertar(params: any) {
    return this.http.post(`${this.url}?control=insertar`, JSON.stringify(params));
  }

  // Editar los datos de un cliente pasando el ID en la URL y los datos en el cuerpo
  editar(id: number, params: any) {
    return this.http.post(`${this.url}?control=editar&id=${id}`, JSON.stringify(params));
  }

  // Eliminar físicamente un cliente mediante su ID único
  // En servicios/clientes.ts
  eliminar(id: number) {
    const urlFinal = `${this.url}?control=eliminar&id=${id}`;
    console.log('URL de petición generada:', urlFinal); // 👈 DEBE salir en consola al borrar
    return this.http.get(urlFinal);
  }
}
