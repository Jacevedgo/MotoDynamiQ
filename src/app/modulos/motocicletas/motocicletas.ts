import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Motocicletas as Servicios } from '../../servicios/motocicletas';

@Component({
  selector: 'app-motocicletas',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './motocicletas.html',
  styleUrls: ['./motocicletas.css'],
})
export class Motocicletas implements OnInit {
  listaMotos: any[] = [];
  listaCategorias: any[] = [];

  nuevaMoto: any = {
    marca: '',
    modelo: '',
    fo_categoria: '',
    cilindraje: null,
    precio: null,
    stock: 0,
  };

  constructor(private motoService: Servicios) {}

  ngOnInit(): void {
    this.consultarMotos();
    this.cargarCategorias();
  }

  consultarMotos() {
    this.motoService.consulta().subscribe({
      next: (data: any) => {
        this.listaMotos = data;
      },
      error: (err) => console.error('Error al consultar motos:', err),
    });
  }

  cargarCategorias() {
    this.motoService.obtenerCategorias().subscribe({
      next: (data: any) => {
        this.listaCategorias = data;
      },
      error: (err) => console.error('Error al cargar categorías:', err),
    });
  }

  crearMotocicleta() {
    // CORRECCIÓN: Creamos una copia con valores numéricos asegurados
    const motoParaEnviar = {
      ...this.nuevaMoto,
      fo_categoria: Number(this.nuevaMoto.fo_categoria),
      cilindraje: Number(this.nuevaMoto.cilindraje),
      precio: Number(this.nuevaMoto.precio),
      stock: Number(this.nuevaMoto.stock),
    };

    this.motoService.insertar(motoParaEnviar).subscribe({
      next: (resultado: any) => {
        if (resultado.Resultado === 'OK') {
          this.consultarMotos();
          this.nuevaMoto = {
            marca: '',
            modelo: '',
            fo_categoria: '',
            cilindraje: null,
            precio: null,
            stock: 0,
          };
        } else {
          alert('Error en backend: ' + resultado.Mensaje);
        }
      },
      error: (err) => console.error('Error al registrar motocicleta:', err),
    });
  }

  borrarMoto(id: number) {
    if (window.confirm('¿Estás seguro de que deseas eliminar esta motocicleta?')) {
      this.motoService.eliminar(id).subscribe({
        next: (res: any) => {
          if (res.Resultado === 'OK') {
            this.consultarMotos();
          } else {
            alert('🚫 ' + res.Mensaje);
          }
        },
      });
    }
  }

  seleccionarMoto(moto: any) {
    this.nuevaMoto = { ...moto };
  }

  actualizarMotocicleta(id: number) {
    // CORRECCIÓN: Igual que en crear, aseguramos tipos numéricos
    const motoParaEnviar = {
      ...this.nuevaMoto,
      fo_categoria: Number(this.nuevaMoto.fo_categoria),
      cilindraje: Number(this.nuevaMoto.cilindraje),
      precio: Number(this.nuevaMoto.precio),
      stock: Number(this.nuevaMoto.stock),
    };

    this.motoService.editar(id, motoParaEnviar).subscribe({
      next: (res: any) => {
        if (res.Resultado === 'OK') {
          this.consultarMotos();
          this.nuevaMoto = {
            marca: '',
            modelo: '',
            fo_categoria: '',
            cilindraje: null,
            precio: null,
            stock: 0,
          };
        } else {
          alert('❌ Error: ' + res.Mensaje);
        }
      },
    });
  }
}
