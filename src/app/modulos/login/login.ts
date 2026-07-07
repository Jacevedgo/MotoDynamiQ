import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
// CORRECCIÓN: Asegúrate que el archivo se llame login.ts y la clase AuthService
import { AuthService } from '../../servicios/login';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './login.html',
  styleUrl: './login.css',
})
export class Login {
  usuario = { identificacion: '', password: '' };

  constructor(
    private authService: AuthService, // Ahora coincide con el import
    private router: Router,
  ) {}

  iniciarSesion() {
    if (!this.usuario.identificacion || !this.usuario.password) {
      alert('Por favor ingrese todos los campos');
      return;
    }

    this.authService.login(this.usuario.identificacion, this.usuario.password).subscribe({
      next: (res: any) => {
        if (res.Resultado === 'OK') {
          localStorage.setItem('usuarioLogueado', JSON.stringify(res.Usuario));
          this.router.navigate(['/inicio']);
        } else {
          alert(res.Mensaje);
        }
      },
      error: (err) => {
        console.error(err);
        alert('Error al conectar con el servidor');
      },
    });
  }
}
