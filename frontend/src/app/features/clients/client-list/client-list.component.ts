import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Plus, Search, MoreHorizontal, Mail, Phone, MapPin } from 'lucide-angular';
import { ButtonComponent } from '../../../ui/button.component';
import { CardComponent, CardHeaderComponent, CardTitleComponent, CardDescriptionComponent, CardContentComponent } from '../../../ui/card.component';
import { InputComponent } from '../../../ui/input.component';

@Component({
  selector: 'app-client-list',
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
          <h1 class="text-3xl font-bold tracking-tight">Clientes</h1>
          <p class="text-muted-foreground">Gerencie seus clientes e informações de contato</p>
        </div>
        <ui-button>
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Novo Cliente
        </ui-button>
      </div>
      
      <!-- Search and Filters -->
      <ui-card>
        <ui-card-content class="p-4">
          <div class="flex gap-4 items-center">
            <div class="relative flex-1 max-w-sm">
              <lucide-icon [img]="SearchIcon" size="16" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground"></lucide-icon>
              <ui-input
                type="text"
                placeholder="Buscar clientes..."
                class="pl-9"
                [(ngModel)]="searchTerm"
                (input)="onSearch()"
              />
            </div>
            <ui-button variant="outline">
              Filtros
            </ui-button>
          </div>
        </ui-card-content>
      </ui-card>
      
      <!-- Clients Grid -->
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <ui-card *ngFor="let client of filteredClients" class="hover:shadow-md transition-shadow">
          <ui-card-header>
            <div class="flex items-start justify-between">
              <div class="space-y-1">
                <ui-card-title class="text-lg">{{ client.name }}</ui-card-title>
                <ui-card-description>Cliente desde {{ client.createdAt | date:'MM/yyyy' }}</ui-card-description>
              </div>
              <ui-button variant="ghost" size="icon">
                <lucide-icon [img]="MoreHorizontalIcon" size="16"></lucide-icon>
              </ui-button>
            </div>
          </ui-card-header>
          
          <ui-card-content class="space-y-4">
            <!-- Contact Info -->
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-sm" *ngIf="client.email">
                <lucide-icon [img]="MailIcon" size="14" class="text-muted-foreground"></lucide-icon>
                <span class="text-muted-foreground">{{ client.email }}</span>
              </div>
              <div class="flex items-center gap-2 text-sm" *ngIf="client.phone">
                <lucide-icon [img]="PhoneIcon" size="14" class="text-muted-foreground"></lucide-icon>
                <span class="text-muted-foreground">{{ client.phone }}</span>
              </div>
              <div class="flex items-center gap-2 text-sm" *ngIf="client.city">
                <lucide-icon [img]="MapPinIcon" size="14" class="text-muted-foreground"></lucide-icon>
                <span class="text-muted-foreground">{{ client.city }}, {{ client.state }}</span>
              </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-border">
              <div class="text-center">
                <div class="text-2xl font-bold">{{ client.totalOrders }}</div>
                <div class="text-xs text-muted-foreground">Pedidos</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold">R$ {{ client.totalSpent | number:'1.2-2' }}</div>
                <div class="text-xs text-muted-foreground">Total Gasto</div>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Empty State -->
      <ui-card *ngIf="filteredClients.length === 0" class="p-12 text-center">
        <div class="mx-auto w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4">
          <lucide-icon [img]="SearchIcon" size="24" class="text-muted-foreground"></lucide-icon>
        </div>
        <h3 class="text-lg font-semibold mb-2">Nenhum cliente encontrado</h3>
        <p class="text-muted-foreground mb-6">
          {{ searchTerm ? 'Nenhum cliente corresponde aos critérios de busca.' : 'Comece adicionando seu primeiro cliente.' }}
        </p>
        <ui-button *ngIf="!searchTerm">
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Adicionar Primeiro Cliente
        </ui-button>
      </ui-card>
    </div>
  `
})
export class ClientListComponent {
  searchTerm = '';
  
  clients = [
    {
      id: 1,
      name: 'Maria Silva',
      email: 'maria.silva@email.com',
      phone: '(11) 99999-9999',
      city: 'São Paulo',
      state: 'SP',
      totalOrders: 12,
      totalSpent: 1250.00,
      createdAt: new Date('2024-01-15')
    },
    {
      id: 2,
      name: 'João Santos',
      email: 'joao.santos@email.com',
      phone: '(11) 88888-8888',
      city: 'Rio de Janeiro',
      state: 'RJ',
      totalOrders: 8,
      totalSpent: 890.00,
      createdAt: new Date('2024-02-20')
    },
    {
      id: 3,
      name: 'Ana Costa',
      email: 'ana.costa@email.com',
      phone: '(11) 77777-7777',
      city: 'Belo Horizonte',
      state: 'MG',
      totalOrders: 15,
      totalSpent: 1875.50,
      createdAt: new Date('2023-11-10')
    },
    {
      id: 4,
      name: 'Pedro Lima',
      email: 'pedro.lima@email.com',
      phone: '(11) 66666-6666',
      city: 'Porto Alegre',
      state: 'RS',
      totalOrders: 6,
      totalSpent: 675.25,
      createdAt: new Date('2024-03-05')
    },
    {
      id: 5,
      name: 'Juliana Oliveira',
      email: 'juliana.oliveira@email.com',
      phone: '(11) 55555-5555',
      city: 'Salvador',
      state: 'BA',
      totalOrders: 20,
      totalSpent: 2100.00,
      createdAt: new Date('2023-09-12')
    }
  ];
  
  filteredClients = [...this.clients];
  
  // Lucide icons
  PlusIcon = Plus;
  SearchIcon = Search;
  MoreHorizontalIcon = MoreHorizontal;
  MailIcon = Mail;
  PhoneIcon = Phone;
  MapPinIcon = MapPin;
  
  onSearch() {
    const term = this.searchTerm.toLowerCase().trim();
    if (!term) {
      this.filteredClients = [...this.clients];
      return;
    }
    
    this.filteredClients = this.clients.filter(client =>
      client.name.toLowerCase().includes(term) ||
      client.email.toLowerCase().includes(term) ||
      client.phone.includes(term) ||
      client.city.toLowerCase().includes(term)
    );
  }
}