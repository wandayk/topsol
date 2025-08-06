import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet, RouterModule } from '@angular/router';
import { LucideAngularModule, Menu, Users, Package, FileText, DollarSign, BarChart3 } from 'lucide-angular';
import { ButtonComponent } from './ui/button.component';

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
    <div class="flex h-screen bg-background">
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
            <ui-button variant="ghost" size="sm">
              Configurações
            </ui-button>
            <ui-button variant="outline" size="sm">
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
  `,
})
export class AppComponent {
  title = 'TOP SOL - Sistema de Gestão';

  // Lucide icons
  MenuIcon = Menu;
  UsersIcon = Users;
  PackageIcon = Package;
  FileTextIcon = FileText;
  DollarSignIcon = DollarSign;
  BarChart3Icon = BarChart3;
}