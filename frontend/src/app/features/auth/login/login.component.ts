import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="min-h-screen flex items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center">
          <h2 class="mt-6 text-3xl font-bold text-foreground">
            TOP SOL
          </h2>
          <p class="mt-2 text-sm text-muted-foreground">
            Entre em sua conta
          </p>
        </div>
        
        <form [formGroup]="loginForm" (ngSubmit)="onSubmit()" class="mt-8 space-y-6">
          <div class="space-y-4">
            <div>
              <label for="email" class="block text-sm font-medium text-foreground">
                Email
              </label>
              <input
                id="email"
                name="email"
                type="email"
                formControlName="email"
                required
                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-border placeholder-muted-foreground text-foreground rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-background"
                placeholder="Digite seu email"
              />
              <div *ngIf="loginForm.get('email')?.invalid && loginForm.get('email')?.touched" 
                   class="mt-1 text-sm text-red-600">
                <div *ngIf="loginForm.get('email')?.errors?.['required']">Email é obrigatório</div>
                <div *ngIf="loginForm.get('email')?.errors?.['email']">Email deve ser válido</div>
              </div>
            </div>
            
            <div>
              <label for="password" class="block text-sm font-medium text-foreground">
                Senha
              </label>
              <input
                id="password"
                name="password"
                type="password"
                formControlName="password"
                required
                class="mt-1 appearance-none relative block w-full px-3 py-2 border border-border placeholder-muted-foreground text-foreground rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-background"
                placeholder="Digite sua senha"
              />
              <div *ngIf="loginForm.get('password')?.invalid && loginForm.get('password')?.touched" 
                   class="mt-1 text-sm text-red-600">
                <div *ngIf="loginForm.get('password')?.errors?.['required']">Senha é obrigatória</div>
                <div *ngIf="loginForm.get('password')?.errors?.['minlength']">Senha deve ter pelo menos 6 caracteres</div>
              </div>
            </div>
          </div>

          <div *ngIf="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
            {{ errorMessage }}
          </div>

          <div>
            <button
              type="submit"
              [disabled]="loginForm.invalid || isLoading"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span *ngIf="isLoading" class="mr-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ isLoading ? 'Entrando...' : 'Entrar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  `
})
export class LoginComponent implements OnInit {
  loginForm!: FormGroup;
  isLoading = false;
  errorMessage = '';

  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loginForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]]
    });

    // Redirect if already authenticated
    if (this.authService.isAuthenticated) {
      this.router.navigate(['/dashboard']);
    }
  }

  onSubmit(): void {
    if (this.loginForm.valid) {
      this.isLoading = true;
      this.errorMessage = '';

      const credentials = this.loginForm.value;

      this.authService.login(credentials).subscribe({
        next: (response) => {
          this.isLoading = false;
          if (response.status) {
            this.router.navigate(['/dashboard']);
          } else {
            this.errorMessage = response.message || 'Erro ao fazer login';
          }
        },
        error: (error) => {
          this.isLoading = false;
          if (error.error?.message) {
            this.errorMessage = error.error.message;
          } else if (error.error?.errors) {
            // Handle validation errors
            const errors = Object.values(error.error.errors).flat();
            this.errorMessage = errors.join(', ');
          } else {
            this.errorMessage = 'Erro de conexão. Tente novamente.';
          }
        }
      });
    }
  }
}