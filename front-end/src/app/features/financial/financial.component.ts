import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Plus, Search, Filter, Calendar, TrendingUp, TrendingDown, DollarSign, CreditCard, Wallet, BarChart3, PieChart } from 'lucide-angular';
import { ButtonComponent } from '../../ui/button.component';
import { CardComponent, CardHeaderComponent, CardTitleComponent, CardDescriptionComponent, CardContentComponent } from '../../ui/card.component';
import { InputComponent } from '../../ui/input.component';

@Component({
  selector: 'app-financial',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    LucideAngularModule,
    ButtonComponent,
    CardComponent,
    CardHeaderComponent,
    CardTitleComponent,
    CardDescriptionComponent,
    CardContentComponent,
    InputComponent
  ],
  template: `
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Financeiro</h1>
          <p class="text-muted-foreground">Controle suas receitas e despesas</p>
        </div>
        <ui-button>
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Nova Transação
        </ui-button>
      </div>
      
      <!-- Financial Stats -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Receita Total</ui-card-title>
            <lucide-icon [img]="TrendingUpIcon" class="h-4 w-4 text-green-600"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold text-green-600">R$ {{ financialStats.totalRevenue | number:'1.2-2' }}</div>
            <p class="text-xs text-muted-foreground">+12% em relação ao mês anterior</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Despesas</ui-card-title>
            <lucide-icon [img]="TrendingDownIcon" class="h-4 w-4 text-red-600"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold text-red-600">R$ {{ financialStats.totalExpenses | number:'1.2-2' }}</div>
            <p class="text-xs text-muted-foreground">+3% em relação ao mês anterior</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Lucro Líquido</ui-card-title>
            <lucide-icon [img]="DollarSignIcon" class="h-4 w-4 text-blue-600"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold text-blue-600">R$ {{ financialStats.netProfit | number:'1.2-2' }}</div>
            <p class="text-xs text-muted-foreground">{{ getProfitPercentage() }}% margem</p>
          </ui-card-content>
        </ui-card>
        
        <ui-card>
          <ui-card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
            <ui-card-title class="text-sm font-medium">Contas a Receber</ui-card-title>
            <lucide-icon [img]="WalletIcon" class="h-4 w-4 text-orange-600"></lucide-icon>
          </ui-card-header>
          <ui-card-content>
            <div class="text-2xl font-bold text-orange-600">R$ {{ financialStats.accountsReceivable | number:'1.2-2' }}</div>
            <p class="text-xs text-muted-foreground">{{ getPendingCount() }} transações pendentes</p>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Filters -->
      <ui-card>
        <ui-card-content class="p-4">
          <div class="flex gap-4 items-center">
            <div class="relative flex-1 max-w-sm">
              <lucide-icon [img]="SearchIcon" size="16" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground"></lucide-icon>
              <ui-input
                type="text"
                placeholder="Buscar transações..."
                class="pl-9"
                [(ngModel)]="searchTerm"
                (input)="onSearch()"
              />
            </div>
            <ui-button variant="outline">
              <lucide-icon [img]="FilterIcon" size="16" class="mr-2"></lucide-icon>
              Tipo
            </ui-button>
            <ui-button variant="outline">
              <lucide-icon [img]="CalendarIcon" size="16" class="mr-2"></lucide-icon>
              Período
            </ui-button>
          </div>
        </ui-card-content>
      </ui-card>
      
      <!-- Charts and Recent Transactions -->
      <div class="grid gap-6 md:grid-cols-7">
        <!-- Chart Placeholder -->
        <ui-card class="md:col-span-4">
          <ui-card-header>
            <ui-card-title class="flex items-center gap-2">
              <lucide-icon [img]="BarChart3Icon" size="18"></lucide-icon>
              Fluxo de Caixa Mensal
            </ui-card-title>
            <ui-card-description>Receitas vs Despesas nos últimos 6 meses</ui-card-description>
          </ui-card-header>
          <ui-card-content class="pl-2">
            <div class="h-64 bg-muted rounded-lg flex items-center justify-center">
              <div class="text-center text-muted-foreground">
                <lucide-icon [img]="BarChart3Icon" size="48" class="mx-auto mb-2"></lucide-icon>
                <p>Gráfico de Fluxo de Caixa</p>
                <p class="text-sm">Implementar Chart.js ou similar</p>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
        
        <!-- Category Distribution -->
        <ui-card class="md:col-span-3">
          <ui-card-header>
            <ui-card-title class="flex items-center gap-2">
              <lucide-icon [img]="PieChartIcon" size="18"></lucide-icon>
              Distribuição por Categoria
            </ui-card-title>
            <ui-card-description>Despesas por categoria este mês</ui-card-description>
          </ui-card-header>
          <ui-card-content>
            <div class="space-y-4">
              <div *ngFor="let category of expenseCategories" class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-3 h-3 rounded-full" [style.background-color]="category.color"></div>
                  <span class="text-sm font-medium">{{ category.name }}</span>
                </div>
                <div class="text-right">
                  <div class="text-sm font-bold">R$ {{ category.amount | number:'1.2-2' }}</div>
                  <div class="text-xs text-muted-foreground">{{ category.percentage }}%</div>
                </div>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Recent Transactions -->
      <ui-card>
        <ui-card-header>
          <ui-card-title>Transações Recentes</ui-card-title>
          <ui-card-description>Suas últimas movimentações financeiras</ui-card-description>
        </ui-card-header>
        <ui-card-content>
          <div class="space-y-4">
            <div *ngFor="let transaction of filteredTransactions" class="flex items-center justify-between p-4 border border-border rounded-lg hover:bg-accent/50 transition-colors">
              <div class="flex items-center gap-4">
                <div 
                  class="w-10 h-10 rounded-full flex items-center justify-center"
                  [class]="getTransactionIconClasses(transaction.type)"
                >
                  <lucide-icon [img]="getTransactionIcon(transaction.type)" size="18"></lucide-icon>
                </div>
                <div>
                  <div class="font-medium">{{ transaction.description }}</div>
                  <div class="text-sm text-muted-foreground flex items-center gap-2">
                    <span>{{ transaction.category }}</span>
                    <span class="text-muted-foreground/60">•</span>
                    <span>{{ transaction.date | date:'dd/MM/yyyy' }}</span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <div 
                  class="font-bold"
                  [class]="transaction.type === 'income' ? 'text-green-600' : 'text-red-600'"
                >
                  {{ transaction.type === 'income' ? '+' : '-' }}R$ {{ transaction.amount | number:'1.2-2' }}
                </div>
                <div class="text-sm text-muted-foreground">{{ getStatusText(transaction.status) }}</div>
              </div>
            </div>
          </div>
        </ui-card-content>
      </ui-card>
    </div>
  `
})
export class FinancialComponent {
  searchTerm = '';
  
