import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';
import { MatIconModule } from '@angular/material/icon';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    MatCardModule,
    MatIconModule
  ],
  template: `
    <div class="dashboard-container">
      <h1>Dashboard</h1>
      
      <div class="stats-grid">
        <mat-card class="stat-card">
          <mat-card-header>
            <mat-icon mat-card-avatar>people</mat-icon>
            <mat-card-title>Clientes</mat-card-title>
          </mat-card-header>
          <mat-card-content>
            <div class="stat-number">{{ stats.clients }}</div>
            <div class="stat-label">Total de clientes</div>
          </mat-card-content>
        </mat-card>

        <mat-card class="stat-card">
          <mat-card-header>
            <mat-icon mat-card-avatar>collections</mat-icon>
            <mat-card-title>Coleções</mat-card-title>
          </mat-card-header>
          <mat-card-content>
            <div class="stat-number">{{ stats.collections }}</div>
            <div class="stat-label">Coleções ativas</div>
          </mat-card-content>
        </mat-card>

        <mat-card class="stat-card">
          <mat-card-header>
            <mat-icon mat-card-avatar>receipt</mat-icon>
            <mat-card-title>Notas</mat-card-title>
          </mat-card-header>
          <mat-card-content>
            <div class="stat-number">{{ stats.notes }}</div>
            <div class="stat-label">Notas do mês</div>
          </mat-card-content>
        </mat-card>

        <mat-card class="stat-card">
          <mat-card-header>
            <mat-icon mat-card-avatar>attach_money</mat-icon>
            <mat-card-title>Faturamento</mat-card-title>
          </mat-card-header>
          <mat-card-content>
            <div class="stat-number">R$ {{ stats.revenue | number:'1.2-2' }}</div>
            <div class="stat-label">Faturamento do mês</div>
          </mat-card-content>
        </mat-card>
      </div>
    </div>
  `,
  styles: [`
    .dashboard-container {
      padding: 1rem;
    }

    h1 {
      margin-bottom: 2rem;
      color: #333;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1rem;
    }

    .stat-card {
      cursor: pointer;
      transition: transform 0.2s ease-in-out;
    }

    .stat-card:hover {
      transform: translateY(-2px);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: #1976d2;
      margin: 0.5rem 0;
    }

    .stat-label {
      color: #666;
      font-size: 0.875rem;
    }
  `]
})
export class DashboardComponent {
  stats = {
    clients: 0,
    collections: 0,
    notes: 0,
    revenue: 0
  };

  ngOnInit() {
    // Load dashboard statistics
    this.loadStats();
  }

  loadStats() {
    // Mock data for now
    this.stats = {
      clients: 125,
      collections: 8,
      notes: 47,
      revenue: 15750.00
    };
  }
}