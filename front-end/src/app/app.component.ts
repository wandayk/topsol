import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet, RouterModule, Router } from '@angular/router';
import { LucideAngularModule, Menu, Users, Package, FileText, DollarSign, BarChart3, LogOut } from 'lucide-angular';
import { ButtonComponent } from './ui/button.component';
import { AuthService, User } from './features/auth/auth.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    RouterModule,
    LucideAngularModule,
    ButtonComponent
  ],
  template: `
    <div class="flex h-screen bg-background" *ngIf="currentUser$ | async as user; else loginView">
      <!-- Sidebar -->
      <aside class="w-64 bg-card border-r border-border">
        <div class="p-6">
          <h1 class="text-2xl font-bold text-foreground">TOP SOL</h1>
          <p class="text-sm text-muted-foreground">Sistema de Gestão</p>
        </div>
        
        <nav class="px-4 space-y-1">
          <a 
            routerLink="/dashboard" 
            routerLinkActive="bg-accent text-accent-foreground" 
            class="flex items-center gap-3 px-3 py-2 text-sm rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
          >
            <lucide-icon [img]="BarChart3Icon" size="18"></lucide-icon>
            Dashboard
          </a>
          
          <a 
            routerLink="/clients" 
            routerLinkActive="bg-accent text-accent-foreground"
            class="flex items-center gap-3 px-3 py-2 text-sm rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
          >
            <lucide-icon [img]="UsersIcon" size="18"></lucide-icon>
            Clientes
          </a>
          
          <a 
            routerLink="/collections" 
            routerLinkActive="bg-accent text-accent-foreground"
            class="flex items-center gap-3 px-3 py-2 text-sm rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
          >
            <lucide-icon [img]="PackageIcon" size="18"></lucide-icon>
            Coleções
          </a>
          
          <a 
            routerLink="/notes" 
            routerLinkActive="bg-accent text-accent-foreground"
            class="flex items-center gap-3 px-3 py-2 text-sm rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
          >
            <lucide-icon [img]="FileTextIcon" size="18"></lucide-icon>
            Notas
          </a>
          
          <a 
            routerLink="/financial" 
            routerLinkActive="bg-accent text-accent-foreground"
            class="flex items-center gap-3 px-3 py-2 text-sm rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
          >
            <lucide-icon [img]="DollarSignIcon" size="18"></lucide-icon>
            Financeiro
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="h-16 bg-card border-b border-border flex items-center justify-between px-6">
          <div class="flex items-center gap-4">
            <ui-button variant="ghost" size="icon" class="lg:hidden">
              <lucide-icon [img]="MenuIcon" size="18"></lucide-icon>
            </ui-button>
          </div>
          
          <div class="flex items-center gap-4">
            <span class="text-sm text-muted-foreground">
              Olá, {{ user.name }}
            </span>
            <ui-button 
              variant="outline" 
              size="sm"
              (click)="logout()"
              class="flex items-center gap-2"
            >
              <lucide-icon [img]="LogOutIcon" size="16"></lucide-icon>
              Sair
            </ui-button>
          </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
          <router-outlet></router-outlet>
        </div>
      </main>
    </div>

    <ng-template #loginView>
      <router-outlet></router-outlet>
    </ng-template>
  `,
})
export class AppComponent implements OnInit {
  title = 'TOP SOL';
  currentUser$: Observable<User | null>;

  // Lucide icons
  MenuIcon = Menu;
  UsersIcon = Users;
  PackageIcon = Package;
  FileTextIcon = FileText;
  DollarSignIcon = DollarSign;
  BarChart3Icon = BarChart3;
  LogOutIcon = LogOut;

  constructor(
    private authService: AuthService,
    private router: Router
  ) {
    this.currentUser$ = this.authService.currentUser$;
  }

  ngOnInit(): void {
    // Check if user is authenticated on app start
    this.currentUser$.subscribe(user => {
      if (!user && this.router.url !== '/login') {
        this.router.navigate(['/login']);
      }
    });
  }

  logout(): void {
    // Try normal logout first
    this.authService.logout().subscribe({
      next: () => {
        this.router.navigate(['/login']);
      },
      error: () => {
        // If normal logout fails, force logout and navigate
        this.authService.forceLogout();
        this.router.navigate(['/login']);
      }
    });
  }
}