  financialStats = {
    totalRevenue: 15750.00,
    totalExpenses: 8240.50,
    netProfit: 7509.50,
    accountsReceivable: 2340.75
  };
  
  expenseCategories = [
    { name: 'Matéria Prima', amount: 3200.00, percentage: 39, color: '#3b82f6' },
    { name: 'Marketing', amount: 1800.00, percentage: 22, color: '#10b981' },
    { name: 'Operacional', amount: 1600.00, percentage: 19, color: '#f59e0b' },
    { name: 'Frete', amount: 980.50, percentage: 12, color: '#ef4444' },
    { name: 'Outros', amount: 660.00, percentage: 8, color: '#8b5cf6' }
  ];
  
  transactions = [
    {
      id: 1,
      description: 'Venda - Maria Silva',
      type: 'income',
      amount: 245.50,
      category: 'Vendas',
      date: new Date('2024-08-05'),
      status: 'completed'
    },
    {
      id: 2,
      description: 'Compra de Tecido',
      type: 'expense',
      amount: 850.00,
      category: 'Matéria Prima',
      date: new Date('2024-08-04'),
      status: 'completed'
    },
    {
      id: 3,
      description: 'Venda - João Santos',
      type: 'income',
      amount: 89.00,
      category: 'Vendas',
      date: new Date('2024-08-04'),
      status: 'pending'
    },
    {
      id: 4,
      description: 'Publicidade Instagram',
      type: 'expense',
      amount: 150.00,
      category: 'Marketing',
      date: new Date('2024-08-03'),
      status: 'completed'
    },
    {
      id: 5,
      description: 'Venda - Ana Costa',
      type: 'income',
      amount: 356.75,
      category: 'Vendas',
      date: new Date('2024-08-03'),
      status: 'completed'
    }
  ];
  
  filteredTransactions = [...this.transactions];
  
  // Lucide icons
  PlusIcon = Plus;
  SearchIcon = Search;
  FilterIcon = Filter;
  CalendarIcon = Calendar;
  TrendingUpIcon = TrendingUp;
  TrendingDownIcon = TrendingDown;
  DollarSignIcon = DollarSign;
  CreditCardIcon = CreditCard;
  WalletIcon = Wallet;
  BarChart3Icon = BarChart3;
  PieChartIcon = PieChart;
  
  onSearch() {
    const term = this.searchTerm.toLowerCase().trim();
    if (!term) {
      this.filteredTransactions = [...this.transactions];
      return;
    }
    
    this.filteredTransactions = this.transactions.filter(transaction =>
      transaction.description.toLowerCase().includes(term) ||
      transaction.category.toLowerCase().includes(term)
    );
  }
  
  getProfitPercentage(): number {
    if (this.financialStats.totalRevenue === 0) return 0;
    return Math.round((this.financialStats.netProfit / this.financialStats.totalRevenue) * 100);
  }
  
  getPendingCount(): number {
    return this.transactions.filter(t => t.status === 'pending').length;
  }
  
  getTransactionIcon(type: string): any {
    return type === 'income' ? this.TrendingUpIcon : this.TrendingDownIcon;
  }
  
  getTransactionIconClasses(type: string): string {
    return type === 'income' 
      ? 'bg-green-100 text-green-600' 
      : 'bg-red-100 text-red-600';
  }
  
  getStatusText(status: string): string {
    switch (status) {
      case 'completed':
        return 'Concluída';
      case 'pending':
        return 'Pendente';
      default:
        return 'Desconhecida';
    }
  }
}