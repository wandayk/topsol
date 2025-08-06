import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LucideAngularModule, Users, Package, FileText, DollarSign } from 'lucide-angular';
import { CardComponent, CardHeaderComponent, CardTitleComponent, CardDescriptionComponent, CardContentComponent } from '../../ui/card.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    LucideAngularModule,
    CardComponent,
    CardHeaderComponent,
    CardTitleComponent,
    CardDescriptionComponent,
    CardContentComponent
  ],
  template: `
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
        <p class="text-muted-foreground">Visão geral do seu negócio</p>
      </div>
      
      <!-- Stats Grid -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Clientes</ui-card-title>
            <lucide-icon [img]="UsersIcon" class="h-4 w-4 text-muted-foreground"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold">{{ stats.clients }}</div>
            <p class="text-xs text-muted-foreground">+2 desde ontem</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Coleções Ativas</ui-card-title>
            <lucide-icon [img]="PackageIcon" class="h-4 w-4 text-muted-foreground"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold">{{ stats.collections }}</div>
            <p class="text-xs text-muted-foreground">1 nova esta semana</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Notas do Mês</ui-card-title>
            <lucide-icon [img]="FileTextIcon" class="h-4 w-4 text-muted-foreground"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold">{{ stats.notes }}</div>
            <p class="text-xs text-muted-foreground">+12% em relação ao mês anterior</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Faturamento</ui-card-title>
            <lucide-icon [img]="DollarSignIcon" class="h-4 w-4 text-muted-foreground"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold">R$ {{ stats.revenue | number:'1.2-2' }}</div>
            <p class="text-xs text-muted-foreground">+8% em relação ao mês anterior</p>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Recent Activity -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
        <ui-card class="col-span-4">
          <ui-card-header>
            <ui-card-title>Vendas Recentes</ui-card-title>
            <ui-card-description>
              Você fez {{ stats.notes }} vendas este mês.
            </ui-card-description>
          </ui-card-header>
          <ui-card-content class="pl-2">
            <div class="space-y-8">
              <div class="flex items-center" *ngFor="let sale of recentSales">
                <div class="ml-4 space-y-1">
                  <p class="text-sm font-medium leading-none">{{ sale.client }}</p>
                  <p class="text-sm text-muted-foreground">{{ sale.items }}</p>
                </div>
                <div class="ml-auto font-medium">R$ {{ sale.amount | number:'1.2-2' }}</div>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
        
        <ui-card class="col-span-3">
          <ui-card-header>
            <ui-card-title>Produtos em Destaque</ui-card-title>
            <ui-card-description>
              Itens mais vendidos da semana
            </ui-card-description>
          </ui-card-header>
          <ui-card-content>
            <div class="space-y-8">
              <div class="flex items-center" *ngFor="let product of topProducts">
                <div class="ml-4 space-y-1">
                  <p class="text-sm font-medium leading-none">{{ product.name }}</p>
                  <p class="text-sm text-muted-foreground">{{ product.collection }}</p>
                </div>
                <div class="ml-auto font-medium">{{ product.sales }} vendas</div>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
      </div>
    </div>
  `,
})
export class DashboardComponent {
  stats = {
    clients: 125,
    collections: 8,
    notes: 47,
    revenue: 15750.00
  };

  recentSales = [
    { client: 'Maria Silva', items: '2 itens', amount: 125.50 },
    { client: 'João Santos', items: '1 item', amount: 89.00 },
    { client: 'Ana Costa', items: '3 itens', amount: 245.75 },
    { client: 'Pedro Lima', items: '1 item', amount: 95.00 },
  ];

  topProducts = [
    { name: 'Legging Basic', collection: 'Verão 2024', sales: 24 },
    { name: 'Top Fitness', collection: 'Verão 2024', sales: 18 },
    { name: 'Shorts High', collection: 'Sport Line', sales: 12 },
  ];

  // Lucide icons
  UsersIcon = Users;
  PackageIcon = Package;
  FileTextIcon = FileText;
  DollarSignIcon = DollarSign;

  ngOnInit() {
    this.loadStats();
  }

  loadStats() {
    // Mock data is already set above
  }
}