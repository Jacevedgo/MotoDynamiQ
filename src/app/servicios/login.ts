import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class AuthService {
  // url = 'http://localhost/motodynamiq/backend/controladores/login.php';
  url = 'https://motodynamiq.store/backend/controladores/login.php';

  constructor(private http: HttpClient) {}

  login(identificacion: string, password: string) {
    return this.http.post(this.url, { identificacion, password });
  }
}
