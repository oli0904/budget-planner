import { Component, OnInit, inject } from '@angular/core';
import { ApiService } from './services/api.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App implements OnInit {

  private apiService = inject(ApiService);

  message = 'Connecting to Laravel...';

  ngOnInit(): void {
    this.apiService.getHello().subscribe({
      next: (response) => {
        this.message = response.message;
      },
      error: (error) => {
        console.error(error);
        this.message = 'Could not connect to Laravel.';
      }
    });
  }
}