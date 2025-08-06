import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-note-list',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div>
      <h1>Lista de Notas</h1>
      <p>Componente em desenvolvimento...</p>
    </div>
  `
})
export class NoteListComponent {
}