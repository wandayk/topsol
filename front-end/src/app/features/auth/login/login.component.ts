import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';
import { ToastService } from '../../../shared/services/toast.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="min-h-screen relative overflow-hidden">
      <!-- Video Background -->
      <video 
        autoplay 
        muted 
        loop 
        class="absolute inset-0 w-full h-full object-cover z-0"
      >
        <source src="assets/video.mp4" type="video/mp4">
      </video>
      
      <!-- Overlay for better contrast -->
      <div class="absolute inset-0 bg-black/40 z-10"></div>
      
      <!-- Login Container -->
      <div class="relative z-20 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
          <!-- Glass Container -->
          <div class="">
            <div class="text-center mb-8">
              <img src="assets/vekant-white.svg" alt="Logo" class="mx-auto h-12 w-auto" />
            </div>
            
            <form [formGroup]="loginForm" (ngSubmit)="onSubmit()" class="space-y-6">
              <div class="space-y-4">
                <div>
                  <label for="email" class="block text-sm font-medium text-white mb-2">
                    Email
                  </label>
                  <input
                    id="email"
                    name="email"
                    type="email"
                    formControlName="email"
                    required
                    class="w-full px-4 py-3 bg-white/20 backdrop-blur-sm border border-white/30 placeholder-white/70 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-200"
                    placeholder="Digite seu email"
                  />
                  <div *ngIf="loginForm.get('email')?.invalid && loginForm.get('email')?.touched" 
                       class="mt-2 text-sm text-red-300">
                    <div *ngIf="loginForm.get('email')?.errors?.['required']">Email é obrigatório</div>
                    <div *ngIf="loginForm.get('email')?.errors?.['email']">Email deve ser válido</div>
                  </div>
                </div>
                
                <div>
                  <label for="password" class="block text-sm font-medium text-white mb-2">
                    Senha
                  </label>
                  <input
                    id="password"
                    name="password"
                    type="password"
                    formControlName="password"
                    required
                    class="w-full px-4 py-3 bg-white/20 backdrop-blur-sm border border-white/30 placeholder-white/70 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-200"
                    placeholder="Digite sua senha"
                  />
                  <div *ngIf="loginForm.get('password')?.invalid && loginForm.get('password')?.touched" 
                       class="mt-2 text-sm text-red-300">
                    <div *ngIf="loginForm.get('password')?.errors?.['required']">Senha é obrigatória</div>
                    <div *ngIf="loginForm.get('password')?.errors?.['minlength']">Senha deve ter pelo menos 6 caracteres</div>
                  </div>
                </div>
              </div>

              <div>
                <button
                  type="submit"
                  [disabled]="loginForm.invalid || isLoading"
                  class="w-full flex justify-center py-3 px-4 mt-10 border border-transparent text-sm font-medium rounded-lg text-white bg-primary/80 backdrop-blur-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg"
                >
                  <span *ngIf="isLoading" class="mr-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </span>
                  {{ isLoading ? 'Entrando...' : 'Entrar' }}
                </button>
                <span class="block text-center text-sm text-white mt-4">Esqueceu sua senha? <a href="#" class="text-white underline">Recuperar</a></span>
              </div>
            </form>
          </div>
        </div>
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
    private router: Router,
    private toastService: ToastService
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

    // Test toast - remova depois de testar
    setTimeout(() => {
      this.toastService.info('Toast está funcionando!', 'Teste');
    }, 1000);
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
            this.toastService.success('Login realizado com sucesso!', 'Bem-vindo');
            this.router.navigate(['/dashboard']);
          } else {
            this.toastService.error(response.message || 'Erro ao fazer login', 'Erro de Login');
          }
        },
        error: (error) => {
          this.isLoading = false;
          if (error.error?.message) {
            this.toastService.error(error.error.message, 'Erro de Login');
          } else if (error.error?.errors) {
            // Handle validation errors
            const errors = Object.values(error.error.errors).flat();
            this.toastService.error(errors.join(', '), 'Erro de Validação');
          } else {
            this.toastService.error('Erro de conexão. Tente novamente.', 'Erro de Conexão');
          }
        }
      });
    }
  }
}