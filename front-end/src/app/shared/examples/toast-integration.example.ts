// Exemplo de como integrar os toasts no seu app.component.ts

import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ToastContainerComponent } from './shared/components/toast-container/toast-container.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, ToastContainerComponent],
  template: `
    <router-outlet></router-outlet>
    <app-toast-container></app-toast-container>
  `
})
export class AppComponent {
  title = 'vekant';
}

// Exemplo de uso em qualquer componente:

import { ToastService } from './shared/services/toast.service';

export class ExampleComponent {
  constructor(private toastService: ToastService) {}

  showSuccessToast() {
    this.toastService.success('Operação realizada com sucesso!', 'Sucesso');
  }

  showErrorToast() {
    this.toastService.error('Algo deu errado!', 'Erro', 7000);
  }

  showWarningToast() {
    this.toastService.warning('Atenção! Verifique os dados.', 'Aviso');
  }

  showInfoToast() {
    this.toastService.info('Informação importante.', 'Info');
  }
}
