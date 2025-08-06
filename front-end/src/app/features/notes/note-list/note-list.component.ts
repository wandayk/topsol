import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Plus, Search, Filter, Calendar, User, Package, MoreHorizontal, FileText, CheckCircle, AlertCircle, Clock } from 'lucide-angular';
import { ButtonComponent } from '../../../ui/button.component';
import { CardComponent, CardHeaderComponent, CardTitleComponent, CardDescriptionComponent, CardContentComponent } from '../../../ui/card.component';
import { InputComponent } from '../../../ui/input.component';

@Component({
  selector: 'app-note-list',
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
          <h1 class="text-3xl font-bold tracking-tight">Notas</h1>
          <p class="text-muted-foreground">Gerencie pedidos e vendas</p>
        </div>
        <ui-button>
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Nova Nota
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
                placeholder="Buscar notas..."
                class="pl-9"
                [(ngModel)]="searchTerm"
                (input)="onSearch()"
              />
            </div>
            <ui-button variant="outline">
              <lucide-icon [img]="FilterIcon" size="16" class="mr-2"></lucide-icon>
              Filtros
            </ui-button>
            <ui-button variant="outline">
              <lucide-icon [img]="CalendarIcon" size="16" class="mr-2"></lucide-icon>
              Data
            </ui-button>
          </div>
        </ui-card-content>
      </ui-card>
      
      <!-- Notes List -->
      <div class="space-y-4">
        <ui-card *ngFor="let note of filteredNotes" class="hover:shadow-md transition-shadow">
          <ui-card-content class="p-6">
            <div class="flex items-start justify-between">
              <!-- Note Main Info -->
              <div class="flex-1 space-y-3">
                <div class="flex items-center gap-3">
                  <div class="flex items-center gap-2">
                    <lucide-icon [img]="FileTextIcon" size="16" class="text-muted-foreground"></lucide-icon>
                    <span class="font-semibold text-lg">Nota #{{ note.id }}</span>
                  </div>
                  <span 
                    class="px-2 py-1 text-xs rounded-full flex items-center gap-1"
                    [class]="getStatusClasses(note.status)"
                  >
                    <lucide-icon [img]="getStatusIcon(note.status)" size="12"></lucide-icon>
                    {{ getStatusText(note.status) }}
                  </span>
                </div>
                
                <!-- Client Info -->
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                  <lucide-icon [img]="UserIcon" size="14"></lucide-icon>
                  <span>{{ note.clientName }}</span>
                  <span class="text-muted-foreground/60">•</span>
                  <lucide-icon [img]="CalendarIcon" size="14"></lucide-icon>
                  <span>{{ note.date | date:'dd/MM/yyyy' }}</span>
                </div>
                
                <!-- Items Summary -->
                <div class="flex items-center gap-2 text-sm">
                  <lucide-icon [img]="PackageIcon" size="14" class="text-muted-foreground"></lucide-icon>
                  <span class="text-muted-foreground">{{ note.itemCount }} itens</span>
                  <span class="text-muted-foreground/60">•</span>
                  <span class="font-semibold text-green-600">R$ {{ note.total | number:'1.2-2' }}</span>
                </div>
                
                <!-- Items Preview -->
                <div class="flex flex-wrap gap-1 mt-2">
                  <span 
                    *ngFor="let item of note.items.slice(0, 3)" 
                    class="px-2 py-1 text-xs bg-secondary text-secondary-foreground rounded"
                  >
                    {{ item.name }} ({{ item.quantity }}x)
                  </span>
                  <span 
                    *ngIf="note.items.length > 3" 
                    class="px-2 py-1 text-xs bg-muted text-muted-foreground rounded"
                  >
                    +{{ note.items.length - 3 }} mais
                  </span>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="flex items-center gap-2">
                <ui-button variant="outline" size="sm">
                  Ver Detalhes
                </ui-button>
                <ui-button variant="ghost" size="icon">
                  <lucide-icon [img]="MoreHorizontalIcon" size="16"></lucide-icon>
                </ui-button>
              </div>
            </div>
          </ui-card-content>
        </ui-card>
      </div>
      
      <!-- Empty State -->
      <ui-card *ngIf="filteredNotes.length === 0" class="p-12 text-center">
        <div class="mx-auto w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4">
          <lucide-icon [img]="FileTextIcon" size="24" class="text-muted-foreground"></lucide-icon>
        </div>
        <h3 class="text-lg font-semibold mb-2">Nenhuma nota encontrada</h3>
        <p class="text-muted-foreground mb-6">
          {{ searchTerm ? 'Nenhuma nota corresponde aos critérios de busca.' : 'Comece criando sua primeira nota de venda.' }}
        </p>
        <ui-button *ngIf="!searchTerm">
          <lucide-icon [img]="PlusIcon" size="16" class="mr-2"></lucide-icon>
          Criar Primeira Nota
        </ui-button>
      </ui-card>
    </div>
  `
})
export class NoteListComponent {
  searchTerm = '';
  
  notes = [
    {
      id: 1001,
      clientName: 'Maria Silva',
      date: new Date('2024-08-05'),
      status: 'completed',
      itemCount: 3,
      total: 245.50,
      items: [
        { name: 'Legging Basic', quantity: 2 },
        { name: 'Top Fitness', quantity: 1 },
        { name: 'Shorts High', quantity: 1 }
      ]
    },
    {
      id: 1002,
      clientName: 'João Santos',
      date: new Date('2024-08-04'),
      status: 'pending',
      itemCount: 1,
      total: 89.00,
      items: [
        { name: 'Calça Sport', quantity: 1 }
      ]
    },
    {
      id: 1003,
      clientName: 'Ana Costa',
      date: new Date('2024-08-03'),
      status: 'processing',
      itemCount: 4,
      total: 356.75,
      items: [
        { name: 'Conjunto Verão', quantity: 1 },
        { name: 'Top Fitness', quantity: 2 },
        { name: 'Legging Premium', quantity: 1 }
      ]
    },
    {
      id: 1004,
      clientName: 'Pedro Lima',
      date: new Date('2024-08-02'),
      status: 'completed',
      itemCount: 2,
      total: 178.00,
      items: [
        { name: 'Shorts Basic', quantity: 2 }
      ]
    },
    {
      id: 1005,
      clientName: 'Juliana Oliveira',
      date: new Date('2024-08-01'),
      status: 'cancelled',
      itemCount: 5,
      total: 445.25,
      items: [
        { name: 'Conjunto Premium', quantity: 1 },
        { name: 'Legging Sport', quantity: 2 },
        { name: 'Top Basic', quantity: 2 }
      ]
    }
  ];
  
  filteredNotes = [...this.notes];
  
  // Lucide icons
  PlusIcon = Plus;
  SearchIcon = Search;
  FilterIcon = Filter;
  CalendarIcon = Calendar;
  UserIcon = User;
  PackageIcon = Package;
  MoreHorizontalIcon = MoreHorizontal;
  FileTextIcon = FileText;
  CheckCircleIcon = CheckCircle;
  AlertCircleIcon = AlertCircle;
  ClockIcon = Clock;
  
  onSearch() {
    const term = this.searchTerm.toLowerCase().trim();
    if (!term) {
      this.filteredNotes = [...this.notes];
      return;
    }
    
    this.filteredNotes = this.notes.filter(note =>
      note.clientName.toLowerCase().includes(term) ||
      note.id.toString().includes(term) ||
      note.items.some(item => item.name.toLowerCase().includes(term))
    );
  }
  
  getStatusClasses(status: string): string {
    switch (status) {
      case 'completed':
        return 'bg-green-100 text-green-700 border border-green-200';
      case 'pending':
        return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
      case 'processing':
        return 'bg-blue-100 text-blue-700 border border-blue-200';
      case 'cancelled':
        return 'bg-red-100 text-red-700 border border-red-200';
      default:
        return 'bg-gray-100 text-gray-700 border border-gray-200';
    }
  }
  
  getStatusText(status: string): string {
    switch (status) {
      case 'completed':
        return 'Concluída';
      case 'pending':
        return 'Pendente';
      case 'processing':
        return 'Processando';
      case 'cancelled':
        return 'Cancelada';
      default:
        return 'Desconhecido';
    }
  }
  
  getStatusIcon(status: string): any {
    switch (status) {
      case 'completed':
        return this.CheckCircleIcon;
      case 'pending':
        return this.ClockIcon;
      case 'processing':
        return this.AlertCircleIcon;
      case 'cancelled':
        return this.AlertCircleIcon;
      default:
        return this.ClockIcon;
    }
  }
}