import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Plus, Search, MoreHorizontal, Calendar, Package2, TrendingUp, Eye } from 'lucide-angular';
import { ButtonComponent } from '../../../ui/button.component';
import { CardComponent, CardHeaderComponent, CardTitleComponent, CardDescriptionComponent, CardContentComponent } from '../../../ui/card.component';
import { InputComponent } from '../../../ui/input.component';

@Component({
  selector: 'app-collection-list',
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
          <h1 class="text-3xl font-bold tracking-tight">Coleções</h1>
          <p class="text-muted-foreground">Gerencie suas coleções e produtos</p>
        </div>
        <ui-button>
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Nova Coleção
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
                placeholder="Buscar coleções..."
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
      
      <!-- Collections Grid -->
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <ui-card *ngFor="let collection of filteredCollections" class="hover:shadow-md transition-shadow group">
          <ui-card-header>
            <div class="flex items-start justify-between">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <ui-card-title class="text-lg">{{ collection.name }}</ui-card-title>
                  <span 
                    class="px-2 py-1 text-xs rounded-full"
                    [class]="getStatusClasses(collection.status)"
                  >
                    {{ getStatusText(collection.status) }}
                  </span>
                </div>
                <ui-card-description>{{ collection.description }}</ui-card-description>
              </div>
              <ui-button variant="ghost" size="icon">
                <lucide-icon [img]="MoreHorizontalIcon" size="16"></lucide-icon>
              </ui-button>
            </div>
          </ui-card-header>
          
          <ui-card-content class="space-y-4">
            <!-- Collection Image Placeholder -->
            <div class="aspect-video bg-muted rounded-lg flex items-center justify-center">
              <lucide-icon [img]="Package2Icon" size="48" class="text-muted-foreground"></lucide-icon>
            </div>
            
            <!-- Collection Details -->
            <div class="space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-muted-foreground">Lançamento:</span>
                <span class="font-medium flex items-center gap-1">
                  <lucide-icon [img]="CalendarIcon" size="14"></lucide-icon>
                  {{ collection.launchDate | date:'MMM/yyyy' }}
                </span>
              </div>
              
              <div class="flex items-center justify-between text-sm">
                <span class="text-muted-foreground">Produtos:</span>
                <span class="font-medium flex items-center gap-1">
                  <lucide-icon [img]="Package2Icon" size="14"></lucide-icon>
                  {{ collection.itemCount }}
                </span>
              </div>
              
              <div class="flex items-center justify-between text-sm">
                <span class="text-muted-foreground">Vendas:</span>
                <span class="font-medium flex items-center gap-1 text-green-600">
                  <lucide-icon [img]="TrendingUpIcon" size="14"></lucide-icon>
                  {{ collection.salesCount }}
                </span>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
              <ui-button variant="outline" size="sm" class="flex-1">
                <lucide-icon [img]="EyeIcon" size="14" class="mr-2"></lucide-icon>
                Ver Detalhes
              </ui-button>
              <ui-button size="sm" class="flex-1">
                Editar
              </ui-button>
            </div>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Empty State -->
      <ui-card *ngIf="filteredCollections.length === 0" class="p-12 text-center">
        <div class="mx-auto w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4">
          <lucide-icon [img]="Package2Icon" size="24" class="text-muted-foreground"></lucide-icon>
        </div>
        <h3 class="text-lg font-semibold mb-2">Nenhuma coleção encontrada</h3>
        <p class="text-muted-foreground mb-6">
          {{ searchTerm ? 'Nenhuma coleção corresponde aos critérios de busca.' : 'Comece criando sua primeira coleção de produtos.' }}
        </p>
        <ui-button *ngIf="!searchTerm">
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Criar Primeira Coleção
        </ui-button>
      </ui-card>
    </div>
  `
})
export class CollectionListComponent {
  searchTerm = '';
  
  collections = [
    {
      id: 1,
      name: 'Verão 2024',
      description: 'Coleção fitness para o verão com tecidos leves e respiráveis',
      status: 'active',
      launchDate: new Date('2024-01-01'),
      itemCount: 24,
      salesCount: 156
    },
    {
      id: 2,
      name: 'Sport Line',
      description: 'Linha esportiva premium para alta performance',
      status: 'active',
      launchDate: new Date('2024-03-15'),
      itemCount: 18,
      salesCount: 89
    },
    {
      id: 3,
      name: 'Outono 2024',
      description: 'Coleção com tons terrosos e tecidos aquecidos',
      status: 'upcoming',
      launchDate: new Date('2024-04-01'),
      itemCount: 12,
      salesCount: 0
    },
    {
      id: 4,
      name: 'Basic Essentials',
      description: 'Peças básicas do dia a dia com excelente custo-benefício',
      status: 'active',
      launchDate: new Date('2023-08-01'),
      itemCount: 30,
      salesCount: 245
    },
    {
      id: 5,
      name: 'Inverno 2023',
      description: 'Coleção de inverno com tecnologia térmica',
      status: 'archived',
      launchDate: new Date('2023-06-01'),
      itemCount: 20,
      salesCount: 178
    }
  ];
  
  filteredCollections = [...this.collections];
  
  // Lucide icons
  PlusIcon = Plus;
  SearchIcon = Search;
  MoreHorizontalIcon = MoreHorizontal;
  CalendarIcon = Calendar;
  Package2Icon = Package2;
  TrendingUpIcon = TrendingUp;
  EyeIcon = Eye;
  
  onSearch() {
    const term = this.searchTerm.toLowerCase().trim();
    if (!term) {
      this.filteredCollections = [...this.collections];
      return;
    }
    
    this.filteredCollections = this.collections.filter(collection =>
      collection.name.toLowerCase().includes(term) ||
      collection.description.toLowerCase().includes(term)
    );
  }
  
  getStatusClasses(status: string): string {
    switch (status) {
      case 'active':
        return 'bg-green-100 text-green-700 border border-green-200';
      case 'upcoming':
        return 'bg-blue-100 text-blue-700 border border-blue-200';
      case 'archived':
        return 'bg-gray-100 text-gray-700 border border-gray-200';
      default:
        return 'bg-gray-100 text-gray-700 border border-gray-200';
    }
  }
  
  getStatusText(status: string): string {
    switch (status) {
      case 'active':
        return 'Ativa';
      case 'upcoming':
        return 'Em Breve';
      case 'archived':
        return 'Arquivada';
      default:
        return 'Desconhecido';
    }
  }
}