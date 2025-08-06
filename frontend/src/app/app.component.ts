import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatSidenavModule } from '@angular/material/sidenav';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatSidenavModule
  ],
  template: `
    <div class="app-container">
      <mat-toolbar color="primary" class="app-toolbar">
        <button mat-icon-button (click)="toggleSidenav()">
          <mat-icon>menu</mat-icon>
        </button>
        <span class="app-title">TopSol</span>
        <span class="spacer"></span>
        <button mat-icon-button>
          <mat-icon>account_circle</mat-icon>
        </button>
      </mat-toolbar>

      <mat-sidenav-container class="app-sidenav-container">
        <mat-sidenav #sidenav mode="side" opened class="app-sidenav">
          <nav class="nav-menu">
            <a mat-button routerLink="/dashboard" class="nav-item">
              <mat-icon>dashboard</mat-icon>
              <span>Dashboard</span>
            </a>
            <a mat-button routerLink="/clients" class="nav-item">
              <mat-icon>people</mat-icon>
              <span>Clientes</span>
            </a>
            <a mat-button routerLink="/collections" class="nav-item">
              <mat-icon>collections</mat-icon>
              <span>Coleções</span>
            </a>
            <a mat-button routerLink="/notes" class="nav-item">
              <mat-icon>receipt</mat-icon>
              <span>Notas</span>
            </a>
            <a mat-button routerLink="/financial" class="nav-item">
              <mat-icon>attach_money</mat-icon>
              <span>Financeiro</span>
            </a>
          </nav>
        </mat-sidenav>

        <mat-sidenav-content class="app-content">
          <router-outlet></router-outlet>
        </mat-sidenav-content>
      </mat-sidenav-container>
    </div>
  `,
  styles: [`
    .app-container {
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .app-toolbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .app-title {
      font-size: 1.5rem;
      font-weight: 500;
    }

    .spacer {
      flex: 1 1 auto;
    }

    .app-sidenav-container {
      flex: 1;
      height: calc(100vh - 64px);
    }

    .app-sidenav {
      width: 250px;
    }

    .nav-menu {
      padding: 1rem 0;
    }

    .nav-item {
      width: 100%;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      text-align: left;
      justify-content: flex-start;
    }

    .nav-item mat-icon {
      margin-right: 8px;
    }

    .app-content {
      padding: 1rem;
    }
  `]
})
export class AppComponent {
  title = 'TopSol - Sistema de Gestão';

  toggleSidenav() {
    // Will implement sidenav toggle logic
  }
}